<?php

namespace App\Http\Controllers;

use App\Models\PremiumSlot;
use App\Models\SlotBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SlotBookingController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchant;

        // Alle 7 Slots mit aktueller Belegung + Queue-Länge
        $slots = PremiumSlot::orderBy('zone')->orderBy('position')->get()
            ->map(function ($slot) {
                $current = $slot->currentBooking();
                $slot->current_booking = $current;
                $slot->queue_length = $slot->bookings()
                    ->whereIn('status', ['pending', 'approved'])
                    ->count();
                // verbleibende Tage der aktuellen Belegung
                $slot->days_remaining = $current && $current->ends_at
                    ? max(0, now()->diffInDays($current->ends_at, false))
                    : null;
                return $slot;
            });

        // Eigene aktive Ads fürs Dropdown
        $myAds = $merchant->ads()->where('status', 'active')->get(['id', 'title']);

        // Eigene laufende/wartende Buchungen (Übersicht "meine Anträge")
        $myBookings = SlotBooking::with(['slot', 'ad'])
            ->where('merchant_id', $merchant->id)
            ->whereIn('status', ['pending', 'approved', 'live'])
            ->orderByDesc('created_at')
            ->get();

        // Slot-Daten für JS (Live-Preisberechnung im Formular)
        $slotsJs = $slots->map(fn($s) => [
            'id'            => $s->id,
            'zone'          => $s->zone,
            'position'      => $s->position,
            'price'         => $s->effective_price_cents,
            'base_price'    => $s->base_price_cents,
            'has_discount'  => $s->has_discount,
            'discount_pct'  => $s->discount_pct,
            'queue_length'  => $s->queue_length,
            'is_empty'      => $s->is_empty,
        ])->values();

        return view('slots.index', compact('slots', 'myAds', 'myBookings', 'slotsJs'));
    }

    public function store(Request $request)
    {
        $merchant = Auth::user()->merchant;

        $validated = $request->validate([
            'premium_slot_id' => ['required', 'exists:premium_slots,id'],
            'ad_id'           => ['required', 'exists:ads,id'],
            'duration_days'   => ['required', 'integer', 'min:1', 'max:7'],
        ]);

        // Ad muss dem Merchant gehören + aktiv sein
        $ad = $merchant->ads()->where('id', $validated['ad_id'])->where('status', 'active')->first();
        abort_if(!$ad, 422, 'Ad nicht gefunden oder nicht aktiv.');

        // ── Transaktion + Lock gegen Race Condition bei gleichzeitiger Buchung ──
        return DB::transaction(function () use ($merchant, $validated) {
            // Slot mit Lock laden — verhindert dass zwei Anträge dieselbe Queue-Position bekommen
            $slot = PremiumSlot::lockForUpdate()->findOrFail($validated['premium_slot_id']);

            // ── Wiederbuchungs-Sperre: kein zweiter offener Antrag desselben Merchants für denselben Slot ──
            $existingOpen = SlotBooking::where('premium_slot_id', $slot->id)
                ->where('merchant_id', $merchant->id)
                ->whereIn('status', ['pending', 'approved', 'live'])
                ->exists();

            if ($existingOpen) {
                return back()->withErrors([
                    'slot' => 'Du hast bereits einen offenen Antrag oder eine laufende Buchung für diesen Slot.',
                ]);
            }

            // ── Queue-Position: nächste freie Position in der Warteschlange ──
            $queuePosition = SlotBooking::where('premium_slot_id', $slot->id)
                ->whereIn('status', ['pending', 'approved'])
                ->max('queue_position');
            $queuePosition = ($queuePosition ?? 0) + 1;

            // ── Preis: effektiver Tagespreis (inkl. Rabatt) × Laufzeit ──
            $totalCents = $slot->effective_price_cents * $validated['duration_days'];

            SlotBooking::create([
                'premium_slot_id' => $slot->id,
                'merchant_id'     => $merchant->id,
                'ad_id'           => $validated['ad_id'],
                'duration_days'   => $validated['duration_days'],
                'total_cents'     => $totalCents,
                'status'          => 'pending',
                'queue_position'  => $queuePosition,
                // starts_at/ends_at bleiben null — werden bei Admin-Approval gesetzt (v2)
            ]);

            return back()->with('status', 'slot-booked');
        });
    }
}

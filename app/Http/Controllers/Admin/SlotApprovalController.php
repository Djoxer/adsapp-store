<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlotBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlotApprovalController extends Controller
{
    public function index()
    {
        // Pending zuerst (Handlungsbedarf), dann der Rest als Kontext
        $pending = SlotBooking::with(['slot', 'ad', 'merchant.user'])
            ->where('status', 'pending')
            ->orderBy('premium_slot_id')
            ->orderBy('queue_position')
            ->get();

        $active = SlotBooking::with(['slot', 'ad', 'merchant.user'])
            ->whereIn('status', ['approved', 'live'])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        return view('admin.slots', compact('pending', 'active'));
    }

    public function approve(SlotBooking $booking)
    {
        abort_if($booking->status !== 'pending', 422, 'Nur pending-Anträge können genehmigt werden.');

        return DB::transaction(function () use ($booking) {
            // Slot sperren während wir die Startzeit berechnen
            $slot = $booking->slot()->lockForUpdate()->first();

            // Startzeitpunkt = Ende der letzten belegenden/genehmigten Buchung, sonst jetzt
            $lastEnd = SlotBooking::where('premium_slot_id', $slot->id)
                ->whereIn('status', ['live', 'approved'])
                ->whereNotNull('ends_at')
                ->max('ends_at');

            $startsAt = $lastEnd && now()->lt($lastEnd) ? \Carbon\Carbon::parse($lastEnd) : now();
            $endsAt   = (clone $startsAt)->addDays($booking->duration_days);

            // Wenn sofort startend → direkt live, sonst approved (wartet auf Startzeit)
            $status = $startsAt->lessThanOrEqualTo(now()) ? 'live' : 'approved';

            $booking->update([
                'status'    => $status,
                'starts_at' => $startsAt,
                'ends_at'   => $endsAt,
            ]);

            return back()->with('status', 'slot-approved');
        });
    }

    public function reject(Request $request, SlotBooking $booking)
    {
        abort_if($booking->status !== 'pending', 422);

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->update([
            'status'          => 'rejected',
            'rejected_reason' => $request->reason,
        ]);

        return back()->with('status', 'slot-rejected');
    }
}

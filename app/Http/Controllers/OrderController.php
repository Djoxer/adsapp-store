<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ad;
use App\Models\AdEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchant;

        // Leads = dwell-Events (Klicks zum Händler) auf eigene Ads
        $leads = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->leftJoin('users', 'ad_events.user_id', '=', 'users.id')
            ->where('ads.merchant_id', $merchant->id)
            ->where('ad_events.event_type', 'dwell')
            ->orderByDesc('ad_events.created_at')
            ->select(
                'ad_events.id',
                'ads.title as ad_title',
                'ads.id as ad_id',
                'users.email as buyer_email',
                'ad_events.created_at'
            )
            ->paginate(15);

        // Stats
        $totalLeads = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->where('ads.merchant_id', $merchant->id)
            ->where('ad_events.event_type', 'dwell')
            ->count();

        $leadsToday = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->where('ads.merchant_id', $merchant->id)
            ->where('ad_events.event_type', 'dwell')
            ->whereDate('ad_events.created_at', today())
            ->count();

        return view('orders.index', compact('leads', 'totalLeads', 'leadsToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad_id'           => ['required', 'exists:ads,id'],
            'shipping_choice' => ['required', 'in:standard,express'],
        ]);

        $ad = Ad::findOrFail($request->ad_id);

        abort_if($ad->status !== 'active', 422);

        // Versandkosten — später aus Sendcloud, erstmal fix
        $shippingCents = $request->shipping_choice === 'express' ? 800 : 399;

        $order = Order::create([
            'user_id'         => Auth::id(),
            'ad_id'           => $ad->id,
            'merchant_id'     => $ad->merchant_id,
            'status'          => 'pending',
            'total_cents'     => $ad->price_cents + $shippingCents,
            'shipping_choice' => [
                'type'         => $request->shipping_choice,
                'cost_cents'   => $shippingCents,
            ],
        ]);

        // Sale-Event tracken → Score-Boost
        AdEvent::create([
            'ad_id'      => $ad->id,
            'event_type' => 'sale',
            'user_id'    => Auth::id(),
            'ip_hash'    => hash('sha256', $request->ip()),
        ]);

        return redirect()->route('orders.confirmation', $order)
            ->with('status', 'order-placed');
    }

    public function confirmation(Order $order)
    {
        // Nur eigene Orders einsehen
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load(['ad.merchant', 'ad.images']);

        return view('orders.confirmation', compact('order'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ad;
use App\Models\AdEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
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

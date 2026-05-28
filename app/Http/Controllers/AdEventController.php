<?php

namespace App\Http\Controllers;

use App\Models\AdEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdEventController extends Controller
{
    public function track(Request $request)
    {
        $request->validate([
            'ad_id'      => 'required|exists:ads,id',
            'event_type' => 'required|in:view,dwell,bounce,sale,refund',
        ]);

        AdEvent::create([
            'ad_id'      => $request->ad_id,
            'event_type' => $request->event_type,
            'user_id'    => Auth::id(),
            'ip_hash'    => hash('sha256', $request->ip()),
        ]);

        return response()->json(['ok' => true]);
    }
}

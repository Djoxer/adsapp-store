<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Nur Merchants/Agencies haben Leads — Admin (kein merchant) wird abgewiesen
        abort_unless($user->merchant, 403, 'Kein Händler-Profil.');

        // WICHTIG: alten seen-Zeitpunkt VOR dem Update lesen,
        // damit die "NEU"-Markierung in der View noch greift
        $seenAt = $user->notifications_seen_at ?? $user->created_at;

        $leads = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->leftJoin('users', 'ad_events.user_id', '=', 'users.id')
            ->where('ads.merchant_id', $user->merchant->id)
            ->where('ad_events.event_type', 'dwell')
            ->orderByDesc('ad_events.created_at')
            ->limit(20)
            ->select(
                'ad_events.id',
                'ads.title as ad_title',
                'ads.id as ad_id',
                'users.email as buyer_email',
                'ad_events.created_at'
            )
            ->get();

        // Jetzt als gesehen markieren — nach dem Lesen des alten Werts
        $user->update(['notifications_seen_at' => now()]);

        return view('notifications.index', compact('leads', 'seenAt'));
    }
}

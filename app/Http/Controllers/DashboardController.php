<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ad;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchant;

        // Admin/Buyer haben keinen Merchant-Record → weiterleiten statt crashen
        abort_unless($merchant, 403, 'Kein Händler-Profil für diesen Account.');

        // ── KPI 1: Aktive Ads ──────────────────────────────────────────
        $activeAdsCount = $merchant->ads()->where('status', 'active')->count();

        // ── KPI 2: Leads heute (click-Events = dwell auf eigene Ads) ───
        $leadsToday = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->where('ads.merchant_id', $merchant->id)
            ->where('ad_events.event_type', 'dwell') // dwell = Klick zum Händler
            ->whereDate('ad_events.created_at', today())
            ->count();

        $leadsYesterday = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->where('ads.merchant_id', $merchant->id)
            ->where('ad_events.event_type', 'dwell')
            ->whereDate('ad_events.created_at', today()->subDay())
            ->count();

        $leadsDelta = $leadsYesterday > 0
            ? round((($leadsToday - $leadsYesterday) / $leadsYesterday) * 100, 1)
            : null;

        // ── KPI 3: Views gesamt (letzte 30 Tage) ───────────────────────
        $viewsTotal = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->where('ads.merchant_id', $merchant->id)
            ->where('ad_events.event_type', 'view')
            ->where('ad_events.created_at', '>=', now()->subDays(30))
            ->count();

        // ── KPI 4: Durchschnittlicher Score ────────────────────────────
        $scoreAvg = $merchant->ads()
            ->where('status', 'active')
            ->avg('current_score') ?? 0;

        // ── Top 5 Ads nach Score ────────────────────────────────────────
        $topAds = $merchant->ads()
            ->with('images')
            ->where('status', 'active')
            ->orderByDesc('current_score')
            ->limit(5)
            ->get()
            ->map(function ($ad, $i) use ($merchant) {
                // CTR = dwell / view Events der letzten 30 Tage
                $views = DB::table('ad_events')
                    ->where('ad_id', $ad->id)
                    ->where('event_type', 'view')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count();
                $clicks = DB::table('ad_events')
                    ->where('ad_id', $ad->id)
                    ->where('event_type', 'dwell')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count();
                $ad->ctr    = $views > 0 ? round(($clicks / $views) * 100, 1) . '%' : '—';
                $ad->rank   = $i + 1;
                return $ad;
            });

        // ── Performance Chart: Views + Clicks letzte 30 Tage ───────────
        $chartDays = collect(range(29, 0))->map(function ($daysAgo) use ($merchant) {
            $date = today()->subDays($daysAgo)->toDateString();
            $views = DB::table('ad_events')
                ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
                ->where('ads.merchant_id', $merchant->id)
                ->where('ad_events.event_type', 'view')
                ->whereDate('ad_events.created_at', $date)
                ->count();
            $clicks = DB::table('ad_events')
                ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
                ->where('ads.merchant_id', $merchant->id)
                ->where('ad_events.event_type', 'dwell')
                ->whereDate('ad_events.created_at', $date)
                ->count();
            return compact('date', 'views', 'clicks');
        });

        // Chart-Werte normalisiert auf 0–100% für Balkenhöhe
        $maxViews  = max($chartDays->max('views'), 1);
        $maxClicks = max($chartDays->max('clicks'), 1);
        $chartData = $chartDays->map(fn($d) => [
            'views_pct'  => round(($d['views']  / $maxViews)  * 100),
            'clicks_pct' => round(($d['clicks'] / $maxClicks) * 100),
            'date'       => $d['date'],
        ]);

        // ── Letzte Leads / Orders ───────────────────────────────────────
        $recentLeads = DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->leftJoin('users', 'ad_events.user_id', '=', 'users.id')
            ->where('ads.merchant_id', $merchant->id)
            ->where('ad_events.event_type', 'dwell')
            ->orderByDesc('ad_events.created_at')
            ->limit(5)
            ->select(
                'ad_events.id',
                'ads.title as ad_title',
                'users.email as buyer_email',
                'ad_events.created_at'
            )
            ->get();

        return view('dashboard', compact(
            'activeAdsCount', 'leadsToday', 'leadsDelta',
            'viewsTotal', 'scoreAvg', 'topAds', 'chartData', 'recentLeads'
        ));
    }
}

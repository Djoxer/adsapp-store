<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ad;

class AnalyticsController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchant;
        $adIds = $merchant->ads()->pluck('id');

        // ── Helper: Event-Count im Zeitfenster ──
        $countEvents = fn($type, $since = null) => DB::table('ad_events')
            ->whereIn('ad_id', $adIds)
            ->where('event_type', $type)
            ->when($since, fn($q) => $q->where('created_at', '>=', $since))
            ->count();

        // ── KPI-Block (echt) ──
        $kpis = [
            'views_30'   => $countEvents('view',  now()->subDays(30)),
            'leads_30'   => $countEvents('dwell', now()->subDays(30)),
            'sales_30'   => $countEvents('sale',  now()->subDays(30)),
            'views_total'=> $countEvents('view'),
        ];

        // Conversion: Sales / Leads (dwell)
        $kpis['conversion'] = $kpis['leads_30'] > 0
            ? round(($kpis['sales_30'] / $kpis['leads_30']) * 100, 1)
            : 0;

        // ── 30-Tage-Verlauf: Views, Leads, Sales pro Tag (echt) ──
        $timeline = collect(range(29, 0))->map(function ($daysAgo) use ($adIds) {
            $date = today()->subDays($daysAgo)->toDateString();
            $dayEvents = fn($type) => DB::table('ad_events')
                ->whereIn('ad_id', $adIds)
                ->where('event_type', $type)
                ->whereDate('created_at', $date)
                ->count();
            return [
                'date'   => $date,
                'views'  => $dayEvents('view'),
                'leads'  => $dayEvents('dwell'),
                'sales'  => $dayEvents('sale'),
            ];
        });

        // Normalisierung für Chart-Höhen
        $maxViews = max($timeline->max('views'), 1);
        $maxLeads = max($timeline->max('leads'), 1);
        $chartData = $timeline->map(fn($d) => [
            'date'       => $d['date'],
            'views_pct'  => round(($d['views'] / $maxViews) * 100),
            'leads_pct'  => round(($d['leads'] / $maxLeads) * 100),
            'views'      => $d['views'],
            'leads'      => $d['leads'],
            'sales'      => $d['sales'],
        ]);

        // ── Pro-Ad-Breakdown (echt) ──
        $adBreakdown = $merchant->ads()
            ->with('images')
            ->where('status', 'active')
            ->orderByDesc('current_score')
            ->get()
            ->map(function ($ad) {
                $views  = DB::table('ad_events')->where('ad_id', $ad->id)->where('event_type','view')->where('created_at','>=',now()->subDays(30))->count();
                $leads  = DB::table('ad_events')->where('ad_id', $ad->id)->where('event_type','dwell')->where('created_at','>=',now()->subDays(30))->count();
                $sales  = DB::table('ad_events')->where('ad_id', $ad->id)->where('event_type','sale')->where('created_at','>=',now()->subDays(30))->count();
                $ad->v_views = $views;
                $ad->v_leads = $leads;
                $ad->v_sales = $sales;
                $ad->v_ctr   = $views > 0 ? round(($leads/$views)*100,1) : 0;
                return $ad;
            });

        // ── Markt-Position: Perzentil im Gesamt-Ranking (echt) ──
        // Wie viele aktive Ads gibt es insgesamt, und wo stehen meine?
        $totalActiveAds = Ad::where('status','active')->count();

        // Bester eigener Score + globaler Rang dieser Ad
        $myBestAd = $merchant->ads()->where('status','active')->orderByDesc('current_score')->first();
        $marketPosition = null;
        if ($myBestAd && $totalActiveAds > 0) {
            $betterCount = Ad::where('status','active')
                ->where('current_score', '>', $myBestAd->current_score)
                ->count();
            $rank = $betterCount + 1;
            $percentile = round((1 - ($betterCount / $totalActiveAds)) * 100);
            $marketPosition = [
                'rank'       => $rank,
                'total'      => $totalActiveAds,
                'percentile' => $percentile,
                'best_ad'    => $myBestAd->title,
                'best_score' => $myBestAd->current_score,
            ];
        }

        // Durchschnitts-Score aller aktiven Ads (Markt) vs. meiner
        $marketAvgScore = Ad::where('status','active')->avg('current_score') ?? 0;
        $myAvgScore     = $merchant->ads()->where('status','active')->avg('current_score') ?? 0;

        return view('analytics.index', compact(
            'kpis', 'chartData', 'adBreakdown',
            'marketPosition', 'marketAvgScore', 'myAvgScore'
        ));
    }
}

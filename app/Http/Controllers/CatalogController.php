<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index()
    {
        // Alle aktiven Ads nach Score, eager-load Relations
        $ads = Ad::with(['merchant', 'category', 'images'])
            ->where('status', 'active')
            ->orderByDesc('current_score')
            ->get();

        // Top 3 → Premium-Strip (ersetzt Dummy-Sponsored-Slots)
        $premiumAds = $ads->take(3);

        // Rang 4–N → organisches Grid
        $organicAds = $ads->skip(3);

        // Hotspot = höchster Score (= $ads->first())
        $hotspot = $ads->first();

        // Right Panel = 4 zufällige aktive Ads
        $rightPanelAds = Ad::with(['images'])
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Gebookmarkte IDs für Overlay-State
        $bookmarkedIds = Auth::user()->bookmarks()->pluck('ad_id')->toArray();

        return view('catalog.index', compact(
            'premiumAds', 'organicAds', 'hotspot', 'rightPanelAds', 'bookmarkedIds'
        ));
    }

    public function ranking()
    {
        return view('catalog.ranking');
    }

    public function hotspots()
    {
        return view('catalog.hotspots');
    }

    public function analytics()
    {
        return view('catalog.analytics');
    }
}

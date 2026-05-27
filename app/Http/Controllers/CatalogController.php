<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\PremiumSlot;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index()
    {
        // Premium Strip — nur gebuchte, aktive, zeitlich gültige Slots
        $premiumAds = PremiumSlot::with(['ad.merchant', 'ad.images'])
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->limit(3)
            ->get()
            ->map(fn($slot) => $slot->ad) // nur das Ad-Model rauslösen
            ->filter(); // null rausfiltern falls ad gelöscht

        // Organic Grid — alle aktiven Ads nach Score, komplett unabhängig
        $ads = Ad::with(['merchant', 'category', 'images'])
            ->where('status', 'active')
            ->orderByDesc('current_score')
            ->get();

        $organicAds   = $ads;
        $hotspot      = $ads->first();
        $rightPanelAds = Ad::with(['images'])
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get();

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

<?php
namespace App\Http\Controllers;

use App\Models\Hotspot;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class HotspotController extends Controller
{
    // /hotspots — Übersichtsseite
    public function index()
    {
        $active   = Hotspot::active()->withCount('ads')->get();
        $upcoming = Hotspot::upcoming()->get();
        $archived = Hotspot::archived()->latest('closes_at')->take(8)->get();

        $stats = [
            'active_nodes'  => $active->count(),
            'pending_queue' => $upcoming->count(),
            'total_volume'  => $active->sum('ads_count'),
            'uptime'        => '99.98',
        ];

        return view('catalog.hotspots', compact('active','upcoming','archived','stats'));
    }

    // /hotspots/{slug} — Detail mit allen Ads
    public function show(string $slug)
    {
        $hotspot = Hotspot::where('slug', $slug)
            ->withCount('ads')
            ->firstOrFail();

        $bookmarkedIds = Auth::check()
            ? Bookmark::where('user_id', Auth::id())->pluck('ad_id')->toArray()
            : [];

        $ads = $hotspot->ads()
            ->where('ads.status', 'active')
            ->orderByDesc('current_score')
            ->get();

        return view('catalog.hotspot-detail', compact('hotspot', 'ads', 'bookmarkedIds'));
    }
}

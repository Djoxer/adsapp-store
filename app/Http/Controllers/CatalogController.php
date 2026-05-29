<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Hotspot;
use App\Models\PremiumSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // ── Filter-Parameter aus GET ────────────────────────────────
        $q        = trim($request->get('q', ''));
        $catSlug  = $request->get('category', '');
        $sort     = $request->get('sort', 'score'); // score|newest|price_asc|price_desc

        // ── Basis-Query ─────────────────────────────────────────────
        $query = Ad::with(['merchant', 'category', 'images'])
            ->where('status', 'active');

        // Suche in Titel + Beschreibung
        if ($q !== '') {
            $query->where(function ($q2) use ($q) {
                $q2->where('title', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%");
            });
        }

        // Kategorie-Filter via Slug
        if ($catSlug !== '') {
            $query->whereHas('category', fn($q2) => $q2->where('slug', $catSlug));
        }

        // Sortierung
        $query = match($sort) {
            'newest'     => $query->orderByDesc('created_at'),
            'price_asc'  => $query->orderBy('price_cents'),
            'price_desc' => $query->orderByDesc('price_cents'),
            default      => $query->orderByDesc('current_score'), // 'score'
        };

        $ads = $query->get();

        // ── Catalog-Zonen ────────────────────────────────────────────
        // Premium Strip — live geschaltete Slot-Buchungen
        $premiumAds = ($q === '' && $catSlug === '')
            ? \App\Models\SlotBooking::live()
                ->with(['ad.merchant', 'ad.images'])
                ->orderBy('total_cents', 'desc') // teuerste/prominenteste zuerst
                ->limit(3)
                ->get()
                ->map(fn($booking) => $booking->ad)
                ->filter()
            : collect();

        $organicAds    = $ads;
        // Featured-Hotspot für den großen Banner: aktiver Hotspot mit nächstem Ablauf
        // (null closes_at = dauerhaft → ans Ende, damit befristete zuerst dran sind)
        $featuredHotspot = ($q === '' && $catSlug === '')
            ? \App\Models\Hotspot::active()
                ->withCount('ads')
                ->orderByRaw('closes_at IS NULL, closes_at ASC')
                ->first()
            : null;
        $rightPanelAds = Ad::with(['images'])
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $bookmarkedIds = Auth::user()->bookmarks()->pluck('ad_id')->toArray();

        // Aktive Hotspots für Catalog-Einbindung (Right-Panel + Inline-Einstreuung)
        $catalogHotspots = \App\Models\Hotspot::active()
            ->withCount('ads')
            ->orderByDesc('opens_at')
            ->get();

        // ── Für Filter-Bar ───────────────────────────────────────────
        $categories = Category::orderBy('name')->get();

        // Aktive Kategorie als Objekt (für Highlighting in der Filter-Bar)
        $activeCategory = $catSlug !== ''
            ? $categories->firstWhere('slug', $catSlug)
            : null;

        return view('catalog.index', compact(
            'premiumAds', 'organicAds', 'featuredHotspot', 'rightPanelAds',
            'bookmarkedIds', 'categories', 'activeCategory', 'q', 'sort', 'catalogHotspots'
        ));
    }

    public function ranking(Request $request)
    {
        $period = $request->get('period', 'today'); // today|7d|30d|all

        $since = match($period) {
            '7d'  => now()->subDays(7),
            '30d' => now()->subDays(30),
            'all' => null,
            default => today(),
        };

        $ads = Ad::with(['merchant', 'category', 'images'])
            ->where('status', 'active')
            ->withCount([
                'events as sales_count' => function ($q) use ($since) {
                    $q->where('event_type', 'sale');
                    if ($since) $q->where('created_at', '>=', $since);
                },
                'events as dwell_count' => function ($q) use ($since) {
                    $q->where('event_type', 'dwell');
                    if ($since) $q->where('created_at', '>=', $since);
                },
                'bookmarkedBy as bookmarks_count', // ← korrigiert
            ])
            ->orderByDesc('current_score')
            ->limit(20)
            ->get();

        $marketSplit = Ad::where('status', 'active')
            ->selectRaw('categories.name, COUNT(*) as cnt')
            ->join('categories', 'ads.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderByDesc('cnt')
            ->get();

        $totalActive = $marketSplit->sum('cnt');

        $newcomers = Ad::with(['merchant', 'category'])
            ->where('status', 'active')
            ->latest()
            ->limit(2)
            ->get();

        return view('catalog.ranking', compact(
            'ads', 'period', 'marketSplit', 'totalActive', 'newcomers'
        ));
    }

    public function hotspots()
    {
        // Aktive Hotspots mit Ad-Count + aggregierten Stats
        $active = Hotspot::active()
            ->withCount('ads')
            ->with(['ads' => fn($q) => $q->withCount([
                'events as views' => fn($e) => $e->where('event_type', 'view'),
            ])])
            ->get()
            ->map(function ($h) {
                // Stats über zugeordnete Ads aggregieren (echt)
                $h->total_views     = $h->ads->sum('views');
                $h->total_bookmarks = $h->ads->sum(fn($ad) => $ad->bookmarkedBy()->count());
                return $h;
            });

        $upcoming = Hotspot::upcoming()->orderBy('opens_at')->get();
        $archived = Hotspot::archived()->orderByDesc('closes_at')->limit(4)->get();

        // Stats-Panel (rechts)
        $stats = [
            'active_nodes'  => $active->count(),
            'pending_queue' => $upcoming->count(),
            'total_volume'  => $active->sum('ads_count'),
            'uptime'        => '99.98', // Dummy — kein echtes Monitoring im MVP
        ];

        return view('catalog.hotspots', compact('active', 'upcoming', 'archived', 'stats'));
    }

    public function analytics()
    {
        $user = Auth::user();

        // ── KPI-Cards (echt) ──
        $viewsCount = \App\Models\AdEvent::where('user_id', $user->id)
            ->where('event_type', 'view')
            ->count();

        $bookmarksCount = $user->bookmarks()->count();

        $purchases = \App\Models\Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();
        $purchasesCount = $purchases->count();

        // ── Live-Feed: gemischte Aktivität chronologisch (echt) ──
        // Bookmarks, Orders und Views zusammenführen, nach Zeit sortiert
        $feedBookmarks = \App\Models\Ad::whereIn('id',
            $user->bookmarks()->pluck('ad_id'))
            ->with('images')
            ->get()
            ->map(fn($ad) => (object)[
                'type'      => 'bookmark',
                'ad'        => $ad,
                'timestamp' => $ad->pivot->created_at ?? $ad->updated_at,
                'label'     => 'GEMERKT',
            ]);

        $feedOrders = $purchases->load('ad.images')->map(fn($o) => (object)[
            'type'      => 'order',
            'ad'        => $o->ad,
            'timestamp' => $o->created_at,
            'label'     => 'KAUF',
            'amount'    => $o->total_cents,
        ]);

        $feedViews = \App\Models\AdEvent::where('user_id', $user->id)
            ->where('event_type', 'view')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function ($e) {
                $ad = \App\Models\Ad::with('images')->find($e->ad_id);
                return $ad ? (object)[
                    'type'      => 'view',
                    'ad'        => $ad,
                    'timestamp' => $e->created_at,
                    'label'     => 'GESEHEN',
                ] : null;
            })
            ->filter();

        $activityFeed = $feedBookmarks
            ->concat($feedOrders)
            ->concat($feedViews)
            ->sortByDesc('timestamp')
            ->take(8)
            ->values();

        // ── Kategorie-Verteilung (echt, aus Käufen) ──
        $catSplit = \App\Models\Order::where('orders.user_id', $user->id)
            ->whereIn('orders.status', ['pending', 'confirmed'])
            ->join('ads', 'orders.ad_id', '=', 'ads.id')
            ->join('categories', 'ads.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as cnt, SUM(orders.total_cents) as total')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        $totalSpent = $catSplit->sum('total');

        return view('catalog.analytics', compact(
            'viewsCount', 'bookmarksCount', 'purchasesCount',
            'activityFeed', 'catSplit', 'totalSpent'
        ));
    }
}

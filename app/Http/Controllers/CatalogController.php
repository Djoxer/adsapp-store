<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
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
        // Premium Strip — nur bei aktivem Filter NICHT anzeigen (Filter = organisch)
        $premiumAds = ($q === '' && $catSlug === '')
            ? PremiumSlot::with(['ad.merchant', 'ad.images'])
                ->where('status', 'active')
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now())
                ->limit(3)
                ->get()
                ->map(fn($slot) => $slot->ad)
                ->filter()
            : collect();

        $organicAds    = $ads;
        $hotspot       = ($q === '' && $catSlug === '') ? $ads->first() : null;
        $rightPanelAds = Ad::with(['images'])
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $bookmarkedIds = Auth::user()->bookmarks()->pluck('ad_id')->toArray();

        // ── Für Filter-Bar ───────────────────────────────────────────
        $categories = Category::orderBy('name')->get();

        // Aktive Kategorie als Objekt (für Highlighting in der Filter-Bar)
        $activeCategory = $catSlug !== ''
            ? $categories->firstWhere('slug', $catSlug)
            : null;

        return view('catalog.index', compact(
            'premiumAds', 'organicAds', 'hotspot', 'rightPanelAds',
            'bookmarkedIds', 'categories', 'activeCategory', 'q', 'sort'
        ));
    }

    public function ranking()  { return view('catalog.ranking'); }
    public function hotspots() { return view('catalog.hotspots'); }
    public function analytics(){ return view('catalog.analytics'); }
}

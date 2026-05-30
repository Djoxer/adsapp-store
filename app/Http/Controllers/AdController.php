<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $merchant = Auth::user()->merchant;

        $stats = [
            'total'  => $merchant->ads()->count(),
            'active' => $merchant->ads()->where('status', 'active')->count(),
            'paused' => $merchant->ads()->where('status', 'paused')->count(),
            'draft'  => $merchant->ads()->where('status', 'draft')->count(),
        ];

        $query = $merchant->ads()->with('images');

        // Status-Filter
        if ($request->filled('status') && $request->status !== 'alle') {
            $query->where('status', $request->status);
        }

        // Kategorie-Filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Suche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $ads = $query->latest()->paginate(6)->withQueryString();

        $categories = \App\Models\Category::orderBy('name')->get();

        // Approval-Status fürs Banner in der View
        $isApproved = $merchant->isApproved();

        return view('ads.index', compact('ads', 'stats', 'categories', 'isApproved'));
    }

    public function create()
    {
        // Ebene 1: Nicht-freigegebene Merchants können keine Ads erstellen
        abort_unless(
            Auth::user()->merchant->isApproved(),
            403,
            'Dein Händler-Account ist noch nicht freigegeben. Sobald ein Admin dich bestätigt hat, kannst du Ads schalten.'
        );

        $categories = Category::orderBy('name')->get();
        return view('ads.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Ebene 1: Guard auch beim Speichern (falls jemand die create-Seite umgeht)
        abort_unless(
            Auth::user()->merchant->isApproved(),
            403,
            'Dein Händler-Account ist noch nicht freigegeben.'
        );

        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price_cents' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'exists:categories,id'],
            'deeplink_url'=> ['required', 'url'],
            'status'      => ['required', 'in:active,draft'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $merchant = Auth::user()->merchant;

        $ad = $merchant->ads()->create([
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'description'  => $request->description,
            'price_cents'  => $request->price_cents,
            'deeplink_url' => $request->deeplink_url,
            'status'       => $request->status,
        ]);

        // Image Upload — später S3/Cloudflare, erstmal local
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("ads/{$ad->id}", 'public');
            $ad->images()->create([
                'remote_url' => null,
                'cache_path' => $path,
                'position'   => 1,
            ]);
        }

        // Tags — komma-getrennt parsen
        if ($request->filled('tags')) {
            $tags = collect(explode(',', $request->tags))
                ->map(fn($t) => trim($t))
                ->filter()
                ->map(fn($name) => \App\Models\Tag::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($name)],
                    ['name' => $name]
                ));
            $ad->tags()->sync($tags->pluck('id'));
        }

        return redirect()->route('ads.index')
            ->with('status', 'ad-created');
    }

    public function edit(Ad $ad)
    {
        // Nur eigene Ads editieren
        abort_if($ad->merchant_id !== Auth::user()->merchant->id, 403);
        $categories = Category::orderBy('name')->get();
        return view('ads.edit', compact('ad', 'categories'));
    }

    public function update(Request $request, Ad $ad)
    {
        abort_if($ad->merchant_id !== Auth::user()->merchant->id, 403);

        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'price_cents'  => ['required', 'integer', 'min:1'],
            'category_id'  => ['required', 'exists:categories,id'],
            'deeplink_url' => ['required', 'url'],
            'status'       => ['required', 'in:active,paused,draft'],
        ]);

        $ad->update($request->only(
            'title', 'description', 'price_cents',
            'category_id', 'deeplink_url', 'status'
        ));

        return redirect()->route('ads.index')
            ->with('status', 'ad-updated');
    }

    public function destroy(Ad $ad)
    {
        abort_if($ad->merchant_id !== Auth::user()->merchant->id, 403);
        $ad->delete(); // SoftDelete
        return redirect()->route('ads.index')
            ->with('status', 'ad-deleted');
    }

    public function toggleStatus(Ad $ad)
    {
        abort_if($ad->merchant_id !== Auth::user()->merchant->id, 403);

        // draft bleibt draft — nur über edit aktivierbar
        if ($ad->status === 'draft') {
            return response()->json(['status' => 'draft', 'error' => 'Draft-Ads können nur über Bearbeiten aktiviert werden.'], 422);
        }

        $ad->status = $ad->status === 'active' ? 'paused' : 'active';
        $ad->save();

        return response()->json([
            'status'      => $ad->status,
            'label'       => $ad->status === 'active' ? 'AKTIV' : 'PAUSIERT',
            'statusStyle' => $ad->status === 'active'
                ? 'border-color:#F5B700;color:#F5B700;'
                : 'border-color:#454745;color:#454745;',
        ]);
    }

    public function show(Ad $ad)
    {
        // Ebene 2: öffentlich nur wenn active UND Merchant approved
        abort_unless($ad->isPublic(), 404);

        $ad->load(['merchant', 'category', 'images', 'tags']);

        $bookmarked = Auth::check()
            ? Auth::user()->bookmarks()->where('ad_id', $ad->id)->exists()
            : false;

        // view-Event tracken
        \App\Models\AdEvent::create([
            'ad_id'      => $ad->id,
            'event_type' => 'view',
            'user_id'    => Auth::id(),
            'ip_hash'    => hash('sha256', request()->ip()),
        ]);

        return view('ads.show', compact('ad', 'bookmarked'));
    }

    public function click(Ad $ad)
    {
        // Ebene 2: auch der Klick-Redirect nur für öffentliche Ads
        abort_unless($ad->isPublic(), 404);

        // Click-Event → Score-Boost (wir nutzen 'dwell' als Gewicht-3-Signal,
        // da Enum kein 'click' hat — Klick zum Händler = starkes Interesse)
        \App\Models\AdEvent::create([
            'ad_id'      => $ad->id,
            'event_type' => 'dwell',
            'user_id'    => Auth::id(),
            'ip_hash'    => hash('sha256', request()->ip()),
        ]);

        // Redirect zum echten Händler-Deeplink
        return redirect()->away($ad->deeplink_url);
    }
}

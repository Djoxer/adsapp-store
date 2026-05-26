<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    public function index()
    {
        $ads = Auth::user()->merchant->ads()->latest()->get();
        return view('ads.index', compact('ads'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('ads.create', compact('categories'));
    }

    public function store(Request $request)
    {
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
}

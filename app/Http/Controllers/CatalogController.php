<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index()
    {
        $bookmarkedIds = Auth::user()->bookmarks()->pluck('ad_id')->toArray();

        // später echte Ads aus DB — erstmal leer für Struktur
        return view('catalog.index', compact('bookmarkedIds'));
    }
}

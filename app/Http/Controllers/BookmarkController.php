<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function toggle(Ad $ad)
    {
        $user = Auth::user();

        // toggles bookmark — gibt zurück ob jetzt bookmarked oder nicht
        $result = $user->bookmarks()->toggle($ad->id);
        $bookmarked = count($result['attached']) > 0;

        return response()->json([
            'bookmarked' => $bookmarked,
            'ad_id'      => $ad->id,
        ]);
    }

    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()->with(['images', 'category', 'merchant'])->get();
        return view('catalog.bookmarks', compact('bookmarks'));
    }
}

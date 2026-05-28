<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Leads (dwell-Events) auf eigene Ads — nur für Merchants relevant
        $leads = collect();
        if ($user->merchant) {
            $leads = DB::table('ad_events')
                ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
                ->leftJoin('users', 'ad_events.user_id', '=', 'users.id')
                ->where('ads.merchant_id', $user->merchant->id)
                ->where('ad_events.event_type', 'dwell')
                ->orderByDesc('ad_events.created_at')
                ->limit(20)
                ->select(
                    'ad_events.id',
                    'ads.title as ad_title',
                    'ads.id as ad_id',
                    'users.email as buyer_email',
                    'ad_events.created_at'
                )
                ->get();
        }

        return view('settings.index', compact('user', 'leads'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'region'   => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($request->only('name', 'email', 'region', 'zip_code'));

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }

    // Notifications als gesehen markieren (AJAX vom Tab-Klick):
    public function markNotificationsSeen()
    {
        Auth::user()->update(['notifications_seen_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

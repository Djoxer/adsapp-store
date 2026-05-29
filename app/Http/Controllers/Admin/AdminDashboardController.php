<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlotBooking;
use App\Models\Merchant;
use App\Models\Ad;
use App\Models\Order;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_slots'     => SlotBooking::where('status', 'pending')->count(),
            'pending_merchants' => Merchant::where('approval_status', 'pending')->count(),
            'active_ads'        => Ad::where('status', 'active')->count(),
            'total_users'       => User::count(),
            'total_orders'      => Order::count(),
            'live_slots'        => SlotBooking::where('status', 'live')->count(),
        ];

        // Letzte Aktivität für die Übersicht
        $recentBookings = SlotBooking::with(['slot', 'ad', 'merchant.user'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $recentMerchants = Merchant::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentMerchants'));
    }
}

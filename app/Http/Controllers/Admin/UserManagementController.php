<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
        $role   = $request->get('role', '');

        $users = User::with('merchant')
            ->when($search, fn($q) => $q->where(function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            }))
            ->when($role, fn($q) => $q->where('role', $role))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users', compact('users', 'search', 'role'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:buyer,merchant,agency,admin']);
        $newRole = $request->role;

        // Merchant-Record anlegen wenn Rolle admin oder merchant und noch keiner existiert
        if (in_array($newRole, ['admin', 'merchant', 'agency']) && !$user->merchant) {
            Merchant::create([
                'user_id'           => $user->id,
                'company_name'      => $user->name,
                'approval_status'   => 'approved',
            ]);
        }

        $user->update(['role' => $newRole]);

        return back()->with('success', "Rolle von {$user->name} auf {$newRole} gesetzt.");
    }

    public function toggleBan(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Eigenen Account nicht sperrbar.');
        }

        $newBanned = !$user->is_banned;
        $user->update(['is_banned' => $newBanned]);

        return back()->with('success', $newBanned
            ? "{$user->name} gesperrt."
            : "{$user->name} entsperrt."
        );
    }

    public function toggleMerchant(User $user)
    {
        $merchant = $user->merchant;
        abort_unless($merchant, 404, 'Kein Merchant-Profil.');

        $newStatus = $merchant->approval_status === 'approved' ? 'rejected' : 'approved';
        $merchant->update(['approval_status' => $newStatus]);

        return back()->with('success', "Merchant-Status: {$newStatus}.");
    }
}

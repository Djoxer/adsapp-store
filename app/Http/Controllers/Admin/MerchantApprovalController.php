<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Support\Facades\Auth;

class MerchantApprovalController extends Controller
{
    public function index()
    {
        // Wartende zuerst (Handlungsbedarf)
        $pending = Merchant::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->get();

        // Bereits entschiedene als Kontext (mit Reviewer)
        $decided = Merchant::with(['user', 'reviewer'])
            ->whereIn('approval_status', ['approved', 'rejected'])
            ->orderByDesc('approval_reviewed_at')
            ->limit(20)
            ->get();

        return view('admin.merchants', compact('pending', 'decided'));
    }

    public function approve(Merchant $merchant)
    {
        abort_if($merchant->approval_status !== 'pending', 422, 'Nur wartende Händler können freigegeben werden.');

        // Direkte Property-Zuweisung — approval_reviewed_at / reviewer_id sind nicht im fillable
        $merchant->approval_status     = 'approved';
        $merchant->approval_reviewed_at = now();
        $merchant->approval_reviewer_id = Auth::id();
        $merchant->save();

        return back()->with('status', 'merchant-approved');
    }

    public function reject(Merchant $merchant)
    {
        abort_if($merchant->approval_status !== 'pending', 422);

        $merchant->approval_status     = 'rejected';
        $merchant->approval_reviewed_at = now();
        $merchant->approval_reviewer_id = Auth::id();
        $merchant->save();

        return back()->with('status', 'merchant-rejected');
    }
}

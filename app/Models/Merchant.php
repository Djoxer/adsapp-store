<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'shop_url', 'vat_id',
        'approval_status', 'commission_tier', 'payout_data',
    ];

    protected function casts(): array
    {
        return [
            'payout_data'           => 'array', // JSON → PHP Array auto-cast
            'approval_reviewed_at'  => 'datetime',
            'shop_last_checked_at'  => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'approval_reviewer_id');
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function premiumSlots()
    {
        return $this->hasMany(PremiumSlot::class);
    }

    // Scope für approved Merchants — $merchant->isApproved()
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }
}

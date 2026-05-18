<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'shop_url', 'vat_id',
        'approval_status', 'approval_reviewed_at', 'approval_reviewer_id',
        'commission_tier', 'payout_data', 'shop_last_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'payout_data'           => 'encrypted:array',
            'approval_reviewed_at'  => 'datetime',
            'shop_last_checked_at'  => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function premiumSlots()
    {
        return $this->hasMany(PremiumSlot::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id', 'order_id', 'amount_cents',
        'payout_status', 'creator_id', 'creator_share_cents',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}

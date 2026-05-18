<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ad_id', 'merchant_id', 'status',
        'total_cents', 'shipping_choice', 'delivery_date',
    ];

    protected function casts(): array
    {
        return [
            'shipping_choice' => 'array',
            'delivery_date'   => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class);
    }
}

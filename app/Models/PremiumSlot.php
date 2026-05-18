<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PremiumSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id', 'merchant_id', 'price_cents',
        'starts_at', 'ends_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}

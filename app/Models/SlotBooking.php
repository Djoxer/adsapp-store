<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SlotBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'premium_slot_id', 'merchant_id', 'ad_id',
        'duration_days', 'total_cents', 'status',
        'queue_position', 'starts_at', 'ends_at', 'rejected_reason',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    public function slot()
    {
        return $this->belongsTo(PremiumSlot::class, 'premium_slot_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    // ── Scopes ──
    public function scopeLive($query)
    {
        return $query->where('status', 'live')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}

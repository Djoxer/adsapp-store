<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PremiumSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone', 'position', 'base_price_cents',
        'discount_pct', 'discount_until', 'empty_since',
    ];

    protected function casts(): array
    {
        return [
            'discount_until' => 'datetime',
            'empty_since'    => 'datetime',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(SlotBooking::class);
    }

    // Aktuell live geschaltete Buchung (oder null wenn leer)
    public function currentBooking()
    {
        return $this->bookings()
            ->where('status', 'live')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();
    }

    // Warteschlange: pending/approved Bookings nach queue_position
    public function queue()
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('queue_position');
    }

    // ── Rabatt-Logik ──
    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_pct
            && $this->discount_until
            && $this->discount_until->isFuture();
    }

    // Effektiver Tagespreis in Cent (mit Rabatt falls aktiv)
    public function getEffectivePriceCentsAttribute(): int
    {
        if ($this->has_discount) {
            return (int) round($this->base_price_cents * (1 - $this->discount_pct / 100));
        }
        return $this->base_price_cents;
    }

    public function getIsEmptyAttribute(): bool
    {
        return $this->currentBooking() === null;
    }
}

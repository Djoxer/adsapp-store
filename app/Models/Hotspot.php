<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'slug', 'name', 'subtitle', 'description',
        'hero_image', 'icon', 'opens_at', 'closes_at', 'criteria',
    ];

    protected function casts(): array
    {
        return [
            'opens_at'  => 'datetime',
            'closes_at' => 'datetime',
            'criteria'  => 'array',
        ];
    }

    public function ads()
    {
        return $this->belongsToMany(Ad::class, 'hotspot_ads')
            ->withPivot('added_at');
    }

    // ── Status-Logik: leitet sich aus opens_at/closes_at + now() ab ──

    public function getIsActiveAttribute(): bool
    {
        $now = now();
        // dauerhaft offen (thematisch ohne Daten) = aktiv
        $opened = is_null($this->opens_at) || $this->opens_at <= $now;
        $notClosed = is_null($this->closes_at) || $this->closes_at >= $now;
        return $opened && $notClosed;
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->opens_at && $this->opens_at > now();
    }

    public function getIsArchivedAttribute(): bool
    {
        return $this->closes_at && $this->closes_at < now();
    }

    // Verbleibende Tage bis closes_at (für "NOCH 12 TAGE" Badge)
    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->closes_at) return null;
        return max(0, now()->diffInDays($this->closes_at, false));
    }

    // "Endet bald" wenn ≤ 3 Tage
    public function getEndsSoonAttribute(): bool
    {
        return $this->days_left !== null && $this->days_left <= 3 && $this->is_active;
    }

    // ── Query-Scopes für die View ──

    public function scopeActive($query)
    {
        $now = now();
        return $query->where(fn($q) => $q->whereNull('opens_at')->orWhere('opens_at', '<=', $now))
            ->where(fn($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>=', $now));
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('opens_at')->where('opens_at', '>', now());
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('closes_at')->where('closes_at', '<', now());
    }
}

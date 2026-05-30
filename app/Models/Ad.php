<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'merchant_id', 'category_id', 'title', 'description',
        'price_cents', 'deeplink_url', 'status', 'current_score',
    ];

    protected function casts(): array
    {
        return [
            'current_score'    => 'decimal:2',
            'last_activity_at' => 'datetime',
        ];
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        // ordered by position — immer konsistente Reihenfolge
        return $this->hasMany(AdImage::class)->orderBy('position');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ad_tag');
    }

    public function events()
    {
        // append-only, niemals updaten
        return $this->hasMany(AdEvent::class);
    }

    public function bookmarkedBy() {
        return $this->belongsToMany(User::class, 'bookmarks')->withPivot('created_at');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function hotspots()
    {
        return $this->belongsToMany(Hotspot::class, 'hotspot_ads')
            ->withPivot('added_at');
    }

    /**
     * Öffentlich sichtbare Ads: status='active' UND Merchant ist approved.
     * Zentraler Sichtbarkeits-Hebel für den gesamten Catalog — greift überall
     * wo Ads öffentlich gezeigt werden (Hauptliste, Ranking, Hotspots, Premium).
     */
    public function scopePublic($query)
    {
        return $query->where('ads.status', 'active')
            ->whereHas('merchant', fn($q) => $q->where('approval_status', 'approved'));
    }

    /**
     * Darf diese Ad öffentlich erscheinen? (für Einzelprüfung in show/click)
     */
    public function isPublic(): bool
    {
        return $this->status === 'active'
            && $this->merchant
            && $this->merchant->approval_status === 'approved';
    }

    // Convenience: Preis in Euro für Templates
    public function getPriceEuroAttribute(): string
    {
        return number_format($this->price_cents / 100, 2, ',', '.') . ' €';
    }
}

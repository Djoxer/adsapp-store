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

    // Convenience: Preis in Euro für Templates
    public function getPriceEuroAttribute(): string
    {
        return number_format($this->price_cents / 100, 2, ',', '.') . ' €';
    }
}

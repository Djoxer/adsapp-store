<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'merchant_id', 'category_id', 'title', 'description',
        'price_cents', 'deeplink_url', 'status', 'current_score', 'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'current_score'    => 'decimal:4',
            'last_activity_at' => 'datetime',
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRanked($query)
    {
        return $query->orderByDesc('current_score');
    }

    // Relations
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
        return $this->hasMany(AdImage::class)->orderBy('position');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ad_tag');
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(AdEvent::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function premiumSlots()
    {
        return $this->hasMany(PremiumSlot::class);
    }
}

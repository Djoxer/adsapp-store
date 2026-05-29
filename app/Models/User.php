<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'avatar_path', 'region', 'zip_code',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'notifications_seen_at' => 'datetime',
        ];
    }

    // Helper-Methods für Role-Checks — sauberer als string-Vergleiche überall
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isMerchant(): bool { return $this->role === 'merchant'; }
    public function isBuyer(): bool    { return $this->role === 'buyer'; }
    public function isAgency(): bool   { return $this->role === 'agency'; }

    public function homeRoute(): string
    {
        return match($this->role) {
            'admin'              => route('admin.dashboard'),
            'merchant', 'agency' => route('dashboard'),
            default              => route('catalog'),
        };
    }

    public function homeLabel(): string
    {
        return match($this->role) {
            'admin'              => 'ADMINISTRATION',
            'merchant', 'agency' => 'DASHBOARD',
            default              => '', // buyer hat keinen
        };
    }

    // 1:1 Relations — nur vorhanden wenn Rolle passt
    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }

    public function bookmarks() {
        return $this->belongsToMany(Ad::class, 'bookmarks')->withPivot('created_at');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Anzahl ungelesener Leads seit notifications_seen_at.
     * Genutzt für das Badge in der Topbar-Bell.
     */
    public function getUnreadLeadsCountAttribute(): int
    {
        // Nur Merchants haben Leads
        if (!$this->merchant) {
            return 0;
        }

        $since = $this->notifications_seen_at ?? $this->created_at;

        return DB::table('ad_events')
            ->join('ads', 'ad_events.ad_id', '=', 'ads.id')
            ->where('ads.merchant_id', $this->merchant->id)
            ->where('ad_events.event_type', 'dwell')
            ->where('ad_events.created_at', '>', $since)
            ->count();
    }
}

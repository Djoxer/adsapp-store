<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Rolle prüfen
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isMerchant(): bool { return $this->role === 'merchant'; }
    public function isBuyer(): bool    { return $this->role === 'buyer'; }
    public function isAgency(): bool   { return $this->role === 'agency'; }
    public function isCreator(): bool  { return $this->role === 'creator'; }

    // Relations
    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Ad::class, 'bookmarks')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

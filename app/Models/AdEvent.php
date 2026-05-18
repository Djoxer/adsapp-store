<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdEvent extends Model
{
    use HasFactory;

    public $timestamps = false; // nur created_at, kein updated_at

    protected $fillable = [
        'ad_id', 'event_type', 'user_id', 'ip_hash', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

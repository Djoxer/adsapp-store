<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bookmark extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['user_id', 'ad_id', 'created_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}

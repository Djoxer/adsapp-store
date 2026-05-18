<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id', 'remote_url', 'cache_path', 'position', 'alt_text',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}

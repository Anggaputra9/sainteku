<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedNews extends Model
{
    protected $fillable = [
        'title',
        'url',
        'image_url',
        'excerpt',
        'published_at',
        'source',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    /**
     * Scope: ambil N berita terbaru
     */
    public function scopeLatestNews($query, int $limit = 6)
    {
        return $query->orderByDesc('published_at')->orderByDesc('id')->limit($limit);
    }
}
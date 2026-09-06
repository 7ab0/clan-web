<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Registro manual de un post/story/reel/video publicado por un influencer
 * sobre el pre-cóctel de Fermento, con sus métricas cargadas a mano (no hay
 * integración con Instagram/TikTok).
 */
class InfluencerPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'influencer_id',
        'type',
        'url',
        'screenshot_path',
        'published_at',
        'views',
        'likes',
        'shares',
        'comments',
        'notes',
    ];

    protected $casts = [
        'published_at' => 'date',
        'views' => 'integer',
        'likes' => 'integer',
        'shares' => 'integer',
        'comments' => 'integer',
    ];

    public function influencer(): BelongsTo
    {
        return $this->belongsTo(Influencer::class);
    }
}

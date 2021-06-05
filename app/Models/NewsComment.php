<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class NewsComment
 * @package App\Models
 * @property int $id
 * @property User $user
 * @property News $news
 * @property string $body
 */
class NewsComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'news_id',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}

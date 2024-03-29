<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class News
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $image_path
 * @property Collection $comments
 * @property Collection $tags
 */
class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image_path',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(NewsTag::class);
    }
}

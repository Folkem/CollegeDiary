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
 * @property Collection $comments
 */
class News extends Model
{
    use HasFactory;

    public function comments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }
}

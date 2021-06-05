<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class NewsTag
 * @package App\Models
 * @property string $text
 * @property News $news
 */
class NewsTag extends Model
{
    use HasFactory;

    protected $hidden = [];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}

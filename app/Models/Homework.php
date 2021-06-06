<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Homework
 * @package App\Models
 * @property int $id
 * @property Discipline $discipline
 * @property Carbon $ending_at
 * @property string $description
 */
class Homework extends Model
{
    use HasFactory;

    protected $fillable = [
        'discipline_id',
        'ending_at',
        'description',
    ];

    protected $casts = [
        'ending_at' => 'datetime'
    ];

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }
}

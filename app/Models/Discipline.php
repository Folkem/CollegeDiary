<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Discipline
 * @package App\Models
 * @property int $id
 * @property Group $group
 * @property User $teacher
 * @property string $subject
 */
class Discipline extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'teacher_id',
        'subject',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

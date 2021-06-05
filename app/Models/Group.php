<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Group
 * @package App\Models
 * @property int $id
 * @property Speciality $speciality
 * @property int $year
 * @property int $number
 * @property Collection $students
 * @property Collection $disciplines
 * @property string $human_name
 */
class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'speciality_id',
        'year',
        'number',
    ];

    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getHumanNameAttribute(): string
    {
        return "{$this->speciality->short_name}—{$this->year}{$this->number}";
    }

    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class);
    }
}

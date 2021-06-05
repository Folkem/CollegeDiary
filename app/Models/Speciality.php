<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Speciality
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property Collection $groups
 */
class Speciality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name'
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}

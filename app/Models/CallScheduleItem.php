<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class CallScheduleItem
 * @package App\Models
 * @property int $id
 * @property string $starting_at
 * @property string $ending_at
 */
class CallScheduleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'starting_at',
        'ending_at',
    ];

    protected $casts = [
        'starting_at' => 'datetime:H:i:s',
        'ending_at' => 'datetime:H:i:s',
    ];
}

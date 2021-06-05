<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class CallScheduleItem
 * @package App\Models
 * @property
 */
class CallScheduleItem extends Model
{
    use HasFactory;

    protected $casts = [
        'starting_at' => 'datetime:H:i:s',
        'ending_at' => 'datetime:H:i:s',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LessonScheduleItem
 * @package App\Models
 * @property Discipline $discipline
 * @property CallScheduleItem $callScheduleItem
 * @property WeekDay $weekDay
 */
class LessonScheduleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'discipline_id',
        'call_schedule_item_id',
        'week_day_id',
        'variant',
    ];

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function callScheduleItem(): BelongsTo
    {
        return $this->belongsTo(CallScheduleItem::class);
    }

    public function weekDay(): BelongsTo
    {
        return $this->belongsTo(WeekDay::class);
    }
}

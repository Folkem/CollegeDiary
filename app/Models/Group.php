<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Group
 * @package App\Models
 * @property int $id
 * @property Speciality $speciality
 * @property int $year
 * @property int $number
 * @property Collection $students
 * @property Collection $disciplines
 * @property string $humanName
 * @property Collection $lessonScheduleItems
 * @property array $lessonSchedule
 * @property array $fullLessonSchedule
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
        return "{$this->speciality->short_name}‒{$this->year}{$this->number}";
    }

    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class);
    }

    public function lessonScheduleItems(): HasManyThrough
    {
        return $this->hasManyThrough(LessonScheduleItem::class, Discipline::class);
    }

    public function getLessonScheduleAttribute(): array
    {
        $lessonScheduleItems = $this->lessonScheduleItems()
            ->orderBy('week_day_id')->orderBy('call_schedule_item_id')->orderBy('variant')->get();

        $weekDays = $lessonScheduleItems->map(fn($lsi) => $lsi->weekDay)->unique();

        $lessonSchedule = [];

        foreach ($weekDays as $weekDay) {
            $humanWeekDay = $weekDay->name;
            $lessonSchedule[$humanWeekDay] = [];
            $weekDayLessonSchedule = $lessonScheduleItems->filter(function ($lti) use ($weekDay) {
                return $lti->weekDay == $weekDay;
            });
            foreach ($weekDayLessonSchedule as $wdlsi) {
                if (!array_key_exists($wdlsi->callScheduleItem->id, $lessonSchedule[$humanWeekDay])) {
                    $lessonSchedule[$humanWeekDay][$wdlsi->callScheduleItem->id] = [];
                }

                $numberLessonSchedule =& $lessonSchedule[$humanWeekDay][$wdlsi->callScheduleItem->id];

                $numberLessonSchedule[$wdlsi->variant] = $wdlsi->discipline->for_student;
            }
        }

        return $lessonSchedule;
    }

    public function getFullLessonScheduleAttribute(): array
    {
        $lessonSchedule = $this->lessonSchedule;

        foreach ($lessonSchedule as &$daySchedule) {
            for ($i = 1; $i <= 6; $i++) {
                if (!array_key_exists($i, $daySchedule)) {
                    $daySchedule[$i] = [
                        'постійно' => null,
                    ];
                }
            }
            ksort($daySchedule);
        }

        return $lessonSchedule;
    }
}

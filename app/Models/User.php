<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $avatar_path
 * @property string $password
 * @property Role $role
 * @property Group $group
 * @property array $teacherSchedule
 * @property Collection $newsComments
 * @property Collection $disciplines
 * @property Collection $grades
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_path',
        'password',
        'role_id',
        'group_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function newsComments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class);
    }

    public function getTeacherScheduleAttribute(): array
    {
        $lessonScheduleItems = LessonScheduleItem::with('discipline')
            ->orderBy('week_day_id')->orderBy('call_schedule_item_id')->orderBy('variant')
            ->get()->filter(fn($lsi) => $lsi->discipline->teacher_id === $this->id);

        $weekDays = $lessonScheduleItems->map(fn($lsi) => $lsi->weekDay)->unique();

        $lessonSchedule = [];

        foreach ($weekDays as $weekDay) {
            $humanWeekDay = ucfirst(__($weekDay->name));
            $lessonSchedule[$humanWeekDay] = [];
            $weekDayLessonSchedule = $lessonScheduleItems->filter(function ($lti) use ($weekDay) {
                return $lti->weekDay == $weekDay;
            });
            foreach ($weekDayLessonSchedule as $wdlsi) {
                if (!array_key_exists($wdlsi->callScheduleItem->id, $lessonSchedule[$humanWeekDay])) {
                    $lessonSchedule[$humanWeekDay][$wdlsi->callScheduleItem->id] = [];
                }

                $numberLessonSchedule =& $lessonSchedule[$humanWeekDay][$wdlsi->callScheduleItem->id];

                $numberLessonSchedule[$wdlsi->variant] = $wdlsi->discipline->for_teacher;
            }
        }

        return $lessonSchedule;
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'student_id', 'id');
    }
}

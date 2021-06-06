<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\LessonScheduleItem;
use App\Models\User;
use App\Models\WeekDay;
use Illuminate\Database\Seeder;

class LessonScheduleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::all()->each(function ($group) {
            WeekDay::all()->each(function ($weekDay) use ($group) {
                $start = mt_rand(1, 3);
                $end = mt_rand(4, 6);

                for ($callScheduleItemId = $start; $callScheduleItemId <= $end; $callScheduleItemId++) {
                    $variantNumber = rand(0, 2);
                    $variant = $variantNumber === 0 ?
                        'чисельник' :
                        ($variantNumber === 1 ?
                            'знаменник' :
                            'постійно'
                        );

                    LessonScheduleItem::query()->create([
                        'discipline_id' => $group->disciplines->random()->id,
                        'week_day_id' => $weekDay->id,
                        'call_schedule_item_id' => $callScheduleItemId,
                        'variant' => $variant,
                    ]);
                }
            });
        });
    }
}

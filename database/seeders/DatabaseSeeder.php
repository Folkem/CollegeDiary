<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SpecialitySeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(NewsCommentSeeder::class);
        $this->call(NewsTagSeeder::class);
        $this->call(CallScheduleItemSeeder::class);
        $this->call(DisciplineSeeder::class);
        $this->call(WeekDaySeeder::class);
        $this->call(LessonScheduleItemSeeder::class);
        $this->call(LessonTypeSeeder::class);
        $this->call(LessonSeeder::class);
        $this->call(HomeworkSeeder::class);
        $this->call(GradeSeeder::class);
    }
}

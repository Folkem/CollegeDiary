<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Lesson;
use Faker\Factory;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 200; $i++) {
            $isPresent = $faker->boolean();
            $randomLesson = Lesson::all()->random();
            if ($randomLesson->discipline->group->students->count() > 0) {
                Grade::query()->create([
                    'student_id' => $randomLesson->discipline->group->students->random()->id,
                    'lesson_id' => $randomLesson->id,
                    'is_present' => $isPresent,
                    'grade' => $isPresent ? ($faker->boolean() ? mt_rand(0, 100) : null) : null,
                ]);
            }
        }
    }
}

<?php

namespace Database\Factories;

use App\Models\Discipline;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isPresent = $this->faker->boolean();
        $randomLesson = Lesson::all()->random();
        return [
            'student_id' => $randomLesson->discipline->group->students->random()->id,
            'lesson_id' => $randomLesson->id,
            'is_present' => $isPresent,
            'grade' => $isPresent ? ($this->faker->boolean() ? mt_rand(0, 100) : null) : null,
        ];
    }
}

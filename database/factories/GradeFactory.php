<?php

namespace Database\Factories;

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
        return [
            'student_id' => User::query()->where('role_id', 4)->first()->id,
            'lesson_id' => Lesson::all()->random()->id,
            'is_present' => $isPresent,
            'grade' => $isPresent ? mt_rand(0, 100) : null,
        ];
    }
}

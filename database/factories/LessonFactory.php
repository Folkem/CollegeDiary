<?php

namespace Database\Factories;

use App\Models\Discipline;
use App\Models\Lesson;
use App\Models\LessonType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lesson_type_id' => LessonType::all()->random()->id,
            'discipline_id' => Discipline::all()->random()->id,
            'description' => $this->faker->realTextBetween(10, 250),
        ];
    }
}

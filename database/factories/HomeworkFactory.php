<?php

namespace Database\Factories;

use App\Models\Discipline;
use App\Models\Homework;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Homework::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'discipline_id' => Discipline::all()->random()->id,
            'ending_at' => now()->addHours(mt_rand(3, 24 * 7 * 31)),
            'description' => $this->faker->realTextBetween(10, 300),
        ];
    }
}

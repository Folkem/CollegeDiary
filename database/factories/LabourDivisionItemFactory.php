<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\LabourDivisionItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabourDivisionItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LabourDivisionItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject' => $this->faker->realTextBetween(10, 50),
            'group_id' => Group::all()->random()->id,
            'teacher_id' => User::query()->where('role_id', 3)->get()->random()->id,
        ];
    }
}

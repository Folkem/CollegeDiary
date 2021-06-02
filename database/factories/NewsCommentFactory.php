<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\NewsComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NewsComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => fn() => User::query()->inRandomOrder()->first()->value('id'),
            'news_id' => fn() => News::query()->inRandomOrder()->first()->value('id'),
            'body' => $this->faker->realTextBetween(4, 400),
        ];
    }
}

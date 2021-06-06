<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->realTextBetween(20, 100),
            'body' => $this->faker->realTextBetween(100, 2000),
            'image_path' => 'media/news-covers/main-cover.jpg',
        ];
    }
}

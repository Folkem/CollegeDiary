<?php

namespace Database\Seeders;

use App\Models\LessonType;
use Illuminate\Database\Seeder;

class LessonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LessonType::query()->create(['name' => 'Лекція']);
        LessonType::query()->create(['name' => 'Практична робота']);
        LessonType::query()->create(['name' => 'Лабораторна робота']);
        LessonType::query()->create(['name' => 'Залік']);
        LessonType::query()->create(['name' => 'Диференційований залік']);
        LessonType::query()->create(['name' => 'Екзамен']);
    }
}

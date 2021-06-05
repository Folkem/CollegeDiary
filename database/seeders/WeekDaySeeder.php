<?php

namespace Database\Seeders;

use App\Models\WeekDay;
use Illuminate\Database\Seeder;

class WeekDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeekDay::query()->create(['name' => 'monday']);
        WeekDay::query()->create(['name' => 'tuesday']);
        WeekDay::query()->create(['name' => 'wednesday']);
        WeekDay::query()->create(['name' => 'thursday']);
        WeekDay::query()->create(['name' => 'friday']);
    }
}

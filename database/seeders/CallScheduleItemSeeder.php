<?php

namespace Database\Seeders;

use App\Models\CallScheduleItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CallScheduleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CallScheduleItem::query()->create([
            'starting_at' => Carbon::create(0, 0, 0, 8),
            'ending_at' => Carbon::create(0, 0, 0, 9, 20),
        ]);
        CallScheduleItem::query()->create([
            'starting_at' => Carbon::create(0, 0, 0, 9, 30),
            'ending_at' => Carbon::create(0, 0, 0, 10, 50),
        ]);
        CallScheduleItem::query()->create([
            'starting_at' => Carbon::create(0, 0, 0, 11),
            'ending_at' => Carbon::create(0, 0, 0, 12, 20),
        ]);
        CallScheduleItem::query()->create([
            'starting_at' => Carbon::create(0, 0, 0, 13),
            'ending_at' => Carbon::create(0, 0, 0, 14, 20),
        ]);
        CallScheduleItem::query()->create([
            'starting_at' => Carbon::create(0, 0, 0, 14, 30),
            'ending_at' => Carbon::create(0, 0, 0, 15, 50),
        ]);
        CallScheduleItem::query()->create([
            'starting_at' => Carbon::create(0, 0, 0, 16),
            'ending_at' => Carbon::create(0, 0, 0, 17, 20),
        ]);
    }
}

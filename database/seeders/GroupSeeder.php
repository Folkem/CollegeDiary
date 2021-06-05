<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Speciality;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speciality::all()->each(function ($speciality) {
            collect([1, 2, 3, 4])->each(function ($year) use ($speciality) {
                Group::query()->create([
                    'speciality_id' => $speciality->id,
                    'year' => $year,
                    'number' => 1,
                ]);
            });
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speciality::query()->create([
            'name' => 'Інженерія програмного забезпечення',
            'short_name' => 'КН',
        ]);
        Speciality::query()->create([
            'name' => 'Облік і оподаткування',
            'short_name' => 'БО',
        ]);
        Speciality::query()->create([
            'name' => 'Фінанси, банківська справа та страхування',
            'short_name' => 'ФК',
        ]);
        Speciality::query()->create([
            'name' => 'Право',
            'short_name' => 'ПР',
        ]);
        Speciality::query()->create([
            'name' => 'Будівництво та цивільна інженерія',
            'short_name' => 'БС',
        ]);
        Speciality::query()->create([
            'name' => 'Геодезія та землеустрій',
            'short_name' => 'ЗВ',
        ]);
        Speciality::query()->create([
            'name' => 'Туризм',
            'short_name' => 'ТО',
        ]);
    }
}

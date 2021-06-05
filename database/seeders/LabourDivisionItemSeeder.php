<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\LabourDivisionItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class LabourDivisionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LabourDivisionItem::factory()
            ->count(mt_rand(300, 600))
            ->create();
    }
}

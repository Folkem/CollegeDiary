<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->create(['name' => 'admin']);
        Role::query()->create(['name' => 'department head']);
        Role::query()->create(['name' => 'teacher']);
        Role::query()->create(['name' => 'student']);
        Role::query()->create(['name' => 'parent']);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Role;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                if ($user->role->name === 'student') {
                    $user->update([
                        'group_id' => Group::all()->random()->id
                    ]);
                }
            });

        User::query()->create([
            'name' => 'Гл. Адміністратор',
            'email' => 'maxxfoxx2012@gmail.com',
            'password' => Hash::make('aleksandra'),
            'role_id' => 1,
        ]);

        User::query()->create([
            'name' => 'Зав. відділенням',
            'email' => 'folkem2020@gmail.com',
            'password' => Hash::make('lesya'),
            'role_id' => 2,
        ]);

        User::query()->create([
            'name' => 'Александра Наталія Володимирівна',
            'email' => 'nataliya.aleksandra@uzhnu.edu.ua',
            'password' => Hash::make('natasha'),
            'role_id' => 3,
        ]);

        User::query()->create([
            'name' => 'Лісіцин Максиміліан Максимович',
            'email' => 'c.lisitsyn.maksymilian@student.uzhnu.edu.ua',
            'password' => Hash::make('folkem'),
            'role_id' => 4,
            'group_id' => Speciality::query()->where('short_name', 'КН')
                ->first()->groups()->where('year', 4)->first()->id,
        ]);
    }
}

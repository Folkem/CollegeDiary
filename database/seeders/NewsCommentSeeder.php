<?php

namespace Database\Seeders;

use App\Models\NewsComment;
use Illuminate\Database\Seeder;

class NewsCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsComment::factory()
            ->count(mt_rand(20, 40))
            ->create();
    }
}

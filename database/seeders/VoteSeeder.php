<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('votes')->truncate();
        DB::table('votes')->insert(
            [
                [
                    'title' => 'Đi đá bóng',
                    'user_id' => 1
                ],
                [
                    'title' => 'Đi nhậu',
                    'user_id' => 2
                ],
                [
                    'title' => 'Chơi game',
                    'user_id' => 3
                ],
                [
                    'title' => 'hát',
                    'user_id' => 4
                ]
            ]
        );
    }
}

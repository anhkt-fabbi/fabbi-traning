<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('option_users')->truncate();
        DB::table('option_users')->insert(
            [
                [
                    'user_id' => 1,
                    'option_id' => 1
                ],
                [
                    'user_id' => 1,
                    'option_id' => 2
                ],
                [
                    'user_id' => 1,
                    'option_id' => 2
                ],
                [
                    'user_id' => 2,
                    'option_id' => 2
                ],
                [
                    'user_id' => 3,
                    'option_id' => 2
                ],
                [
                    'user_id' => 4,
                    'option_id' => 2
                ],
                [
                    'user_id' => 5,
                    'option_id' => 2
                ],
                [
                    'user_id' => 6,
                    'option_id' => 1
                ]
            ]
        );
    }
}

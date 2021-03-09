<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('options')->truncate();
        DB::table('options')->insert(
            [
                [
                    'vote_id' => 1,
                    'option' => '7h thứ 3 ở cà mau'
                ],
                [
                    'vote_id' => 1,
                    'option' => '6h thứ 3 ở sân bóng mĩ đình hà nội'
                ],
                [
                    'vote_id' => 1,
                    'option' => '8h thứ 3 nhưng méo đi'
                ],
                [
                    'vote_id' => 2,
                    'option' => '7h thứ 3 thịt chó'
                ],
                [
                    'vote_id' => 2,
                    'option' => '7h thứ 2 lẩu'
                ],
            ]
        );
    }
}

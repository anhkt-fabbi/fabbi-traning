<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'email' => 'admin@gmail.com',
            'password' => bcrypt(12345678),
            'full_name' => 'kieu tuan anh'
        ]);
        \App\Models\User::factory()->count(10)->create();
    }
}

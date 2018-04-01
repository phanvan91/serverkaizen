<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'name' => 'admin',
                'password' => bcrypt('123123123'),
                'email' => 'admin@gmail.com',
                'to_chuc_id' => 1,
                'loai_nguoi_dung_id' => 1,
                'created_at' => date("Y-m-d")
            ]
        );
    }
}

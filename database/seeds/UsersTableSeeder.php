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
        \DB::table('users')->insert(
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@probstack.io',
                'password' => bcrypt('secret'),
                'username' => 'admin',
                'api_token' => md5(time())
            ]
        );
    }
}

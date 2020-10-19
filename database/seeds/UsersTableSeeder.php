<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessMember;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        \DB::table('users')->insert(
//            [
//                'first_name' => 'Super',
//                'last_name' => 'Admin',
//                'email' => 'admin@probstack.io',
//                'password' => bcrypt('secret'),
//                'username' => 'admin',
//                'api_token' => md5(time())
//            ]
//        );

       factory(User::class)->create([
           'first_name' => 'Super',
           'last_name' => 'Admin',
           'email' => 'admin@probstack.io',
           'password' => bcrypt('secret'),
           'username' => 'admin',
           'api_token' => md5(time())
       ]);

        $user = factory(User::class)->create([
            'first_name' => 'Wang',
            'last_name' => 'Admin',
            'email' => 'wang@vocit.io',
            'password' => bcrypt('secret'),
            'username' => 'wang',
            'api_token' => md5(time())
        ]);

        $business = Business::create([
            'name' => 'ACME',
            'email' => 'wang@vocit.test',
            'phone' => '+86 34523452',
            'address' => 'China',
            'logo' => 'https://placehold.it/200x200',
            'contact_person' => 'Wang',
            'contact_email' => 'wang@vocit.test',
            'contact_phone' => '+863434342',
        ]);

        BusinessMember::create([
            'business_id' => $business->id,
            'user_id' => $user->id,
        ]);
    }
}

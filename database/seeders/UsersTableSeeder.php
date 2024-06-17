<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'email' => 'appsinfooo@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$jpkEAkeMcWYfIK4KQuzsFeQeagCaNXPlcoYmulmPu/.95oaH6w5Mm',
                'remember_token' => NULL,
                'created_at' => '2023-02-25 10:02:28',
                'updated_at' => '2023-02-25 10:02:28',
            ),
        ));
        
        
    }
}
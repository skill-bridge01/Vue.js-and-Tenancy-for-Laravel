<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('services')->delete();
        
        \DB::table('services')->insert(array (
            0 => 
            array (
                'id' => 1,
                'services_title' => 'غسيل مستعجل',
                'is_checked' => 1,
                'is_shown' => 1,
                'created_at' => '2023-02-24 18:41:28',
                'updated_at' => '2023-02-24 18:41:28',
            ),
            1 => 
            array (
                'id' => 2,
                'services_title' => 'غسيل عادي',
                'is_checked' => 1,
                'is_shown' => 1,
                'created_at' => '2023-02-24 20:51:46',
                'updated_at' => '2023-02-24 20:51:46',
            ),
            2 => 
            array (
                'id' => 3,
                'services_title' => 'ازالة بقع',
                'is_checked' => 1,
                'is_shown' => 1,
                'created_at' => '2023-02-24 20:51:59',
                'updated_at' => '2023-02-24 20:51:59',
            ),
            3 => 
            array (
                'id' => 4,
                'services_title' => 'كوي',
                'is_checked' => 1,
                'is_shown' => 1,
                'created_at' => '2023-02-24 20:52:17',
                'updated_at' => '2023-02-24 20:52:17',
            ),
            4 => 
            array (
                'id' => 5,
                'services_title' => 'كوي مستعجل',
                'is_checked' => 1,
                'is_shown' => 1,
                'created_at' => '2023-02-24 20:52:23',
                'updated_at' => '2023-02-24 20:52:23',
            ),
            5 => 
            array (
                'id' => 6,
                'services_title' => 'غسيل وكوي مستعجل',
                'is_checked' => 1,
                'is_shown' => 1,
                'created_at' => '2023-02-24 20:52:30',
                'updated_at' => '2023-02-24 20:52:30',
            ),
            6 => 
            array (
                'id' => 7,
                'services_title' => 'غسيل وكوي عادي',
                'is_checked' => 1,
                'is_shown' => 1,
                'created_at' => '2023-02-24 20:52:38',
                'updated_at' => '2023-02-24 20:52:38',
            ),
            7 => 
            array (
                'id' => 8,
                'services_title' => 'مخصصة',
                'is_checked' => 0,
                'is_shown' => 1,
                'created_at' => '2023-04-11 17:57:39',
                'updated_at' => '2023-04-11 17:57:39',
            ),
        ));
        
        
    }
}
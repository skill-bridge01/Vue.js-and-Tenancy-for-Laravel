<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServicePieceTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('service_piece')->delete();
        
        \DB::table('service_piece')->insert(array (
            0 => 
            array (
                'id' => 1,
                'service_id' => 1,
                'piece_id' => 1,
                'price' => 4.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'service_id' => 3,
                'piece_id' => 1,
                'price' => 5.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'service_id' => 2,
                'piece_id' => 1,
                'price' => 5.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'service_id' => 7,
                'piece_id' => 1,
                'price' => 5.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'service_id' => 6,
                'piece_id' => 1,
                'price' => 10.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'service_id' => 4,
                'piece_id' => 1,
                'price' => 2.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'service_id' => 5,
                'piece_id' => 1,
                'price' => 5.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'service_id' => 2,
                'piece_id' => 2,
                'price' => 2.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'service_id' => 1,
                'piece_id' => 2,
                'price' => 3.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'service_id' => 7,
                'piece_id' => 2,
                'price' => 3.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'service_id' => 6,
                'piece_id' => 2,
                'price' => 5.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'service_id' => 4,
                'piece_id' => 2,
                'price' => 2.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'service_id' => 5,
                'piece_id' => 2,
                'price' => 3.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'service_id' => 3,
                'piece_id' => 2,
                'price' => 2.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'service_id' => 8,
                'piece_id' => 1,
                'price' => 0.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'service_id' => 8,
                'piece_id' => 2,
                'price' => 0.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'service_id' => 8,
                'piece_id' => 3,
                'price' => 0.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'service_id' => 8,
                'piece_id' => 4,
                'price' => 0.0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
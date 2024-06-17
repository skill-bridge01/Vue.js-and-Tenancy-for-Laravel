<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PiecesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pieces')->delete();
        
        \DB::table('pieces')->insert(array (
            0 => 
            array (
                'id' => 1,
                'piece_title' => 'ثوب صيفي',
                'created_at' => '2023-02-24 19:02:58',
                'updated_at' => '2023-02-24 19:02:58',
            ),
            1 => 
            array (
                'id' => 2,
                'piece_title' => 'شماغ',
                'created_at' => '2023-02-24 20:54:25',
                'updated_at' => '2023-02-24 20:54:25',
            ),
            2 => 
            array (
                'id' => 3,
                'piece_title' => 'سروال طويل',
                'created_at' => '2023-02-24 20:54:32',
                'updated_at' => '2023-02-24 20:54:32',
            ),
            3 => 
            array (
                'id' => 4,
                'piece_title' => 'سروال قصير',
                'created_at' => '2023-02-24 20:54:37',
                'updated_at' => '2023-02-24 20:54:37',
            ),
        ));
        
        
    }
}
<?php

namespace Database\Seeders;

use App\Models\Pieces;
use Illuminate\Database\Seeder;

class PiecesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pieces::factory()->count(5)->create();
    }
}

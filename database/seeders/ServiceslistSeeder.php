<?php

namespace Database\Seeders;

use App\Models\Serviceslist;
use Illuminate\Database\Seeder;

class ServiceslistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Serviceslist::factory()->count(5)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\ServicesType;
use Illuminate\Database\Seeder;

class ServicesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServicesType::factory()->count(5)->create();
    }
}

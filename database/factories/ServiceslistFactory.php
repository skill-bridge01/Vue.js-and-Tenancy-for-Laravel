<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Serviceslist;

class ServiceslistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Serviceslist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'services_ar' => $this->faker->word,
            'services_en' => $this->faker->word,
            'is_checked' => $this->faker->boolean,
            'is_shown' => $this->faker->boolean,
        ];
    }
}

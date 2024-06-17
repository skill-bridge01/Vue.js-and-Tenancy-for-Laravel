<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pieces;

class PiecesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pieces::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'piece_ar' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'Urgent_wash_price' => $this->faker->randomFloat(0, 0, 99999.),
            'wash_price' => $this->faker->randomFloat(0, 0, 99999.),
        ];
    }
}

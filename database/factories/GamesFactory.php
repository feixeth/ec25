<?php

namespace Database\Factories;
use App\Models\Games;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Games>
 */
class GamesFactory extends Factory
{
    protected $model = Games::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->company,
            'logo' => $this->faker->imageUrl(200, 200, 'logos', true),
        ];
    }
}

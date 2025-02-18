<?php

namespace Database\Factories;
use App\Models\Teams;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teams>
 */
class TeamsFactory extends Factory
{
    protected $model = Teams::class;

    public function definition()
    {
        return [
            'owner' => $this->faker->randomNumber(),
            'name' => $this->faker->company,
            'logo' => $this->faker->imageUrl(200, 200, 'logos', true),
            'country' => $this->faker->country,
            'website' => $this->faker->url,
            'social' => json_encode([
                'facebook' => $this->faker->url,
                'twitter' => $this->faker->url,
                'linkedin' => $this->faker->url,
            ]),
        ];
    }
}

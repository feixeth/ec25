<?php

namespace Database\Factories;

use App\Models\Conversations;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationsFactory extends Factory
{
    protected $model = Conversations::class;

    public function definition()
    {
        return [
            'subject' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

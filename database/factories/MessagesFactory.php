<?php

namespace Database\Factories;

use App\Models\Messages;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessagesFactory extends Factory
{
    protected $model = Messages::class;

    public function definition()
    {
        return [
            'sender_id' => User::factory(),  
            'recipient_id' => User::factory(),
            'messages' => $this->faker->text(200),
            'is_read' => $this->faker->boolean, 
        ];
    }
}

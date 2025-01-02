<?php

namespace Tests\Feature;

use App\Models\Games;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_game_with_valid_data()
    {
        $gameData = [
            'name' => 'Super Mario Bros',
            'code' => 'SMB001',
            'logo' => 'path/to/logo.png'
        ];

        $response = $this->postJson('/api/addGame', $gameData);

        $response->assertStatus(201)
                ->assertJson(['message' => 'Game created successfully']);

        $this->assertDatabaseHas('games', $gameData);
    }

    public function test_cannot_create_game_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // Required field empty
            'code' => str_repeat('a', 21), // Exceeds max length of 20
            'logo' => str_repeat('b', 256) // Exceeds max length of 255
        ];

        $response = $this->postJson('/api/addGame', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'code', 'logo']);

        $this->assertDatabaseCount('games', 0);
    }

    public function test_can_create_game_without_logo()
    {
        $gameData = [
            'name' => 'Tetris',
            'code' => 'TET001'
        ];

        $response = $this->postJson('/api/addGame', $gameData);

        $response->assertStatus(201)
                ->assertJson(['message' => 'Game created successfully']);

        $this->assertDatabaseHas('games', $gameData);
    }

    public function test_cannot_create_game_with_too_long_name()
    {
        $gameData = [
            'name' => str_repeat('a', 192), // 192 characters (max is 191)
            'code' => 'VALID001',
            'logo' => 'valid/path.png'
        ];

        $response = $this->postJson('/api/addGame', $gameData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseCount('games', 0);
    }
}
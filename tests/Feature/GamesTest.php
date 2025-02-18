<?php

namespace Tests\Feature;

use App\Models\Games;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GamesTest extends TestCase
{
    use DatabaseTransactions;


    private array $validGameData;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Données de base pour les tests
        $this->validGameData = [
            'name' => 'Test Game',
            'code' => 'TST001',
            'logo' => 'path/to/logo.png'
        ];

        // Création d'un jeu initial avec ID 2
        Games::create([
            'id' => 2,
            'name' => 'Test Game',
            'code' => 'CS2',
            'logo' => 'path/to/logo.png'
        ]);
    }

    public function test_can_create_game_with_valid_data()
    {
        $response = $this->postJson('/api/game', $this->validGameData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Game created successfully']);
        $this->assertDatabaseHas('games', $this->validGameData);
    }

    public function test_cannot_create_game_with_invalid_data()
    {
        $invalidData = [
            'name' => '',
            'code' => str_repeat('a', 21),
            'logo' => str_repeat('b', 256)
        ];

        $response = $this->postJson('/api/game', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code', 'logo']);
        $this->assertDatabaseCount('games', 1); // Compte seulement le jeu initial
    }

    public function test_can_create_game_without_logo()
    {
        $gameDataWithoutLogo = [
            'name' => 'Test Game',
            'code' => 'TST002'
        ];

        $response = $this->postJson('/api/game', $gameDataWithoutLogo);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Game created successfully']);
        $this->assertDatabaseHas('games', $gameDataWithoutLogo);
    }

    public function test_cannot_create_game_with_too_long_name()
    {
        $validGameData = [
            'name' => str_repeat('a', 192), // 192 characters (max is 191)
            'code' => 'VALID001',
            'logo' => 'valid/path.png'
        ];
        $response = $this->postJson('/api/game', $validGameData);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        // $this->assertDatabaseCount('games', 0);
    }


    public function test_games_can_be_updated(): void
    {
        // First, create a game record we can update
        $game = Games::factory()->create([
            'name' => 'Original Name',
            'code' => 'ON',
            'logo' => 'original_logo.png'
        ]);

        $updateData = [
            'name' => 'CounterStrike',
            'code' => 'CS',
        ];

        $response = $this->put('/api/game/' . $game->id, $updateData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Game updated succesfully']);

        // Verify the update in the database
        $this->assertDatabaseHas('games', [
            'name' => 'CounterStrike',
            'code' => 'CS',
            'logo' => 'original_logo.png'
        ]);
    }


    public function test_games_can_be_deleted(): void
    {
        $gameData = [
            'name' => 'Super Mario Bros',
            'code' => 'SMB001',
            'logo' => 'path/to/logo.png'
        ];
        
        $response = $this->postJson('/api/game', $gameData);
        $response->assertStatus(201)
                ->assertJson(['message' => 'Game created successfully']);
        $this->assertDatabaseHas('games', $gameData);

        // Get the game ID from the database
        $game = Games::where('name', $gameData['name'])->first();
        $game_id = $game->id;
    
        $responseB = $this->delete("/api/game/{$game_id}");
        $responseB->assertStatus(200)
                ->assertJson(['message' => 'Game successfully deleted']);
        $this->assertDatabaseMissing('games', $gameData);
    }


}
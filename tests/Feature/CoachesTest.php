<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Games;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CoachesTest extends TestCase
{

    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_coaches_can_be_created(): void
    {
        $user = User::factory()->create([
            'nickname' => 'CoachTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'coach@example.com',
            'password' => bcrypt('password123'),
            'age' => 25,
            'nationality' => 'French',
            'role' => 'coach',
            'avatar' => 'path/to/avatar.jpg',
        ]);

        $game = Games::firstOrCreate([
            'id' => 2,
        ], [
            'name' => 'Test Game',
            'code' => 'CS2',
            'description' => 'Description of the test game',
        ]);

        $coachData = [
            'user_id' => $user->id, //ID de l'utilisateur créé
            'game_id' => $game->id,
            'status' => 'Available',
            'achievement' => 'We made some success with X teams',
        ];

        //POST
        $response = $this->post('/api/addCoach', $coachData);
        $response->assertStatus(200)
                ->assertJson(['message' => 'Coach succesfully added']);
        $this->assertDatabaseHas('coaches', $coachData);
    }

        public function test_coaches_can_be_deleted(): void
    {
        $user = User::factory()->create([
            'nickname' => 'CoachTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'coach@example.com',
            'password' => bcrypt('password123'),
            'age' => 25,
            'nationality' => 'French',
            'role' => 'coach',
            'avatar' => 'path/to/avatar.jpg',
        ]);

        $game = Games::firstOrCreate([
            'id' => 2,
        ], [
            'name' => 'Test Game',
            'code' => 'CS2',
            'description' => 'Description of the test game',
        ]);

        $coachData = [
            'user_id' => $user->id, //ID de l'utilisateur créé
            'game_id' => $game->id,
            'status' => 'Available',
            'achievement' => 'We made some success with X teams',
        ];

        //POST
        $responseA = $this->post('/api/addCoach', $coachData);
        $responseA->assertStatus(200)
                ->assertJson(['message' => 'Coach succesfully added']);
        $this->assertDatabaseHas('coaches', $coachData);

        // requete post api pour delete la row coach dans la table 
        $responseB = $this->post('/api/deleteCoach/' . $coachData['user_id']);
        $responseB->assertStatus(200)
                  ->assertJson(['message' => 'Coach succesfully deleted']);
        $this->assertDatabaseMissing('coaches', ['user_id' => $coachData['user_id']]);
    }

}

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

    protected $coachData;


    // Test d'un setup pour le DRY - a refaire sur le reste
    // je setup un genre de payload que je peux utiliser a mon bon vouloir dans les tests
    protected function setUp(): void
    {
        parent::setUp();

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

        $this->coachData = [
            'user_id' => $user->id,
            'game_id' => $game->id,
            'status' => 'Available',
            'achievement' => 'We made some success with X teams',
        ];
    }

    public function test_coaches_can_be_created(): void
    {
        $response = $this->post('/api/addCoach', $this->coachData);
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Coach succesfully added']);
        $this->assertDatabaseHas('coaches', $this->coachData);
    }

    public function test_coaches_can_be_deleted(): void
    {
        $this->post('/api/addCoach', $this->coachData);

        $response = $this->post('/api/deleteCoach/' . $this->coachData['user_id']);
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Coach succesfully deleted']);
        $this->assertDatabaseMissing('coaches', ['user_id' => $this->coachData['user_id']]);
    }

    public function test_coach_can_be_updated(): void
    {
        $this->post('/api/addCoach', $this->coachData);

        $updateData = [
            'status' => 'Not available',
            'achievement' => 'Updated achievement',
        ];

        $response = $this->put('/api/updateCoach/' . $this->coachData['user_id'], $updateData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Coach updated succesfully']);

        $this->assertDatabaseHas('coaches', array_merge($this->coachData, $updateData));
    }

}

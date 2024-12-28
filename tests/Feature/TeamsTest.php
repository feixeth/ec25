<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamsTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_teams_can_be_created(): void
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
// TODO 
        //POST
        $response = $this->post('/api/addCoach', $coachData);
        $response->assertStatus(200)
                ->assertJson(['message' => 'Coach succesfully added']);
        $this->assertDatabaseHas('coaches', $coachData);
    }

}

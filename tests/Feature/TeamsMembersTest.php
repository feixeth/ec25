<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teams;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamsMembersTest extends TestCase
{

    use DatabaseTransactions;
    /**
     * A basic test for adding members to a team.
     */
    public function test_members_can_be_add(): void
    {
        // Create le propri de la team
        $owner = User::factory()->create([
            'id' => 1,
            'nickname' => 'OwnerTest',
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'owner@example.com',
            'password' => bcrypt('password123'),
            'age' => 30,
            'nationality' => 'French',
            'role' => 'user',
            'avatar' => 'path/to/avatar.jpg',
        ]);

        // Le futur membre
        $user = User::factory()->create([
            'id' => 2,
            'nickname' => 'TeamMemberTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'teammember@example.com',
            'password' => bcrypt('password123'),
            'age' => 25,
            'nationality' => 'French',
            'role' => 'user',
            'avatar' => 'path/to/avatar.jpg',
        ]);

        // Team avec owner 
        $team = Teams::factory()->create([
            'owner' => $owner->id,
            'name' => 'TeamTest',
            'logo' => 'path/to/avatar.jpg',
            'country' => 'Europe',
            'website' => 'www.website.com',
            'social' => json_encode([
                'facebook' => 'https://facebook.com/user',
                'twitter' => 'https://twitter.com/user',
                'linkedin' => 'https://linkedin.com/user',
            ]),
        ]);

        // Ajouter le membre
        // Requete API Au controller pour un ajout de membre A FAIRE ICI : 

        $response = $this->postJson("/api/teams/{$team->id}/members", [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Member added successfully'
        ]);

        // Vérifier
        $this->assertDatabaseHas('teams_members', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }

    /**
     * A basic test for removing members from a team.
     */
    public function test_members_can_be_remove(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test for promote members of a team as the owner.
     */
    public function test_members_can_be_promote(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test for demote members of a team as the owner.
     */
    public function test_members_can_be_demote(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

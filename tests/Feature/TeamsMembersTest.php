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

        $response = $this->postJson("/api/teams/{$team->id}/members", [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Member added successfully'
        ]);

        $this->assertDatabaseHas('teams_members', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }

    /**
     * A basic test for removing members from a team.
     */
    // public function test_members_can_be_removed(): void
    // {
    //     // Création de l'utilisateur propriétaire de l'équipe
    //     $owner = User::factory()->create([
    //         'nickname' => 'OwnerTest',
    //         'firstname' => 'Jane',
    //         'lastname' => 'Doe',
    //         'email' => 'owner@example.com',
    //         'password' => bcrypt('password123'),
    //         'role' => 'user',
    //     ]);

    //     // Création d'un utilisateur membre
    //     $user = User::factory()->create([
    //         'nickname' => 'TeamMemberTest',
    //         'firstname' => 'John',
    //         'lastname' => 'Doe',
    //         'email' => 'teammember@example.com',
    //         'password' => bcrypt('password123'),
    //         'role' => 'user',
    //     ]);

    //     // Création de l'équipe
    //     $team = Teams::factory()->create([
    //         'owner' => $owner->id,
    //         'name' => 'TeamTest',
    //     ]);

    //     // Ajout du membre à l'équipe
    //     $response = $this->postJson("/api/teams/{$team->id}/members", [
    //         'user_id' => $user->id,
    //     ]);

    //     $response->assertStatus(200)->assertJson([
    //         'message' => 'Member added successfully',
    //     ]);

    //     // Vérification que le membre a bien été ajouté
    //     $this->assertDatabaseHas('team_user', [
    //         'user_id' => $user->id,
    //         'team_id' => $team->id,
    //     ]);

    //     // Suppression du membre
    //     $response = $this->deleteJson("/api/teams/{$team->id}/members", [
    //         'user_id' => $user->id,
    //     ]);

    //     $response->assertStatus(200)->assertJson([
    //         'message' => 'Member removed successfully',
    //     ]);

    //     // Vérification que le membre a été supprimé
    //     $this->assertDatabaseMissing('team_user', [
    //         'user_id' => $user->id,
    //         'team_id' => $team->id,
    //     ]);
    // }


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

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
     *Test that  team can be created.
     */
    public function test_teams_can_be_created(): void
    {
        $firstResponse = $this->post('/register', [
            'nickname' => 'UserTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'age' => 25,
            'nationality' => 'French',
            'role' => 'user',
            'avatar' => 'path/to/avatar.jpg',
        ]);

        // Pour un code 204, on vérifie juste le status
        $firstResponse->assertStatus(204);

        $user = User::where('email', 'user@example.com')->first();
        $this->assertNotNull($user);

        // Pour la création d'équipe, pas besoin de Team::create()
        // car on veut tester l'API, pas créer directement en base
        $teamData = [
            'owner' => (string) $user->id,
            'name' => 'TeamName',
            'logo' => 'path/to/logo.jpg',
            'country' => 'path/to/flag.jpg',
            'website' => 'https://siteweb.com/',
            'social' => json_encode([
                'facebook' => 'https://facebook.com/user',
                'twitter' => 'https://twitter.com/user',
                'linkedin' => 'https://linkedin.com/user'
            ])
        ];

        $teamResponse = $this->actingAs($user)
            ->post('/api/addTeam', $teamData);

        $teamResponse->assertStatus(201)
            ->assertJson(['message' => 'Team successfully added']);

        $this->assertDatabaseHas('teams', [
            'name' => 'TeamName',
            'owner' => $user->id,
        ]);
    }



    public function test_teams_cannot_be_created_with_missing_data(): void
    {
        $firstResponse = $this->post('/register', [
            'nickname' => 'UserTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'age' => 25,
            'nationality' => 'French',
            'role' => 'user',
            'avatar' => 'path/to/avatar.jpg',
        ]);

        $firstResponse->assertStatus(204);

        $user = User::where('email', 'user@example.com')->first();
        $this->assertNotNull($user);

        $teamData = [
            'owner' => (string) $user->id,
            'name' => '', // Nom invalide
            'logo' => 'path/to/logo.jpg',
            'country' => 'path/to/flag.jpg',
            'website' => 'https://siteweb.com/',
            'social' => json_encode([
                'facebook' => 'https://facebook.com/user',
                'twitter' => 'https://twitter.com/user',
                'linkedin' => 'https://linkedin.com/user'
            ])
        ];

        $teamResponse = $this->actingAs($user)
            ->postJson('/api/addTeam', $teamData); // Utilisez postJson

        $teamResponse->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }



// TODO AJOUT ET SUPPRESION DE TEAM MEMBERS PAR UN MEMBRE AVEC LES DROIT ET SANS 
    //     public function up(): void
    // {
    //     Schema::create('teams_members', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('user_id')
    //               ->constrained('users','id')
    //               ->onDelete('cascade');
    //         $table->foreignId('team_id')
    //               ->constrained('teams','id')
    //               ->onDelete('cascade');
    //         $table->timestamps();
    //     });
    // }

}
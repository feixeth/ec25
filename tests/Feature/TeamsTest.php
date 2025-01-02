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
        $user = User::factory()->create([
            'nickname' => 'UserTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'age' => 25,
            'nationality' => 'French',
            'role' => 'user',
            'avatar' => 'path/to/avatar.jpg',
        ]);

        $firstResponse = $this->post('/api/register', $user);
        $firstResponse->assertStatus(200)
                ->assertJson(['message' => ' User succesfully added']);
        $this->assertDatabaseHas('coaches', $coachData);

        $team = Team::create([
            'owner' => $this->user,
            'name' => 'TeamName',
            'logo' => 'path/to/logo.jpg',
            'country' => 'path/to/flag.jpg',
            'website' => 'https://siteweb.com/',
            'social' => 'https://siteweb.com/'
            ]);

        $secondResponse = $this->post('/api/addTeam', $team);
        $secondResponse->assertStatus(200)
                ->assertJson(['message' => ' Team succesfully added']);
        $this->assertDatabaseHas('teams', $team);

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
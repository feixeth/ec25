<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

        // coach data test 
    public function test_new_users_can_register_as_coaches_with_full_data(): void
    {
        $response = $this->post('/register', [
            'nickname' => 'CoachTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'coach@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'age' => 25,
            'nationality' => 'French',
            'role' => 'coach',
            'avatar' => 'path/to/avatar.jpg'
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        // user check in DB 
        $this->assertDatabaseHas('users', [
            'nickname' => 'CoachTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'coach@example.com',
            'age' => 25,
            'nationality' => 'French',
            'role' => 'coach'
        ]);

        // hask check 
        $user = User::where('email', 'coach@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    // client data test 
    public function test_new_users_can_register_as_clients_with_full_data(): void
    {
        $response = $this->post('/register', [
            'nickname' => 'ClientTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'client@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'age' => 25,
            'nationality' => 'French',
            'role' => 'user',
            'avatar' => 'path/to/avatar.jpg'
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $this->assertDatabaseHas('users', [
            'nickname' => 'ClientTest',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'client@example.com',
            'age' => 25,
            'nationality' => 'French',
            'role' => 'user'
        ]);
    }

    // Test de validation
public function test_cannot_register_with_duplicate_email_or_nickname(): void
{
    // Crée un premier utilisateur
    User::factory()->create([
        'nickname' => 'ExistingCoach',
        'email' => 'existing@example.com'
    ]);

    // cree un utilisateur avec le meme email
    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->post('/register', [
        'nickname' => 'NewCoach',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'coach'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    // cree un user avec le meme nickname
    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->post('/register', [
        'nickname' => 'ExistingCoach',
        'email' => 'new@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'coach'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['nickname']);
}
}

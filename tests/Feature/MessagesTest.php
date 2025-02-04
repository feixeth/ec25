<?php

// tests/Feature/MessageControllerTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Messages;
use App\Models\Conversations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessagesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_their_messages()
    {
        $this->actingAs($this->user);

        $otherUser = User::factory()->create();

        $conversation = Conversations::create([
            'subject' => 'Test Conversation'
        ]);

        Messages::factory(3)->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $otherUser->id,
            'conversations_id' => $conversation->id
        ]);

        // Manque le conversation user model et controller ou non ? reflechis 
        // Car ici pour checker ces messages on a besoin de creer le message, et donc de creer la conversation mais 
        // egalement l entree dans latable conversation_users , donc deux entree, une entree id 1 avec le user 1 et l'id de conv 1
        // et une netree id 2 user 2 conv 1
        // Puis on retourne la liste des conversation de l'utilisateur avec une methode controller 



        $response = $this->getJson('/api/messages');
        
        $response->assertStatus(200)
                ->assertJsonCount(1) // Groupé par conversation
                ->assertJsonStructure([
                    $otherUser->id => [
                        '*' => ['id', 'content', 'sender_id', 'recipient_id', 'created_at']
                    ]
                ]);
    }

    public function test_user_can_send_a_message()
    {
        $this->actingAs($this->user);
        $recipient = User::factory()->create();

        $response = $this->postJson('/api/messages', [
            'recipient_id' => $recipient->id,
            'content' => 'Test message'
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'sender_id' => $this->user->id,
                    'recipient_id' => $recipient->id,
                    'content' => 'Test message'
                ]);
                
        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->user->id,
            'recipient_id' => $recipient->id,
            'content' => 'Test message'
        ]);
    }

    public function test_user_can_get_conversation_with_another_user()
    {
        $this->actingAs($this->user);
        $otherUser = User::factory()->create();
        
        Messages::factory(5)->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $otherUser->id
        ]);

        $response = $this->getJson("/api/messages/conversation/{$otherUser->id}");
        
        $response->assertStatus(200)
                ->assertJsonCount(5, 'data')
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'content', 'sender_id', 'recipient_id', 'created_at']
                    ]
                ]);
    }

    public function test_user_can_mark_messages_as_read()
    {
        $this->actingAs($this->user);
        $sender = User::factory()->create();
        
        $messages = Messages::factory(3)->create([
            'sender_id' => $sender->id,
            'recipient_id' => $this->user->id,
            'is_read' => false
        ]);

        $response = $this->postJson("/api/messages/read/{$sender->id}");
        
        $response->assertStatus(200);
        $this->assertEquals(0, Messages::where('is_read', false)->count());
    }
}

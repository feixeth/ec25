<?php

// tests/Feature/MessageControllerTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Messages;
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
        
        Messages::factory(3)->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $otherUser->id
        ]);

        $response = $this->getJson('/api/messages');
        
        $response->assertStatus(200)
                ->assertJsonCount(1) // Groupé par conversation
                ->assertJsonStructure([
                    $otherUser->id => [
                        '*' => ['id', 'messages', 'sender_id', 'recipient_id', 'created_at']
                    ]
                ]);
    }

    public function test_user_can_send_a_message()
    {
        $this->actingAs($this->user);
        $recipient = User::factory()->create();

        $response = $this->postJson('/api/messages', [
            'recipient_id' => $recipient->id,
            'message' => 'Test message'
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'sender_id' => $this->user->id,
                    'recipient_id' => $recipient->id,
                    'messages' => 'Test message'
                ]);
                
        $this->assertDatabaseHas('mesages', [
            'sender_id' => $this->user->id,
            'recipient_id' => $recipient->id,
            'messages' => 'Test message'
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
                        '*' => ['id', 'messages', 'sender_id', 'recipient_id', 'created_at']
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

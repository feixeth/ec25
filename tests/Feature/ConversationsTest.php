<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Conversations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_group_conversation()
    {
        $this->actingAs($this->user);
        $participants = User::factory(2)->create();

        $response = $this->postJson('/api/conversations', [
            'subject' => 'Test Group',
            'message' => 'First message',
            'participants' => $participants->pluck('id')->toArray()
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'subject' => 'Test Group'
                ]);

        $this->assertDatabaseHas('conversations', [
            'subject' => 'Test Group'
        ]);
    }

    public function test_user_can_get_their_conversations()
    {
        $this->actingAs($this->user);
        $conversation = Conversations::factory()->create();
        $conversation->participants()->attach($this->user->id);

        $response = $this->getJson('/api/conversations');

        $response->assertStatus(200)
                ->assertJsonCount(1)
                ->assertJsonStructure([
                    '*' => ['id', 'subject', 'created_at']
                ]);
    }

    public function test_user_cannot_access_conversation_they_are_not_part_of()
    {
        $this->actingAs($this->user);
        $conversation = Conversations::factory()->create();

        $response = $this->getJson("/api/conversations/{$conversation->id}");

        $response->assertStatus(403);
    }
}
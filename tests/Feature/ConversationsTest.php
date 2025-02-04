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
        // Renommons d'abord la méthode pour plus de clarté
        $this->actingAs($this->user, 'sanctum');
        
        // On crée UN SEUL destinataire, pas plusieurs
        $recipient = User::factory()->create();

        $response = $this->postJson('/api/conversations', [
            'subject' => 'Test Group',
            'content' => 'First message',
            'recipient_id' => $recipient->id  // On envoie l'ID du destinataire unique
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'subject' => 'Test Group'
            ]);

        $this->assertDatabaseHas('conversations', [
            'subject' => 'Test Group'
        ]);

        $this->assertDatabaseHas('messages', [
            'content' => 'First message',
            'sender_id' => $this->user->id,
            'recipient_id' => $recipient->id  // Vérifie que le recipient_id est bien enregistré
        ]);
    }

    public function test_user_can_get_their_conversation()
    {
        $this->actingAs($this->user, 'sanctum');
        
        // On crée un autre utilisateur pour la conversation
        $recipient = User::factory()->create();
        
        // On crée une conversation avec un message
        $conversation = Conversations::create([
            'subject' => 'Test Conversation'
        ]);
        
        // On attache les deux participants
        $conversation->participants()->attach([
            $this->user->id,
            $recipient->id
        ]);
        
        // On ajoute un message à la conversation
        $conversation->messages()->create([
            'content' => 'Test message',
            'sender_id' => $this->user->id,
            'recipient_id' => $recipient->id
        ]);

        // On fait la requête GET pour récupérer la conversation spécifique
        $response = $this->getJson("/api/conversations/{$conversation->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $conversation->id,
                'subject' => 'Test Conversation',
                'messages' => [
                    [
                        'content' => 'Test message',
                        'sender_id' => $this->user->id,
                        'recipient_id' => $recipient->id
                    ]
                ]
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
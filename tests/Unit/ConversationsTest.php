<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Messages;
use App\Models\Conversations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConversationsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_conversation_can_have_participants()
    {
        $conversation = Conversations::factory()->create();
        $users = User::factory(3)->create();
        
        $conversation->participants()->attach($users->pluck('id'));
        
        $this->assertEquals(3, $conversation->participants->count());
    }

    public function test_conversation_can_have_messages()
    {
        $conversation = Conversations::factory()->create();
        $messages = Messages::factory(3)->create([
            'conversations_id' => $conversation->id
        ]);
        
        $this->assertEquals(3, $conversation->messages->count());
    }

    public function test_conversation_can_get_latest_message()
    {
        $conversation = Conversations::factory()->create();
        $oldMessage = Messages::factory()->create([
            'conversations_id' => $conversation->id,
            'created_at' => now()->subDay()
        ]);
        $newMessage = Messages::factory()->create([
            'conversations_id' => $conversation->id,
            'created_at' => now()
        ]);
        
        $this->assertEquals($newMessage->id, $conversation->latestMessage->id);
    }
}
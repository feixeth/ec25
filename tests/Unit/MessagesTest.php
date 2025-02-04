<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Messages;
use App\Models\Conversations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessagesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_a_message_belongs_to_a_sender()
    {

        $conversation = Conversations::factory()->create();
        $user = User::factory()->create();
        $message = Messages::factory()->create([
            'sender_id' => $user->id,
            'conversations_id' => $conversation->id
        ]);


        $this->assertInstanceOf(User::class, $message->sender);
        $this->assertEquals($user->id, $message->sender->id);
    }

    public function test_a_message_belongs_to_a_recipient()
    {
        $conversation = Conversations::factory()->create();
        $user = User::factory()->create();
        $message = Messages::factory()->create([
            'sender_id' => $user->id,
            'recipient_id' => $user->id, 
            'conversations_id' => $conversation->id
        ]);
        $this->assertInstanceOf(User::class, $message->recipient);
        $this->assertEquals($user->id, $message->recipient->id);
    }

    public function test_a_message_can_be_marked_as_read()
    {
        $user = User::factory()->create();
        $conversation = Conversations::factory()->create();
        $message = Messages::factory()->create([
            'is_read' => false,
            'conversations_id' => $conversation->id,
            'sender_id' => $user->id,
            'recipient_id' => $user->id, 
            ]
        );
        $this->assertInstanceOf(User::class, $message->recipient);
        $this->assertEquals($user->id, $message->recipient->id);
        $message->update([
            'sender_id' => $user->id,
            'recipient_id' => $user->id, 
            'conversations_id' => $conversation->id,
            'is_read' => true,
        ]);
        
        $this->assertEquals(1, $message->fresh()->is_read);
    }

    public function test_a_message_can_be_soft_deleted()
    {
        $user = User::factory()->create();
        $conversation = Conversations::factory()->create();
        $message = Messages::factory()->create([
            'is_read' => false,
            'conversations_id' => $conversation->id,
            'sender_id' => $user->id,
            'recipient_id' => $user->id, 
            ]
        );
        
        $message->delete();
        
        $this->assertSoftDeleted($message);
    }
}


<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Messages;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessagesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_a_message_belongs_to_a_sender()
    {
        $user = User::factory()->create();
        $message = Messages::factory()->create(['sender_id' => $user->id]);

        $this->assertInstanceOf(User::class, $message->sender);
        $this->assertEquals($user->id, $message->sender->id);
    }

    public function test_a_message_belongs_to_a_recipient()
    {
        $user = User::factory()->create();
        $message = Messages::factory()->create(['recipient_id' => $user->id]);

        $this->assertInstanceOf(User::class, $message->recipient);
        $this->assertEquals($user->id, $message->recipient->id);
    }

    public function test_a_message_can_be_marked_as_read()
    {
        $message = Messages::factory()->create(['is_read' => false]);
        
        $message->update(['is_read' => true]);
        
        $this->assertTrue($message->fresh()->is_read);
    }

    public function test_a_message_can_be_soft_deleted()
    {
        $message = Messages::factory()->create();
        
        $message->delete();
        
        $this->assertSoftDeleted($message);
    }
}


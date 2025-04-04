<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\Notifications;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function a_user_can_receive_a_notification()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a notification for the user
        $notification = Notifications::create([
            'user_id' => $user->id,
            'type' => 'info',
            'message' => 'This is a test notification',
            'is_read' => false,
        ]);

        // Assert the notification exists in the database
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'user_id' => $user->id,
            'message' => 'This is a test notification',
            'is_read' => false,
        ]);
    }

    #[Test]
    public function a_user_can_view_their_notifications()
    {
        // Create a user with auth capabilities
        $user = User::factory()->create();
        
        // Create a notification for the user
        $notification = Notifications::create([
            'user_id' => $user->id,
            'type' => 'info',
            'message' => 'Test notification',
            'is_read' => false,
        ]);

        // sanctum to solve the Signal11 error whe nswitching from webguard to apiguard
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/notifications');
        
        // Assert the response includes the notification
        $response->assertStatus(200)
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.id', $notification->id);
    }

    #[Test]
    public function a_user_can_mark_a_notification_as_read()
    {
        $user = User::factory()->create();
        $notification = Notifications::create([
            'user_id' => $user->id,
            'type' => 'info',
            'message' => 'Test notification',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user, 'sanctum')
                        ->postJson('/api/notifications/' . $notification->id . '/read');

        $response->assertStatus(200);
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    #[Test]
    public function a_user_can_get_unread_notification_count()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create some notifications (2 unread, 1 read)
        Notifications::create([
            'user_id' => $user->id,
            'type' => 'info',
            'message' => 'Unread notification 1',
            'is_read' => false,
        ]);
        
        Notifications::create([
            'user_id' => $user->id,
            'type' => 'info',
            'message' => 'Unread notification 2',
            'is_read' => false,
        ]);
        
        Notifications::create([
            'user_id' => $user->id,
            'type' => 'info',
            'message' => 'Read notification',
            'is_read' => true,
        ]);

        // sanctum to solve the Signal11 error whe nswitching from webguard to apiguard
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/notifications');
        
        // Assert count is correct
        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }
}
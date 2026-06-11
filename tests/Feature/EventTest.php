<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_event(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/events', [
            'title' => 'A社 面接',
            'type' => '面接',
            'start_at' => '2026-06-10 14:00',
            'end_at' => '2026-06-10 15:00',
        ]);

        $response->assertRedirect('/calendar');
        $this->assertDatabaseHas('events', [
            'user_id' => $user->id,
            'title' => 'A社 面接',
        ]);
    }

    public function test_user_cannot_edit_others_event(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $other->id]);

        $this->actingAs($me)->get("/events/{$event->id}/edit")->assertForbidden();
    }
}

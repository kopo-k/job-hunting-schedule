<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_json_returns_red_for_overlap(): void
    {
        $user = User::factory()->create();
        Event::factory()->create([
            'user_id' => $user->id,
            'start_at' => '2026-06-10 14:00:00',
            'end_at' => '2026-06-10 15:00:00',
        ]);
        Event::factory()->create([
            'user_id' => $user->id,
            'start_at' => '2026-06-10 14:30:00',
            'end_at' => '2026-06-10 15:30:00',
        ]);

        $response = $this->actingAs($user)->getJson('/calendar/events');

        $response->assertOk();
        $colors = collect($response->json())->pluck('color');
        $this->assertTrue($colors->every(fn ($c) => $c === '#ef4444'));
    }

    public function test_calendar_json_excludes_other_users(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        Event::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($me)->getJson('/calendar/events');

        $response->assertOk();
        $this->assertCount(0, $response->json());
    }
}

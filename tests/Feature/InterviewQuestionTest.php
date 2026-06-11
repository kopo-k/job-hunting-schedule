<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterviewQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_question_to_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/events/{$event->id}/questions", [
            'question' => '志望動機は？',
            'result' => 'bad',
            'improvement_memo' => '結論から話す',
        ]);

        $response->assertRedirect("/events/{$event->id}");
        $this->assertDatabaseHas('interview_questions', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'question' => '志望動機は？',
            'result' => 'bad',
        ]);
    }

    public function test_user_cannot_add_question_to_others_event(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $other->id]);

        $this->actingAs($me)->post("/events/{$event->id}/questions", [
            'question' => 'x',
            'result' => 'bad',
        ])->assertForbidden();
    }
}

<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\InterviewQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeakQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_only_bad_questions_of_current_user(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);
        InterviewQuestion::factory()->create([
            'user_id' => $user->id, 'event_id' => $event->id,
            'question' => '苦手な質問A', 'result' => 'bad',
        ]);
        InterviewQuestion::factory()->create([
            'user_id' => $user->id, 'event_id' => $event->id,
            'question' => '得意な質問B', 'result' => 'good',
        ]);

        $response = $this->actingAs($user)->get('/weak-questions');

        $response->assertOk();
        $response->assertSee('苦手な質問A');
        $response->assertDontSee('得意な質問B');
    }

    public function test_does_not_show_other_users_bad_questions(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $other->id]);
        InterviewQuestion::factory()->create([
            'user_id' => $other->id, 'event_id' => $event->id,
            'question' => '他人の苦手質問', 'result' => 'bad',
        ]);

        $response = $this->actingAs($me)->get('/weak-questions');

        $response->assertOk();
        $response->assertDontSee('他人の苦手質問');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\InterviewQuestion;
use Illuminate\Http\Request;

class InterviewQuestionController extends Controller
{
    public function store(Request $request, Event $event)
    {
        abort_if($event->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'question' => 'required|string',
            'result' => 'required|in:good,ok,bad',
            'improvement_memo' => 'nullable|string',
        ]);
        $data['user_id'] = auth()->id();
        $data['event_id'] = $event->id;
        InterviewQuestion::create($data);

        return redirect("/events/{$event->id}");
    }

    public function destroy(InterviewQuestion $question)
    {
        abort_if($question->user_id !== auth()->id(), 403);
        $eventId = $question->event_id;
        $question->delete();
        return redirect("/events/{$eventId}");
    }
}

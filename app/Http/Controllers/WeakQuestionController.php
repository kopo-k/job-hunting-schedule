<?php

namespace App\Http\Controllers;

use App\Models\InterviewQuestion;

class WeakQuestionController extends Controller
{
    public function index()
    {
        $questions = InterviewQuestion::where('user_id', auth()->id())
            ->where('result', 'bad')
            ->with('event.company')
            ->latest()
            ->get();

        return view('weak_questions.index', compact('questions'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\ConflictDetector;

class CalendarController extends Controller
{
    private const COLORS = [
        'red' => '#ef4444',
        'yellow' => '#eab308',
        'normal' => '#3b82f6',
    ];

    public function index()
    {
        return view('calendar.index');
    }

    public function events(ConflictDetector $detector)
    {
        $events = Event::where('user_id', auth()->id())->get();

        $statuses = $detector->detect(
            $events->map(fn ($e) => [
                'id' => $e->id,
                'start' => $e->start_at,
                'end' => $e->end_at,
            ])->all()
        );

        return $events->map(fn ($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'start' => $e->start_at->toIso8601String(),
            'end' => $e->end_at->toIso8601String(),
            'color' => self::COLORS[$statuses[$e->id]],
            'url' => "/events/{$e->id}",
        ])->values();
    }
}

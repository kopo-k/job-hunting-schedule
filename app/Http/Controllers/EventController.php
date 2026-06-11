<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create()
    {
        $companies = Company::where('user_id', auth()->id())->get();
        return view('events.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $this->validateEvent($request);
        $data['user_id'] = auth()->id();
        Event::create($data);
        return redirect('/calendar');
    }

    public function show(Event $event)
    {
        $this->authorizeOwner($event);
        $event->load('interviewQuestions', 'company');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorizeOwner($event);
        $companies = Company::where('user_id', auth()->id())->get();
        return view('events.edit', compact('event', 'companies'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOwner($event);
        $event->update($this->validateEvent($request));
        return redirect('/calendar');
    }

    public function destroy(Event $event)
    {
        $this->authorizeOwner($event);
        $event->delete();
        return redirect('/calendar');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'location' => 'nullable|string|max:255',
        ]);
    }

    private function authorizeOwner(Event $event): void
    {
        abort_if($event->user_id !== auth()->id(), 403);
    }
}

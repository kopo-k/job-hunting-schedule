<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Event;
use App\Services\ConflictDetector;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('user_id', auth()->id())->latest()->get();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'memo' => 'nullable|string',
        ]);
        $data['user_id'] = auth()->id();
        Company::create($data);
        return redirect('/companies');
    }

    public function show(Company $company, ConflictDetector $detector)
    {
        $this->authorizeOwner($company);
        $company->load('events');

        $allEvents = Event::where('user_id', auth()->id())->get();
        $statuses = $detector->detect(
            $allEvents->map(fn ($e) => ['id' => $e->id, 'start' => $e->start_at, 'end' => $e->end_at])->all()
        );

        return view('companies.show', compact('company', 'statuses'));
    }

    public function edit(Company $company)
    {
        $this->authorizeOwner($company);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeOwner($company);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'memo' => 'nullable|string',
        ]);
        $company->update($data);
        return redirect('/companies');
    }

    public function destroy(Company $company)
    {
        $this->authorizeOwner($company);
        $company->delete();
        return redirect('/companies');
    }

    private function authorizeOwner(Company $company): void
    {
        abort_if($company->user_id !== auth()->id(), 403);
    }
}

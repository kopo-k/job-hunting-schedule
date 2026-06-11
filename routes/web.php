<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InterviewQuestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeakQuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (\App\Services\ConflictDetector $detector) {
    $userId = auth()->id();
    $allEvents = \App\Models\Event::where('user_id', $userId)->with('company')->get();
    $statuses = $detector->detect(
        $allEvents->map(fn ($e) => ['id' => $e->id, 'start' => $e->start_at, 'end' => $e->end_at])->all()
    );

    $future = $allEvents->where('start_at', '>=', now())->sortBy('start_at');
    $upcoming = $future->take(5);

    return view('dashboard', [
        'companyCount' => \App\Models\Company::where('user_id', $userId)->count(),
        'eventCount' => $allEvents->count(),
        'weakCount' => \App\Models\InterviewQuestion::where('user_id', $userId)->where('result', 'bad')->count(),
        'upcoming' => $upcoming,
        'statuses' => $statuses,
        // これから来る予定のうち重複(赤)のもの（具体名を出して優先判断を促す）
        'conflictEvents' => $future->filter(fn ($e) => ($statuses[$e->id] ?? '') === 'red')->values(),
        // 7日以内に迫る予定（ES締切など。1週間前から準備したい就活生のニーズに合わせる）
        'soonEvents' => $future->filter(fn ($e) => $e->start_at->lte(now()->addDays(7)))->values(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::get('/calendar/events', [CalendarController::class, 'events']);
    Route::resource('companies', CompanyController::class);
    Route::resource('events', EventController::class)->except(['index']);
    Route::post('/events/{event}/questions', [InterviewQuestionController::class, 'store']);
    Route::delete('/questions/{question}', [InterviewQuestionController::class, 'destroy']);
    Route::get('/weak-questions', [WeakQuestionController::class, 'index']);
});

require __DIR__.'/auth.php';

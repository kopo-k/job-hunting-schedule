# 就活スケジュール管理アプリ 実装計画

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 就活の予定を信号機カラーで被り検出しつつ、面接で答えられなかった質問を横断リスト化できるLaravel製Webアプリを作る。

**Architecture:** Laravel(Blade SSR/MVC)モノリス。認証はBreeze。カレンダー画面のみFullCalendar.jsでJSONを描画。被り検出は純粋ロジックのServiceクラスに分離し単体テストする。DBはMySQL、実行環境はDocker(Laravel Sail)。

**Tech Stack:** PHP / Laravel / Blade / Tailwind CSS / FullCalendar.js / MySQL / Docker(Laravel Sail) / PHPUnit

設計書: `docs/superpowers/specs/2026-06-08-job-hunting-schedule-design.md`

---

## ファイル構成（このプランで作る/触る主なファイル）

- `docker-compose.yml`, `.env` — Sail/MySQL設定（Task 0で生成）
- `app/Models/{Company,Event,InterviewQuestion}.php` — Eloquentモデル
- `database/migrations/*` — 各テーブル定義
- `app/Http/Controllers/{CompanyController,EventController,CalendarController,InterviewQuestionController,WeakQuestionController}.php`
- `app/Services/ConflictDetector.php` — 被り検出ロジック（純粋関数・単体テスト対象）
- `resources/views/{companies,events,calendar,interview_questions,weak_questions}/*.blade.php`
- `routes/web.php` — ルーティング
- `tests/Unit/ConflictDetectorTest.php` — 被り検出の単体テスト
- `tests/Feature/*Test.php` — 各CRUD・認可のフィーチャテスト
- `database/factories/*` — テスト用ファクトリ

各コントローラ/モデルは1責務。被り検出は画面・DBから切り離してServiceに置く。

---

## 用語・前提コマンド

- Sailの実行は `./vendor/bin/sail`（以下 `sail` と表記）。
- テスト実行は `sail artisan test`（PHPUnit）。
- 全ての一覧/取得クエリは**ログインユーザーのデータのみ**に絞る（`where('user_id', auth()->id())`）。漏れは認可バグなのでフィーチャテストで担保する。

---

## Task 0: Laravel + Sail (Docker/MySQL) の足場をつくる

**Files:**
- Create: `composer.json`, `docker-compose.yml`, `.env`, `artisan` ほかLaravel一式（自動生成）

> 注: 既存の `docs/`・`CLAUDE.md`・`.gitignore`・`.git` は残す。Laravelは空でないディレクトリに直接生成できないため、一時ディレクトリに生成して中身を移動する。

- [ ] **Step 1: Dockerで一時ディレクトリにLaravel+Sail(MySQL)を生成**

ローカルPHP不要（Docker利用）。
```bash
cd /tmp
rm -rf jhs-tmp
curl -s "https://laravel.build/jhs-tmp?with=mysql" | bash
```
Expected: `/tmp/jhs-tmp` にLaravelプロジェクトが生成される。

- [ ] **Step 2: 生成物をプロジェクトへ移動（既存ファイルは保持）**

```bash
rsync -a --exclude='.git' /tmp/jhs-tmp/ /Users/k24032kk/job-hunting-schedule/
rm -rf /tmp/jhs-tmp
cd /Users/k24032kk/job-hunting-schedule
```
Expected: `artisan` `composer.json` `docker-compose.yml` がプロジェクト直下に存在する。

- [ ] **Step 3: コンテナ起動**

```bash
./vendor/bin/sail up -d
```
Expected: `app` と `mysql` コンテナが起動（`docker ps` で2つ確認）。

- [ ] **Step 4: アプリキー生成とDB疎通確認（初期マイグレーション）**

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```
Expected: マイグレーションが成功し `Migrating: ... Done` が表示される（MySQL接続OKの証拠）。

- [ ] **Step 5: DBユーザがroot以外であることを確認（提出要件）**

`.env` を確認する。
```bash
grep '^DB_USERNAME' .env
```
Expected: `DB_USERNAME=sail`（root以外の一般ユーザ）。Sailが自動で一般ユーザ `sail` を作成するため要件を満たす。rootになっていた場合は `.env` の `DB_USERNAME` を `sail` に修正し `sail down -v && sail up -d` で作り直す。

- [ ] **Step 6: 起動確認**

ブラウザで `http://localhost` を開く。
Expected: Laravelのwelcome画面が表示される。

- [ ] **Step 7: テストが動くことを確認**

```bash
./vendor/bin/sail artisan test
```
Expected: 既定のサンプルテストがPASS。

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "Laravel+Sail(Docker/MySQL)の足場を追加"
```

---

## Task 1: 認証(Breeze/Blade)の導入

**Files:**
- Create: Breezeが生成する認証ルート・コントローラ・Blade画面一式
- Modify: `routes/web.php`

- [ ] **Step 1: Breezeをインストール（Bladeスタック）**

```bash
./vendor/bin/sail composer require laravel/breeze --dev
./vendor/bin/sail artisan breeze:install blade
./vendor/bin/sail npm install
./vendor/bin/sail artisan migrate
```
Expected: 認証用のビュー/ルート、Tailwind設定が追加される。

- [ ] **Step 2: フロントエンドをビルド（開発サーバ）**

```bash
./vendor/bin/sail npm run dev
```
別ターミナルで起動したままにする（Viteの開発サーバ）。

- [ ] **Step 3: 認証フィーチャテストを実行（Breeze同梱）**

```bash
./vendor/bin/sail artisan test --filter=Auth
```
Expected: 登録・ログイン関連テストがPASS。

- [ ] **Step 4: 手動確認**

`http://localhost/register` でユーザー登録 → `/dashboard` に入れること。
Expected: 登録後ログイン状態でダッシュボード表示。

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "Laravel Breeze(Blade)で認証を追加"
```

---

## Task 2: 企業(Company)モデルとCRUD

**Files:**
- Create: `app/Models/Company.php`, `database/migrations/xxxx_create_companies_table.php`, `database/factories/CompanyFactory.php`, `app/Http/Controllers/CompanyController.php`, `resources/views/companies/{index,create,edit,show}.blade.php`
- Modify: `routes/web.php`, `app/Models/User.php`
- Test: `tests/Feature/CompanyTest.php`

- [ ] **Step 1: マイグレーションとモデルを生成**

```bash
./vendor/bin/sail artisan make:model Company -mfc --resource
```
Expected: モデル・マイグレーション・ファクトリ・リソースコントローラが生成される。

- [ ] **Step 2: マイグレーションを定義**

`database/migrations/xxxx_create_companies_table.php` の `up()`:
```php
Schema::create('companies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('status')->default('エントリー');
    $table->text('memo')->nullable();
    $table->timestamps();
});
```

- [ ] **Step 3: モデルとリレーションを定義**

`app/Models/Company.php`:
```php
class Company extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'status', 'memo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
```
`app/Models/User.php` にリレーション追加:
```php
public function companies()
{
    return $this->hasMany(Company::class);
}
```

- [ ] **Step 4: ファクトリを定義**

`database/factories/CompanyFactory.php` の `definition()`:
```php
return [
    'user_id' => \App\Models\User::factory(),
    'name' => $this->faker->company(),
    'status' => 'エントリー',
    'memo' => null,
];
```

- [ ] **Step 5: 失敗するフィーチャテストを書く**

`tests/Feature/CompanyTest.php`:
```php
<?php
namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_company(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/companies', [
            'name' => 'テスト株式会社',
            'status' => 'エントリー',
        ]);

        $response->assertRedirect('/companies');
        $this->assertDatabaseHas('companies', [
            'user_id' => $user->id,
            'name' => 'テスト株式会社',
        ]);
    }

    public function test_user_sees_only_own_companies(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        Company::factory()->create(['user_id' => $me->id, 'name' => '自分の会社']);
        Company::factory()->create(['user_id' => $other->id, 'name' => '他人の会社']);

        $response = $this->actingAs($me)->get('/companies');

        $response->assertSee('自分の会社');
        $response->assertDontSee('他人の会社');
    }

    public function test_guest_cannot_access_companies(): void
    {
        $this->get('/companies')->assertRedirect('/login');
    }
}
```

- [ ] **Step 6: テストが失敗することを確認**

```bash
./vendor/bin/sail artisan test --filter=CompanyTest
```
Expected: ルート未定義などでFAIL。

- [ ] **Step 7: ルートを追加**

`routes/web.php`（`auth` ミドルウェア配下）:
```php
use App\Http\Controllers\CompanyController;

Route::middleware('auth')->group(function () {
    Route::resource('companies', CompanyController::class);
});
```

- [ ] **Step 8: コントローラを実装**

`app/Http/Controllers/CompanyController.php`:
```php
<?php
namespace App\Http\Controllers;

use App\Models\Company;
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

    public function show(Company $company)
    {
        $this->authorizeOwner($company);
        $company->load('events');
        return view('companies.show', compact('company'));
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
```

- [ ] **Step 9: Blade画面を作成**

`resources/views/companies/index.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">応募企業</h1>
            <a href="/companies/create" class="px-3 py-1 bg-blue-600 text-white rounded">新規登録</a>
        </div>
        <ul class="divide-y">
            @foreach ($companies as $company)
                <li class="py-3 flex justify-between">
                    <a href="/companies/{{ $company->id }}" class="font-medium">{{ $company->name }}</a>
                    <span class="text-sm text-gray-500">{{ $company->status }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
```
`resources/views/companies/create.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">企業を登録</h1>
        <form method="POST" action="/companies" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">企業名</label>
                <input name="name" class="border rounded w-full p-2" value="{{ old('name') }}" required>
                @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm">選考状況</label>
                <input name="status" class="border rounded w-full p-2" value="{{ old('status', 'エントリー') }}" required>
            </div>
            <div>
                <label class="block text-sm">メモ</label>
                <textarea name="memo" class="border rounded w-full p-2">{{ old('memo') }}</textarea>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
        </form>
    </div>
</x-app-layout>
```
`resources/views/companies/edit.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">企業を編集</h1>
        <form method="POST" action="/companies/{{ $company->id }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm">企業名</label>
                <input name="name" class="border rounded w-full p-2" value="{{ old('name', $company->name) }}" required>
                @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm">選考状況</label>
                <input name="status" class="border rounded w-full p-2" value="{{ old('status', $company->status) }}" required>
            </div>
            <div>
                <label class="block text-sm">メモ</label>
                <textarea name="memo" class="border rounded w-full p-2">{{ old('memo', $company->memo) }}</textarea>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">更新</button>
        </form>
    </div>
</x-app-layout>
```
`resources/views/companies/show.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">{{ $company->name }}</h1>
            <a href="/companies/{{ $company->id }}/edit" class="text-blue-600">編集</a>
        </div>
        <p class="text-sm text-gray-600 mb-2">状況: {{ $company->status }}</p>
        <p class="mb-4 whitespace-pre-line">{{ $company->memo }}</p>
        <h2 class="font-semibold mb-2">関連予定</h2>
        <ul class="list-disc pl-5">
            @foreach ($company->events as $event)
                <li>{{ $event->title }}（{{ $event->start_at }}）</li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
```

- [ ] **Step 10: テストが通ることを確認**

```bash
./vendor/bin/sail artisan test --filter=CompanyTest
```
Expected: 3件すべてPASS。

- [ ] **Step 11: Commit**

```bash
git add -A
git commit -m "企業(Company)のCRUDとユーザー分離を追加"
```

---

## Task 3: 予定(Event)モデルとCRUD

**Files:**
- Create: `app/Models/Event.php`, `database/migrations/xxxx_create_events_table.php`, `database/factories/EventFactory.php`, `app/Http/Controllers/EventController.php`, `resources/views/events/{create,edit,show}.blade.php`
- Modify: `routes/web.php`, `app/Models/User.php`
- Test: `tests/Feature/EventTest.php`

- [ ] **Step 1: モデル等を生成**

```bash
./vendor/bin/sail artisan make:model Event -mfc --resource
```

- [ ] **Step 2: マイグレーションを定義**

`up()`:
```php
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
    $table->string('title');
    $table->string('type')->default('面接'); // 面接/説明会/ES締切/その他
    $table->dateTime('start_at');
    $table->dateTime('end_at');
    $table->string('location')->nullable();
    $table->timestamps();
});
```

- [ ] **Step 3: モデルを定義**

`app/Models/Event.php`:
```php
class Event extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'company_id', 'title', 'type', 'start_at', 'end_at', 'location'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function interviewQuestions()
    {
        return $this->hasMany(InterviewQuestion::class);
    }
}
```
`app/Models/User.php` に追加:
```php
public function events()
{
    return $this->hasMany(Event::class);
}
```

- [ ] **Step 4: ファクトリを定義**

`database/factories/EventFactory.php` の `definition()`:
```php
return [
    'user_id' => \App\Models\User::factory(),
    'company_id' => null,
    'title' => $this->faker->word() . ' 面接',
    'type' => '面接',
    'start_at' => '2026-06-10 14:00:00',
    'end_at' => '2026-06-10 15:00:00',
    'location' => 'オンライン',
];
```

- [ ] **Step 5: 失敗するフィーチャテストを書く**

`tests/Feature/EventTest.php`:
```php
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
```

- [ ] **Step 6: テストが失敗することを確認**

```bash
./vendor/bin/sail artisan test --filter=EventTest
```
Expected: FAIL（ルート/コントローラ未実装）。

- [ ] **Step 7: ルートを追加**

`routes/web.php` の `auth` グループ内:
```php
use App\Http\Controllers\EventController;

Route::resource('events', EventController::class)->except(['index']);
```

- [ ] **Step 8: コントローラを実装**

`app/Http/Controllers/EventController.php`:
```php
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
```

- [ ] **Step 9: Blade画面を作成**

`resources/views/events/create.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">予定を登録</h1>
        <form method="POST" action="/events" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">タイトル</label>
                <input name="title" class="border rounded w-full p-2" value="{{ old('title') }}" required>
                @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm">種別</label>
                <select name="type" class="border rounded w-full p-2">
                    @foreach (['面接','説明会','ES締切','その他'] as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">企業（任意）</label>
                <select name="company_id" class="border rounded w-full p-2">
                    <option value="">未選択</option>
                    @foreach ($companies as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">開始</label>
                <input type="datetime-local" name="start_at" class="border rounded w-full p-2" value="{{ old('start_at') }}" required>
            </div>
            <div>
                <label class="block text-sm">終了</label>
                <input type="datetime-local" name="end_at" class="border rounded w-full p-2" value="{{ old('end_at') }}" required>
                @error('end_at')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm">場所</label>
                <input name="location" class="border rounded w-full p-2" value="{{ old('location') }}">
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
        </form>
    </div>
</x-app-layout>
```
`resources/views/events/edit.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">予定を編集</h1>
        <form method="POST" action="/events/{{ $event->id }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm">タイトル</label>
                <input name="title" class="border rounded w-full p-2" value="{{ old('title', $event->title) }}" required>
            </div>
            <div>
                <label class="block text-sm">種別</label>
                <select name="type" class="border rounded w-full p-2">
                    @foreach (['面接','説明会','ES締切','その他'] as $t)
                        <option value="{{ $t }}" @selected($event->type === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">企業（任意）</label>
                <select name="company_id" class="border rounded w-full p-2">
                    <option value="">未選択</option>
                    @foreach ($companies as $c)
                        <option value="{{ $c->id }}" @selected($event->company_id === $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">開始</label>
                <input type="datetime-local" name="start_at" class="border rounded w-full p-2" value="{{ old('start_at', $event->start_at->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div>
                <label class="block text-sm">終了</label>
                <input type="datetime-local" name="end_at" class="border rounded w-full p-2" value="{{ old('end_at', $event->end_at->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div>
                <label class="block text-sm">場所</label>
                <input name="location" class="border rounded w-full p-2" value="{{ old('location', $event->location) }}">
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">更新</button>
            </div>
        </form>
        <form method="POST" action="/events/{{ $event->id }}" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="text-red-600 text-sm">この予定を削除</button>
        </form>
    </div>
</x-app-layout>
```
`resources/views/events/show.blade.php`（振り返りはTask 6で追記する土台）:
```blade
<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">{{ $event->title }}</h1>
            <a href="/events/{{ $event->id }}/edit" class="text-blue-600">編集</a>
        </div>
        <p class="text-sm text-gray-600">種別: {{ $event->type }}</p>
        <p class="text-sm text-gray-600">日時: {{ $event->start_at }} 〜 {{ $event->end_at }}</p>
        <p class="text-sm text-gray-600 mb-4">場所: {{ $event->location }}</p>
    </div>
</x-app-layout>
```

- [ ] **Step 10: テストが通ることを確認**

```bash
./vendor/bin/sail artisan test --filter=EventTest
```
Expected: 2件PASS。

- [ ] **Step 11: Commit**

```bash
git add -A
git commit -m "予定(Event)のCRUDと認可を追加"
```

---

## Task 4: 被り検出ロジック(ConflictDetector)

**Files:**
- Create: `app/Services/ConflictDetector.php`
- Test: `tests/Unit/ConflictDetectorTest.php`

> 設計: 純粋ロジック。入力は `['id'=>int, 'start'=>Carbon, 'end'=>Carbon]` の配列。出力は `[id => 'red'|'yellow'|'normal']`。優先順位は red > yellow > normal。黄判定の閾値は60分。

- [ ] **Step 1: 失敗する単体テストを書く**

`tests/Unit/ConflictDetectorTest.php`:
```php
<?php
namespace Tests\Unit;

use App\Services\ConflictDetector;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class ConflictDetectorTest extends TestCase
{
    private function ev(int $id, string $start, string $end): array
    {
        return ['id' => $id, 'start' => Carbon::parse($start), 'end' => Carbon::parse($end)];
    }

    public function test_overlapping_events_are_red(): void
    {
        $detector = new ConflictDetector();
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 14:30', '2026-06-10 15:30'),
        ]);

        $this->assertSame('red', $result[1]);
        $this->assertSame('red', $result[2]);
    }

    public function test_tight_gap_is_yellow(): void
    {
        $detector = new ConflictDetector();
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 15:30', '2026-06-10 16:30'), // 間隔30分 < 60
        ]);

        $this->assertSame('yellow', $result[1]);
        $this->assertSame('yellow', $result[2]);
    }

    public function test_wide_gap_is_normal(): void
    {
        $detector = new ConflictDetector();
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 17:00', '2026-06-10 18:00'), // 間隔120分
        ]);

        $this->assertSame('normal', $result[1]);
        $this->assertSame('normal', $result[2]);
    }

    public function test_red_takes_priority_over_yellow(): void
    {
        $detector = new ConflictDetector();
        // 1と2は重複(red)、2と3は間隔短い(yellow候補) → 2はredが優先
        $result = $detector->detect([
            $this->ev(1, '2026-06-10 14:00', '2026-06-10 15:00'),
            $this->ev(2, '2026-06-10 14:30', '2026-06-10 15:30'),
            $this->ev(3, '2026-06-10 16:00', '2026-06-10 17:00'), // 2の終了から30分
        ]);

        $this->assertSame('red', $result[1]);
        $this->assertSame('red', $result[2]);
        $this->assertSame('yellow', $result[3]);
    }
}
```

- [ ] **Step 2: テストが失敗することを確認**

```bash
./vendor/bin/sail artisan test --filter=ConflictDetectorTest
```
Expected: FAIL（クラス未定義）。

- [ ] **Step 3: ConflictDetectorを実装**

`app/Services/ConflictDetector.php`:
```php
<?php
namespace App\Services;

class ConflictDetector
{
    private int $gapThresholdMinutes;

    public function __construct(int $gapThresholdMinutes = 60)
    {
        $this->gapThresholdMinutes = $gapThresholdMinutes;
    }

    /**
     * @param array<int, array{id:int, start:\Illuminate\Support\Carbon, end:\Illuminate\Support\Carbon}> $events
     * @return array<int, string> id => 'red'|'yellow'|'normal'
     */
    public function detect(array $events): array
    {
        $status = [];
        foreach ($events as $e) {
            $status[$e['id']] = 'normal';
        }

        $count = count($events);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $a = $events[$i];
                $b = $events[$j];

                // 重複: a.start < b.end AND b.start < a.end
                if ($a['start']->lt($b['end']) && $b['start']->lt($a['end'])) {
                    $status[$a['id']] = 'red';
                    $status[$b['id']] = 'red';
                    continue;
                }

                // 間隔（早い方の終了〜遅い方の開始）
                if ($a['start']->lte($b['start'])) {
                    $prev = $a; $next = $b;
                } else {
                    $prev = $b; $next = $a;
                }
                $gap = $prev['end']->diffInMinutes($next['start'], false);
                if ($gap >= 0 && $gap < $this->gapThresholdMinutes) {
                    // redを上書きしない
                    if ($status[$prev['id']] !== 'red') {
                        $status[$prev['id']] = 'yellow';
                    }
                    if ($status[$next['id']] !== 'red') {
                        $status[$next['id']] = 'yellow';
                    }
                }
            }
        }

        return $status;
    }
}
```

- [ ] **Step 4: テストが通ることを確認**

```bash
./vendor/bin/sail artisan test --filter=ConflictDetectorTest
```
Expected: 4件すべてPASS。

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "被り検出ロジック(ConflictDetector)を単体テスト付きで追加"
```

---

## Task 5: カレンダー画面(FullCalendar.js + 信号機カラー)

**Files:**
- Create: `app/Http/Controllers/CalendarController.php`, `resources/views/calendar/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/CalendarTest.php`

> カレンダー画面はBladeでFullCalendarを読み込み、`/calendar/events`(JSON)から予定＋色を取得して描画する。色はConflictDetectorの結果から決定。

- [ ] **Step 1: 失敗するフィーチャテストを書く（JSONエンドポイント）**

`tests/Feature/CalendarTest.php`:
```php
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
```

- [ ] **Step 2: テストが失敗することを確認**

```bash
./vendor/bin/sail artisan test --filter=CalendarTest
```
Expected: FAIL（ルート未定義）。

- [ ] **Step 3: ルートを追加**

`routes/web.php` の `auth` グループ内:
```php
use App\Http\Controllers\CalendarController;

Route::get('/calendar', [CalendarController::class, 'index']);
Route::get('/calendar/events', [CalendarController::class, 'events']);
```

- [ ] **Step 4: コントローラを実装**

`app/Http/Controllers/CalendarController.php`:
```php
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
```

- [ ] **Step 5: テストが通ることを確認**

```bash
./vendor/bin/sail artisan test --filter=CalendarTest
```
Expected: 2件PASS。

- [ ] **Step 6: カレンダーBladeを作成（FullCalendar読み込み）**

`resources/views/calendar/index.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">カレンダー</h1>
            <a href="/events/create" class="px-3 py-1 bg-blue-600 text-white rounded">予定を追加</a>
        </div>
        <div class="flex gap-4 text-sm mb-3">
            <span><span class="inline-block w-3 h-3 rounded-full" style="background:#ef4444"></span> 重複</span>
            <span><span class="inline-block w-3 h-3 rounded-full" style="background:#eab308"></span> 間隔が短い</span>
            <span><span class="inline-block w-3 h-3 rounded-full" style="background:#3b82f6"></span> 通常</span>
        </div>
        <div id="calendar"></div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: '/calendar/events'
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
```

- [ ] **Step 7: レイアウトに `@stack('scripts')` があることを確認/追加**

`resources/views/layouts/app.blade.php` の `</body>` 直前に以下が無ければ追加:
```blade
        @stack('scripts')
    </body>
```

- [ ] **Step 8: 手動確認**

`http://localhost/calendar` を開き、重複する2予定を作ると赤、間隔30分なら黄、離れていれば青で表示されること。予定クリックで詳細へ遷移すること。

- [ ] **Step 9: Commit**

```bash
git add -A
git commit -m "FullCalendarのカレンダー画面と信号機カラーを追加"
```

---

## Task 6: 面接の振り返り(InterviewQuestion)

**Files:**
- Create: `app/Models/InterviewQuestion.php`, `database/migrations/xxxx_create_interview_questions_table.php`, `database/factories/InterviewQuestionFactory.php`, `app/Http/Controllers/InterviewQuestionController.php`
- Modify: `resources/views/events/show.blade.php`, `routes/web.php`, `app/Models/User.php`
- Test: `tests/Feature/InterviewQuestionTest.php`

> `result` は 'good'(◯) / 'ok'(△) / 'bad'(✕) の3値。面接予定(show)から質問を追加・編集・削除する。

- [ ] **Step 1: モデル等を生成**

```bash
./vendor/bin/sail artisan make:model InterviewQuestion -mf
```

- [ ] **Step 2: マイグレーションを定義**

`up()`:
```php
Schema::create('interview_questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('event_id')->constrained()->cascadeOnDelete();
    $table->text('question');
    $table->string('result'); // good / ok / bad
    $table->text('improvement_memo')->nullable();
    $table->timestamps();
});
```

- [ ] **Step 3: モデルを定義**

`app/Models/InterviewQuestion.php`:
```php
class InterviewQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id', 'question', 'result', 'improvement_memo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
```
`app/Models/User.php` に追加:
```php
public function interviewQuestions()
{
    return $this->hasMany(InterviewQuestion::class);
}
```

- [ ] **Step 4: ファクトリを定義**

`database/factories/InterviewQuestionFactory.php` の `definition()`:
```php
return [
    'user_id' => \App\Models\User::factory(),
    'event_id' => \App\Models\Event::factory(),
    'question' => '学生時代に力を入れたことは？',
    'result' => 'bad',
    'improvement_memo' => null,
];
```

- [ ] **Step 5: 失敗するフィーチャテストを書く**

`tests/Feature/InterviewQuestionTest.php`:
```php
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
```

- [ ] **Step 6: テストが失敗することを確認**

```bash
./vendor/bin/sail artisan test --filter=InterviewQuestionTest
```
Expected: FAIL。

- [ ] **Step 7: ルートを追加**

`routes/web.php` の `auth` グループ内:
```php
use App\Http\Controllers\InterviewQuestionController;

Route::post('/events/{event}/questions', [InterviewQuestionController::class, 'store']);
Route::delete('/questions/{question}', [InterviewQuestionController::class, 'destroy']);
```

- [ ] **Step 8: コントローラを実装**

`app/Http/Controllers/InterviewQuestionController.php`:
```php
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
```

- [ ] **Step 9: 予定詳細(show)に振り返りUIを追記**

`resources/views/events/show.blade.php` の `</div>`（外側）直前に追記:
```blade
        <hr class="my-6">
        <h2 class="font-semibold mb-3">面接の振り返り</h2>
        <ul class="space-y-2 mb-6">
            @foreach ($event->interviewQuestions as $q)
                <li class="border rounded p-3">
                    <div class="flex justify-between">
                        <span class="font-medium">{{ $q->question }}</span>
                        <span class="text-sm">{{ ['good'=>'◯うまく','ok'=>'△微妙','bad'=>'✕答えられず'][$q->result] }}</span>
                    </div>
                    @if ($q->improvement_memo)
                        <p class="text-sm text-gray-600 mt-1">次はこう答える: {{ $q->improvement_memo }}</p>
                    @endif
                    <form method="POST" action="/questions/{{ $q->id }}" class="mt-1">
                        @csrf @method('DELETE')
                        <button class="text-red-600 text-xs">削除</button>
                    </form>
                </li>
            @endforeach
        </ul>
        <form method="POST" action="/events/{{ $event->id }}/questions" class="space-y-2">
            @csrf
            <input name="question" class="border rounded w-full p-2" placeholder="実際にされた質問" required>
            <select name="result" class="border rounded w-full p-2">
                <option value="good">◯うまく答えられた</option>
                <option value="ok">△微妙</option>
                <option value="bad" selected>✕答えられなかった</option>
            </select>
            <textarea name="improvement_memo" class="border rounded w-full p-2" placeholder="次はこう答える"></textarea>
            <button class="px-3 py-1 bg-blue-600 text-white rounded">質問を追加</button>
        </form>
```

- [ ] **Step 10: テストが通ることを確認**

```bash
./vendor/bin/sail artisan test --filter=InterviewQuestionTest
```
Expected: 2件PASS。

- [ ] **Step 11: Commit**

```bash
git add -A
git commit -m "面接の振り返り(InterviewQuestion)を追加"
```

---

## Task 7: 苦手質問リスト(横断一覧)

**Files:**
- Create: `app/Http/Controllers/WeakQuestionController.php`, `resources/views/weak_questions/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/WeakQuestionTest.php`

> `result = 'bad'` の質問を全企業横断で、企業名・面接日とともに一覧表示する。

- [ ] **Step 1: 失敗するフィーチャテストを書く**

`tests/Feature/WeakQuestionTest.php`:
```php
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
```

- [ ] **Step 2: テストが失敗することを確認**

```bash
./vendor/bin/sail artisan test --filter=WeakQuestionTest
```
Expected: FAIL。

- [ ] **Step 3: ルートを追加**

`routes/web.php` の `auth` グループ内:
```php
use App\Http\Controllers\WeakQuestionController;

Route::get('/weak-questions', [WeakQuestionController::class, 'index']);
```

- [ ] **Step 4: コントローラを実装**

`app/Http/Controllers/WeakQuestionController.php`:
```php
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
```

- [ ] **Step 5: Bladeを作成**

`resources/views/weak_questions/index.blade.php`:
```blade
<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-xl font-bold mb-4">苦手質問リスト（答えられなかった質問）</h1>
        @if ($questions->isEmpty())
            <p class="text-gray-500">まだありません。面接の振り返りで「✕答えられなかった」を記録すると、ここに集まります。</p>
        @else
            <ul class="space-y-3">
                @foreach ($questions as $q)
                    <li class="border rounded p-3">
                        <p class="font-medium">{{ $q->question }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $q->event->company->name ?? '企業未設定' }} ／ {{ $q->event->start_at }}
                        </p>
                        @if ($q->improvement_memo)
                            <p class="text-sm text-gray-600 mt-1">次はこう答える: {{ $q->improvement_memo }}</p>
                        @endif
                        <a href="/events/{{ $q->event_id }}" class="text-blue-600 text-sm">この面接を見る</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
```

- [ ] **Step 6: ナビゲーションにリンクを追加**

`resources/views/layouts/navigation.blade.php` のナビリンク群に、Breeze標準の `x-nav-link` に倣って追加:
```blade
<x-nav-link :href="url('/calendar')" :active="request()->is('calendar')">カレンダー</x-nav-link>
<x-nav-link :href="url('/companies')" :active="request()->is('companies*')">企業</x-nav-link>
<x-nav-link :href="url('/weak-questions')" :active="request()->is('weak-questions')">苦手質問</x-nav-link>
```

- [ ] **Step 7: テストが通ることを確認**

```bash
./vendor/bin/sail artisan test --filter=WeakQuestionTest
```
Expected: 2件PASS。

- [ ] **Step 8: 全テストを実行**

```bash
./vendor/bin/sail artisan test
```
Expected: 全テストPASS。

- [ ] **Step 9: Commit**

```bash
git add -A
git commit -m "苦手質問リスト(横断一覧)とナビゲーションを追加"
```

---

## Task 8: 提出用の初期化スクリプト(Seeder)とREADME

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`
- Create: `README.md`
- Test: `tests/Feature/SeederTest.php`

> 提出要件: DB初期化スクリプト＋初期データ投入コード＋実行README。Laravelの**マイグレーション(スキーマ)** と **シーダー(初期データ)** が「初期化スクリプト」に該当する。デモ用ユーザと、被り（赤・黄）が一目で分かるサンプル予定を投入する。

- [ ] **Step 1: 失敗するフィーチャテストを書く（シーダーが初期データを作る）**

`tests/Feature/SeederTest.php`:
```php
<?php
namespace Tests\Feature;

use App\Models\Company;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_demo_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['email' => 'demo@example.com']);
        $this->assertTrue(Company::count() >= 1);
        $this->assertTrue(Event::count() >= 2); // 被りデモのため最低2件
    }
}
```

- [ ] **Step 2: テストが失敗することを確認**

```bash
./vendor/bin/sail artisan test --filter=SeederTest
```
Expected: FAIL（デモユーザが存在しない）。

- [ ] **Step 3: DatabaseSeederを実装**

`database/seeders/DatabaseSeeder.php`:
```php
<?php
namespace Database\Seeders;

use App\Models\Company;
use App\Models\Event;
use App\Models\InterviewQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'デモ太郎', 'password' => Hash::make('password')]
        );

        $companyA = Company::create([
            'user_id' => $user->id, 'name' => 'A株式会社', 'status' => '面接',
        ]);
        $companyB = Company::create([
            'user_id' => $user->id, 'name' => 'B株式会社', 'status' => '説明会',
        ]);

        // 赤デモ: 時間が重複する2予定
        Event::create([
            'user_id' => $user->id, 'company_id' => $companyA->id,
            'title' => 'A社 最終面接', 'type' => '面接',
            'start_at' => '2026-06-15 14:00:00', 'end_at' => '2026-06-15 15:00:00',
            'location' => '東京本社',
        ]);
        Event::create([
            'user_id' => $user->id, 'company_id' => $companyB->id,
            'title' => 'B社 説明会', 'type' => '説明会',
            'start_at' => '2026-06-15 14:30:00', 'end_at' => '2026-06-15 15:30:00',
            'location' => 'オンライン',
        ]);
        // 黄デモ: 間隔が30分しかない予定
        $interview = Event::create([
            'user_id' => $user->id, 'company_id' => $companyA->id,
            'title' => 'A社 一次面接', 'type' => '面接',
            'start_at' => '2026-06-16 10:00:00', 'end_at' => '2026-06-16 11:00:00',
            'location' => '東京本社',
        ]);
        Event::create([
            'user_id' => $user->id, 'company_id' => $companyB->id,
            'title' => 'B社 グループ面接', 'type' => '面接',
            'start_at' => '2026-06-16 11:30:00', 'end_at' => '2026-06-16 12:30:00',
            'location' => '新宿オフィス',
        ]);

        // 苦手質問のデモ
        InterviewQuestion::create([
            'user_id' => $user->id, 'event_id' => $interview->id,
            'question' => '当社が第一志望ですか？', 'result' => 'bad',
            'improvement_memo' => '志望度の高さを具体的なエピソードで示す',
        ]);
    }
}
```

- [ ] **Step 4: テストが通ることを確認**

```bash
./vendor/bin/sail artisan test --filter=SeederTest
```
Expected: PASS。

- [ ] **Step 5: 初期化が一発で通ることを手動確認**

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```
Expected: 全テーブル再作成後、デモデータが投入される。`http://localhost/login` に `demo@example.com` / `password` でログインし、カレンダーに赤・黄の予定が見えること。

- [ ] **Step 6: READMEを作成**

`README.md`:
````markdown
# 就活スケジュール管理アプリ

就活の予定の被りを信号機カラー（🔴重複 / 🟡間隔狭）で可視化し、面接で答えられなかった
質問を全企業横断でリスト化するWebアプリ（Laravel + MySQL + Docker）。

## 動作環境

- Docker Desktop（Docker Compose v2）

## セットアップ手順

```bash
# 1. 依存パッケージのインストール（初回のみ・Docker経由）
docker run --rm -v "$(pwd)":/var/www/html -w /var/www/html laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# 2. 環境ファイルを用意
cp .env.example .env

# 3. コンテナ起動（app + mysql）
./vendor/bin/sail up -d

# 4. アプリキー生成
./vendor/bin/sail artisan key:generate

# 5. DB初期化スクリプト実行（テーブル作成＋初期データ投入）
./vendor/bin/sail artisan migrate:fresh --seed

# 6. フロントエンドビルド
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

ブラウザで http://localhost を開く。

## デモログイン

| メールアドレス | パスワード |
|---------------|-----------|
| demo@example.com | password |

## データベース

- DBユーザ: `sail`（root以外の一般ユーザ）
- 初期化スクリプト: `database/migrations/`（スキーマ）＋ `database/seeders/DatabaseSeeder.php`（初期データ）
- 再初期化: `./vendor/bin/sail artisan migrate:fresh --seed`

## 主な機能

- 予定のカレンダー管理（FullCalendar.js）と被り検出（🔴重複 / 🟡間隔60分未満）
- 応募企業の管理
- 面接の振り返り（質問・手応え・改善メモ）
- 答えられなかった質問の横断リスト

## コマンド対応表（sail = docker compose）

| 操作 | コマンド |
|------|---------|
| 起動 | `./vendor/bin/sail up -d`（= `docker compose up -d`）|
| 停止 | `./vendor/bin/sail down` |
| テスト | `./vendor/bin/sail artisan test` |
````

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "提出用の初期化シーダーとREADMEを追加"
```

---

## 提出物チェックリスト（Moodle 期末レポート / 期限 2026-07-27 13:30）

- [ ] プログラム一式（このリポジトリ）
- [ ] README（`README.md` / Task 8）
- [ ] DB初期化スクリプト（`database/migrations/` ＋ `database/seeders/DatabaseSeeder.php` / Task 8）
- [ ] DBユーザがroot以外（`sail` ユーザ / Task 0 Step 5で確認）
- [ ] レポート（A4 2段組 6ページ以上・中間レポートと同じスタイルシート）※コードとは別途。設計書をもとに作成
- [ ] 上記を1つのZIPにまとめて提出

---

## 完了基準（設計書の成功基準との対応）

- マルチユーザーで自分のデータのみ見える → CompanyTest / EventTest / CalendarTest / WeakQuestionTest の分離テストで担保
- 重複(赤)・間隔狭(黄)の色分け → ConflictDetectorTest（単体）＋ CalendarTest（JSON）で担保
- 面接ごとに質問記録・答えられなかった質問の横断リスト → InterviewQuestionTest / WeakQuestionTest で担保
- Docker(DB込み)で起動・動作 → Task 0 で `sail up` ＋ `migrate` ＋ welcome表示を確認

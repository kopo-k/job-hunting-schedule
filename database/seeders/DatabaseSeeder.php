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
    /**
     * Seed the application's database.
     */
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

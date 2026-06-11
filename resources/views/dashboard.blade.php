<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">こんにちは、{{ Auth::user()->name }}さん</h1>
            <p class="text-sm text-gray-500 mb-6">就活の予定と振り返りをまとめて管理しましょう。</p>

            {{-- 重複アラート（具体名つき）と締切アラートは両方とも見逃せないので同時に表示 --}}
            @if ($conflictEvents->isNotEmpty())
                <a href="/calendar" class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-4 mb-3 hover:bg-red-100 transition">
                    <span class="text-red-500 text-xl leading-none">⚠️</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-red-800">予定の重複があります</p>
                        <p class="text-sm text-red-700 mb-1">志望度を見て優先順位を決めましょう。</p>
                        <ul class="text-sm text-red-900 space-y-0.5">
                            @foreach ($conflictEvents->take(4) as $ce)
                                <li>・{{ $ce->start_at->format('n/j H:i') }} {{ $ce->title }}</li>
                            @endforeach
                        </ul>
                    </div>
                </a>
            @endif

            @if ($soonEvents->isNotEmpty())
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <span class="text-amber-500 text-xl leading-none">🔔</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-amber-800">7日以内に {{ $soonEvents->count() }} 件の予定があります</p>
                        <p class="text-sm text-amber-700 mb-1">ES締切や面接の準備に漏れがないか確認しましょう。</p>
                        <ul class="text-sm text-amber-900 space-y-0.5">
                            @foreach ($soonEvents->take(4) as $se)
                                <li class="flex items-center gap-1.5">
                                    @if ($se->type === 'ES締切')<span class="text-xs font-medium px-1.5 rounded bg-rose-100 text-rose-700">締切</span>@endif
                                    {{ $se->start_at->format('n/j H:i') }} {{ $se->title }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <a href="/calendar" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-indigo-300 hover:shadow transition">
                    <p class="text-sm text-gray-500">登録予定</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $eventCount }}<span class="text-base font-normal text-gray-400 ml-1">件</span></p>
                </a>
                <a href="/companies" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-indigo-300 hover:shadow transition">
                    <p class="text-sm text-gray-500">応募企業</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $companyCount }}<span class="text-base font-normal text-gray-400 ml-1">社</span></p>
                </a>
                <a href="/weak-questions" class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-red-500 p-5 hover:shadow transition">
                    <p class="text-sm text-gray-500">苦手質問</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $weakCount }}<span class="text-base font-normal text-gray-400 ml-1">件</span></p>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-900">これからの予定</h2>
                    <a href="/calendar" class="text-indigo-600 text-sm font-medium hover:underline">カレンダーを見る →</a>
                </div>
                @if ($upcoming->isEmpty())
                    <p class="text-sm text-gray-400">予定はまだありません。<a href="/events/create" class="text-indigo-600 hover:underline">予定を追加</a></p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($upcoming as $event)
                            @php $days = (int) ceil(now()->startOfDay()->diffInDays($event->start_at->copy()->startOfDay(), false)); @endphp
                            <a href="/events/{{ $event->id }}" class="flex items-center justify-between py-3 hover:bg-gray-50 -mx-2 px-2 rounded transition">
                                <span class="flex items-center gap-2 text-gray-900 min-w-0">
                                    <x-signal-dot :status="$statuses[$event->id] ?? 'normal'" />
                                    @if ($event->type === 'ES締切')
                                        <span class="shrink-0 text-xs font-medium px-1.5 py-0.5 rounded bg-rose-100 text-rose-700">締切</span>
                                    @endif
                                    <span class="truncate">{{ $event->title }}</span>
                                    @if ($event->company)
                                        <x-priority-badge :priority="$event->company->priority" />
                                    @endif
                                </span>
                                <span class="shrink-0 text-right">
                                    <span class="block text-sm text-gray-700">{{ $event->start_at->format('m/d H:i') }}</span>
                                    <span class="block text-xs {{ $days <= 1 ? 'text-rose-600 font-medium' : 'text-gray-400' }}">
                                        {{ $days <= 0 ? '今日' : ($days === 1 ? '明日' : "あと{$days}日") }}
                                    </span>
                                </span>
                            </a>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

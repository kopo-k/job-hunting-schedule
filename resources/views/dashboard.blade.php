<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">こんにちは、{{ Auth::user()->name }}さん</h1>
            <p class="text-sm text-gray-500 mb-6">就活の予定と振り返りをまとめて管理しましょう。</p>

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
                            <a href="/events/{{ $event->id }}" class="flex items-center justify-between py-3 hover:bg-gray-50 -mx-2 px-2 rounded transition">
                                <span class="flex items-center gap-2 text-gray-900">
                                    <x-signal-dot :status="$statuses[$event->id] ?? 'normal'" />
                                    {{ $event->title }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $event->start_at->format('m/d H:i') }}</span>
                            </a>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

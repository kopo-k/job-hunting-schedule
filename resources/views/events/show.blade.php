<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="/calendar" class="text-sm text-gray-500 hover:text-gray-700">← カレンダーへ戻る</a>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-3">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">{{ $event->type }}</span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $event->title }}</h1>
                    </div>
                    <a href="/events/{{ $event->id }}/edit" class="text-indigo-600 text-sm font-medium hover:underline">編集</a>
                </div>
                <dl class="mt-4 space-y-1 text-sm text-gray-700">
                    <div class="flex gap-2"><dt class="text-gray-400 w-16">日時</dt><dd>{{ $event->start_at->format('Y/m/d H:i') }} 〜 {{ $event->end_at->format('H:i') }}</dd></div>
                    @if ($event->company)
                        <div class="flex gap-2"><dt class="text-gray-400 w-16">企業</dt><dd>{{ $event->company->name }}</dd></div>
                    @endif
                    @if ($event->location)
                        <div class="flex gap-2"><dt class="text-gray-400 w-16">場所</dt><dd>{{ $event->location }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <h2 class="font-semibold text-gray-900 mb-4">面接の振り返り</h2>

                @if ($event->interviewQuestions->isNotEmpty())
                    <ul class="space-y-2 mb-6">
                        @foreach ($event->interviewQuestions as $q)
                            @php
                                $badge = ['good'=>['◯ うまく','bg-green-50 text-green-700'],'ok'=>['△ 微妙','bg-amber-50 text-amber-700'],'bad'=>['✕ 答えられず','bg-red-50 text-red-700']][$q->result];
                            @endphp
                            <li class="border border-gray-100 rounded-lg p-4 bg-gray-50/50">
                                <div class="flex justify-between items-start gap-3">
                                    <span class="font-medium text-gray-900">{{ $q->question }}</span>
                                    <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $badge[1] }}">{{ $badge[0] }}</span>
                                </div>
                                @if ($q->improvement_memo)
                                    <p class="text-sm text-gray-600 mt-2">💡 次はこう答える: {{ $q->improvement_memo }}</p>
                                @endif
                                <form method="POST" action="/questions/{{ $q->id }}" class="mt-2" onsubmit="return confirm('削除しますか？')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-xs hover:underline">削除</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-400 mb-6">まだ質問が記録されていません。</p>
                @endif

                <form method="POST" action="/events/{{ $event->id }}/questions" class="space-y-3 border-t border-gray-100 pt-5">
                    @csrf
                    <input name="question" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="実際にされた質問" required>
                    <select name="result" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="good">◯ うまく答えられた</option>
                        <option value="ok">△ 微妙</option>
                        <option value="bad" selected>✕ 答えられなかった</option>
                    </select>
                    <textarea name="improvement_memo" rows="2" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="次はこう答える（任意）"></textarea>
                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">質問を追加</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

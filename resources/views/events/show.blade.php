<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">{{ $event->title }}</h1>
            <a href="/events/{{ $event->id }}/edit" class="text-blue-600">編集</a>
        </div>
        <p class="text-sm text-gray-600">種別: {{ $event->type }}</p>
        <p class="text-sm text-gray-600">日時: {{ $event->start_at }} 〜 {{ $event->end_at }}</p>
        <p class="text-sm text-gray-600 mb-4">場所: {{ $event->location }}</p>

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
    </div>
</x-app-layout>

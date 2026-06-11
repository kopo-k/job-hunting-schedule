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

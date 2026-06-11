<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">苦手質問リスト</h1>
                <p class="text-sm text-gray-500 mt-1">答えられなかった質問を全企業横断で見直せます（{{ $questions->count() }}件）</p>
            </div>

            @if ($questions->isEmpty())
                <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                    <p class="text-gray-500">まだありません。</p>
                    <p class="text-sm text-gray-400 mt-1">面接の振り返りで「✕答えられなかった」を記録すると、ここに集まります。</p>
                </div>
            @else
                <ul class="space-y-3">
                    @foreach ($questions as $q)
                        <li class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-red-500 p-5">
                            <p class="font-semibold text-gray-900">{{ $q->question }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $q->event->company->name ?? '企業未設定' }} ／ {{ $q->event->start_at->format('Y/m/d') }}
                            </p>
                            @if ($q->improvement_memo)
                                <div class="mt-3 bg-amber-50 border border-amber-100 rounded-lg p-3">
                                    <p class="text-xs font-medium text-amber-800">次はこう答える</p>
                                    <p class="text-sm text-amber-900 mt-0.5">{{ $q->improvement_memo }}</p>
                                </div>
                            @endif
                            <a href="/events/{{ $q->event_id }}" class="inline-block text-indigo-600 text-sm font-medium hover:underline mt-3">この面接を見る →</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>

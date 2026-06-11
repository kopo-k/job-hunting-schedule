<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="/companies" class="text-sm text-gray-500 hover:text-gray-700">← 企業一覧へ戻る</a>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h1>
                        <div class="mt-2"><x-status-badge :status="$company->status" /></div>
                    </div>
                    <a href="/companies/{{ $company->id }}/edit" class="text-indigo-600 text-sm font-medium hover:underline">編集</a>
                </div>
                @if ($company->memo)
                    <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $company->memo }}</p>
                @endif
            </div>

            <h2 class="font-semibold text-gray-900 mt-8 mb-3">関連予定</h2>
            @if ($company->events->isEmpty())
                <p class="text-sm text-gray-500">関連する予定はまだありません。</p>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 divide-y divide-gray-100">
                    @foreach ($company->events as $event)
                        <a href="/events/{{ $event->id }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                            <span class="flex items-center gap-2 text-gray-900">
                                <x-signal-dot :status="$statuses[$event->id] ?? 'normal'" />
                                {{ $event->title }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $event->start_at->format('Y/m/d H:i') }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

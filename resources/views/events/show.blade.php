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

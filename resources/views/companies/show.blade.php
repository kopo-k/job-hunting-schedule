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

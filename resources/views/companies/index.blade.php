<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">応募企業</h1>
            <a href="/companies/create" class="px-3 py-1 bg-blue-600 text-white rounded">新規登録</a>
        </div>
        <ul class="divide-y">
            @foreach ($companies as $company)
                <li class="py-3 flex justify-between">
                    <a href="/companies/{{ $company->id }}" class="font-medium">{{ $company->name }}</a>
                    <span class="text-sm text-gray-500">{{ $company->status }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>

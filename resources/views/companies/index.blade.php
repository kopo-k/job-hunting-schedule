<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">応募企業</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $companies->count() }}社を管理中</p>
                </div>
                <a href="/companies/create"
                   class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    新規登録
                </a>
            </div>

            @if ($companies->isEmpty())
                <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                    <p class="text-gray-500">まだ企業が登録されていません。</p>
                    <a href="/companies/create" class="text-indigo-600 font-medium hover:underline mt-2 inline-block">最初の企業を登録する</a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 divide-y divide-gray-100">
                    @foreach ($companies as $company)
                        <a href="/companies/{{ $company->id }}"
                           class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900">{{ $company->name }}</span>
                            <x-status-badge :status="$company->status" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">企業を登録</h1>
        <form method="POST" action="/companies" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">企業名</label>
                <input name="name" class="border rounded w-full p-2" value="{{ old('name') }}" required>
                @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm">選考状況</label>
                <input name="status" class="border rounded w-full p-2" value="{{ old('status', 'エントリー') }}" required>
            </div>
            <div>
                <label class="block text-sm">メモ</label>
                <textarea name="memo" class="border rounded w-full p-2">{{ old('memo') }}</textarea>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
        </form>
    </div>
</x-app-layout>

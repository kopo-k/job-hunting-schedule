<x-app-layout>
    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="/calendar" class="text-sm text-gray-500 hover:text-gray-700">← カレンダーへ戻る</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">予定を登録</h1>
            </div>

            <form method="POST" action="/events" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">タイトル</label>
                    <input name="title" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('title') }}" required>
                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">種別</label>
                        <select name="type" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach (['面接','説明会','ES締切','その他'] as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">企業（任意）</label>
                        <select name="company_id" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">未選択</option>
                            @foreach ($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">開始</label>
                        <input type="datetime-local" name="start_at" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('start_at') }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">終了</label>
                        <input type="datetime-local" name="end_at" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('end_at') }}" required>
                        @error('end_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">場所</label>
                    <input name="location" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('location') }}" placeholder="オンライン / 東京本社 など">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <a href="/calendar" class="px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-800">キャンセル</a>
                    <button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">保存</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

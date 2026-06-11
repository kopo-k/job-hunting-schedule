<x-app-layout>
    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="/events/{{ $event->id }}" class="text-sm text-gray-500 hover:text-gray-700">← 戻る</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">予定を編集</h1>
            </div>

            <form method="POST" action="/events/{{ $event->id }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">タイトル</label>
                    <input name="title" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('title', $event->title) }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">種別</label>
                        <select name="type" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach (['面接','説明会','ES締切','その他'] as $t)
                                <option value="{{ $t }}" @selected($event->type === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">企業（任意）</label>
                        <select name="company_id" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">未選択</option>
                            @foreach ($companies as $c)
                                <option value="{{ $c->id }}" @selected($event->company_id === $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">開始</label>
                        <input type="datetime-local" name="start_at" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('start_at', $event->start_at->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">終了</label>
                        <input type="datetime-local" name="end_at" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('end_at', $event->end_at->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">場所</label>
                    <input name="location" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('location', $event->location) }}">
                </div>
                <div class="flex justify-between items-center pt-2">
                    <button form="delete-event" class="text-red-600 text-sm font-medium hover:underline">削除</button>
                    <div class="flex gap-3">
                        <a href="/events/{{ $event->id }}" class="px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-800">キャンセル</a>
                        <button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">更新</button>
                    </div>
                </div>
            </form>
            <form id="delete-event" method="POST" action="/events/{{ $event->id }}" onsubmit="return confirm('この予定を削除しますか？')">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-app-layout>

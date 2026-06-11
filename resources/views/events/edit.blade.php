<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">予定を編集</h1>
        <form method="POST" action="/events/{{ $event->id }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm">タイトル</label>
                <input name="title" class="border rounded w-full p-2" value="{{ old('title', $event->title) }}" required>
            </div>
            <div>
                <label class="block text-sm">種別</label>
                <select name="type" class="border rounded w-full p-2">
                    @foreach (['面接','説明会','ES締切','その他'] as $t)
                        <option value="{{ $t }}" @selected($event->type === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">企業（任意）</label>
                <select name="company_id" class="border rounded w-full p-2">
                    <option value="">未選択</option>
                    @foreach ($companies as $c)
                        <option value="{{ $c->id }}" @selected($event->company_id === $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">開始</label>
                <input type="datetime-local" name="start_at" class="border rounded w-full p-2" value="{{ old('start_at', $event->start_at->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div>
                <label class="block text-sm">終了</label>
                <input type="datetime-local" name="end_at" class="border rounded w-full p-2" value="{{ old('end_at', $event->end_at->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div>
                <label class="block text-sm">場所</label>
                <input name="location" class="border rounded w-full p-2" value="{{ old('location', $event->location) }}">
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">更新</button>
            </div>
        </form>
        <form method="POST" action="/events/{{ $event->id }}" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="text-red-600 text-sm">この予定を削除</button>
        </form>
    </div>
</x-app-layout>

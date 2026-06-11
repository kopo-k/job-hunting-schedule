<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">予定を登録</h1>
        <form method="POST" action="/events" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">タイトル</label>
                <input name="title" class="border rounded w-full p-2" value="{{ old('title') }}" required>
                @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm">種別</label>
                <select name="type" class="border rounded w-full p-2">
                    @foreach (['面接','説明会','ES締切','その他'] as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">企業（任意）</label>
                <select name="company_id" class="border rounded w-full p-2">
                    <option value="">未選択</option>
                    @foreach ($companies as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">開始</label>
                <input type="datetime-local" name="start_at" class="border rounded w-full p-2" value="{{ old('start_at') }}" required>
            </div>
            <div>
                <label class="block text-sm">終了</label>
                <input type="datetime-local" name="end_at" class="border rounded w-full p-2" value="{{ old('end_at') }}" required>
                @error('end_at')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm">場所</label>
                <input name="location" class="border rounded w-full p-2" value="{{ old('location') }}">
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="/companies/{{ $company->id }}" class="text-sm text-gray-500 hover:text-gray-700">← 戻る</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">企業を編集</h1>
            </div>

            <form method="POST" action="/companies/{{ $company->id }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">企業名</label>
                    <input name="name" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('name', $company->name) }}" required>
                    @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">選考状況</label>
                        <select name="status" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach (['エントリー','ES提出','書類選考','一次面接','二次面接','最終面接','内定','お祈り'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $company->status) === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">志望度</label>
                        <select name="priority" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="3" @selected(old('priority', $company->priority) == 3)>★★★ 第一志望</option>
                            <option value="2" @selected(old('priority', $company->priority) == 2)>★★ 志望</option>
                            <option value="1" @selected(old('priority', $company->priority) == 1)>★ 興味あり</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">メモ</label>
                    <textarea name="memo" rows="3" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">{{ old('memo', $company->memo) }}</textarea>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <button form="delete-company" class="text-red-600 text-sm font-medium hover:underline">削除</button>
                    <div class="flex gap-3">
                        <a href="/companies/{{ $company->id }}" class="px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-800">キャンセル</a>
                        <button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">更新</button>
                    </div>
                </div>
            </form>
            <form id="delete-company" method="POST" action="/companies/{{ $company->id }}" onsubmit="return confirm('この企業を削除しますか？')">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-app-layout>

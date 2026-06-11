@props(['status' => ''])
@php
    $map = [
        'エントリー' => 'bg-gray-100 text-gray-600',
        'ES提出' => 'bg-amber-100 text-amber-700',
        '書類選考' => 'bg-sky-100 text-sky-700',
        '一次面接' => 'bg-blue-100 text-blue-700',
        '二次面接' => 'bg-indigo-100 text-indigo-700',
        '最終面接' => 'bg-violet-100 text-violet-700',
        '内定' => 'bg-green-100 text-green-700',
        'お祈り' => 'bg-red-100 text-red-700',
        // 旧データ互換
        '説明会' => 'bg-sky-50 text-sky-700',
        '面接' => 'bg-indigo-50 text-indigo-700',
    ];
    $class = $map[$status] ?? 'bg-gray-100 text-gray-700';
@endphp
<span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $class }}">{{ $status }}</span>

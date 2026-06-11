@props(['status' => ''])
@php
    $map = [
        'エントリー' => 'bg-gray-100 text-gray-700',
        '書類選考' => 'bg-blue-50 text-blue-700',
        '説明会' => 'bg-sky-50 text-sky-700',
        '面接' => 'bg-indigo-50 text-indigo-700',
        '内定' => 'bg-green-50 text-green-700',
        'お祈り' => 'bg-red-50 text-red-700',
    ];
    $class = $map[$status] ?? 'bg-gray-100 text-gray-700';
@endphp
<span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $class }}">{{ $status }}</span>

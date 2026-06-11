@props(['priority' => 2])
@php
    $map = [
        3 => ['第一志望', '★★★', 'text-rose-600'],
        2 => ['志望', '★★', 'text-amber-500'],
        1 => ['興味あり', '★', 'text-gray-400'],
    ];
    [$label, $stars, $color] = $map[(int) $priority] ?? $map[2];
@endphp
<span class="inline-flex items-center gap-1 text-xs font-medium {{ $color }}" title="志望度: {{ $label }}">
    <span>{{ $stars }}</span>
    <span class="text-gray-500">{{ $label }}</span>
</span>

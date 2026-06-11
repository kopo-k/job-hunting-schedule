@props(['status' => 'normal'])
@php
    $map = [
        'red' => ['#ef4444', '重複あり'],
        'yellow' => ['#eab308', '間隔が短い'],
        'normal' => ['#3b82f6', '通常'],
    ];
    [$color, $label] = $map[$status] ?? $map['normal'];
@endphp
<span class="inline-block w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $color }}" title="{{ $label }}"></span>

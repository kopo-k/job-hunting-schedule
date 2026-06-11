<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $map = [
        'エントリー' => 'bg-gray-100 text-gray-700',
        'ES提出' => 'bg-blue-50 text-blue-700',
        '書類選考' => 'bg-blue-50 text-blue-700',
        '一次面接' => 'bg-indigo-50 text-indigo-700',
        '二次面接' => 'bg-indigo-50 text-indigo-700',
        '最終面接' => 'bg-violet-50 text-violet-700',
        '内定' => 'bg-green-50 text-green-700',
        'お祈り' => 'bg-red-50 text-red-700',
        // 旧データ互換
        '説明会' => 'bg-sky-50 text-sky-700',
        '面接' => 'bg-indigo-50 text-indigo-700',
    ];
    $class = $map[$status] ?? 'bg-gray-100 text-gray-700';
?>
<span class="text-xs font-medium px-2.5 py-1 rounded-full <?php echo e($class); ?>"><?php echo e($status); ?></span>
<?php /**PATH /var/www/html/resources/views/components/status-badge.blade.php ENDPATH**/ ?>
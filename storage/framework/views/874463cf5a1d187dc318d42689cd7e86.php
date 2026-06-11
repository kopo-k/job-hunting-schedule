<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['priority' => 2]));

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

foreach (array_filter((['priority' => 2]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $map = [
        3 => ['第一志望', '★★★', 'text-rose-600'],
        2 => ['志望', '★★', 'text-amber-500'],
        1 => ['興味あり', '★', 'text-gray-400'],
    ];
    [$label, $stars, $color] = $map[(int) $priority] ?? $map[2];
?>
<span class="inline-flex items-center gap-1 text-xs font-medium <?php echo e($color); ?>" title="志望度: <?php echo e($label); ?>">
    <span><?php echo e($stars); ?></span>
    <span class="text-gray-500"><?php echo e($label); ?></span>
</span>
<?php /**PATH /var/www/html/resources/views/components/priority-badge.blade.php ENDPATH**/ ?>
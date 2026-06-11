<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status' => 'normal']));

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

foreach (array_filter((['status' => 'normal']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $map = [
        'red' => ['#ef4444', '重複あり'],
        'yellow' => ['#eab308', '間隔が短い'],
        'normal' => ['#3b82f6', '通常'],
    ];
    [$color, $label] = $map[$status] ?? $map['normal'];
?>
<span class="inline-block w-2.5 h-2.5 rounded-full shrink-0" style="background: <?php echo e($color); ?>" title="<?php echo e($label); ?>"></span>
<?php /**PATH /var/www/html/resources/views/components/signal-dot.blade.php ENDPATH**/ ?>
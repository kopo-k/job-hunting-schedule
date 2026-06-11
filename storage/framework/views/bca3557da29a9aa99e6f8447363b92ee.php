<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-xl font-bold mb-4">苦手質問リスト（答えられなかった質問）</h1>
        <?php if($questions->isEmpty()): ?>
            <p class="text-gray-500">まだありません。面接の振り返りで「✕答えられなかった」を記録すると、ここに集まります。</p>
        <?php else: ?>
            <ul class="space-y-3">
                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="border rounded p-3">
                        <p class="font-medium"><?php echo e($q->question); ?></p>
                        <p class="text-sm text-gray-500">
                            <?php echo e($q->event->company->name ?? '企業未設定'); ?> ／ <?php echo e($q->event->start_at); ?>

                        </p>
                        <?php if($q->improvement_memo): ?>
                            <p class="text-sm text-gray-600 mt-1">次はこう答える: <?php echo e($q->improvement_memo); ?></p>
                        <?php endif; ?>
                        <a href="/events/<?php echo e($q->event_id); ?>" class="text-blue-600 text-sm">この面接を見る</a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/weak_questions/index.blade.php ENDPATH**/ ?>
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
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">苦手質問リスト</h1>
                <p class="text-sm text-gray-500 mt-1">答えられなかった質問を全企業横断で見直せます（<?php echo e($questions->count()); ?>件）</p>
            </div>

            <?php if($questions->isEmpty()): ?>
                <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                    <p class="text-gray-500">まだありません。</p>
                    <p class="text-sm text-gray-400 mt-1">面接の振り返りで「✕答えられなかった」を記録すると、ここに集まります。</p>
                </div>
            <?php else: ?>
                <ul class="space-y-3">
                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-red-500 p-5">
                            <p class="font-semibold text-gray-900"><?php echo e($q->question); ?></p>
                            <p class="text-xs text-gray-500 mt-1">
                                <?php echo e($q->event->company->name ?? '企業未設定'); ?> ／ <?php echo e($q->event->start_at->format('Y/m/d')); ?>

                            </p>
                            <?php if($q->improvement_memo): ?>
                                <div class="mt-3 bg-amber-50 border border-amber-100 rounded-lg p-3">
                                    <p class="text-xs font-medium text-amber-800">次はこう答える</p>
                                    <p class="text-sm text-amber-900 mt-0.5"><?php echo e($q->improvement_memo); ?></p>
                                </div>
                            <?php endif; ?>
                            <a href="/events/<?php echo e($q->event_id); ?>" class="inline-block text-indigo-600 text-sm font-medium hover:underline mt-3">この面接を見る →</a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
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
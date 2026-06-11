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
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="/companies" class="text-sm text-gray-500 hover:text-gray-700">← 企業一覧へ戻る</a>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo e($company->name); ?></h1>
                        <span class="inline-block mt-2 text-xs font-medium px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700"><?php echo e($company->status); ?></span>
                    </div>
                    <a href="/companies/<?php echo e($company->id); ?>/edit" class="text-indigo-600 text-sm font-medium hover:underline">編集</a>
                </div>
                <?php if($company->memo): ?>
                    <p class="mt-4 text-gray-700 whitespace-pre-line"><?php echo e($company->memo); ?></p>
                <?php endif; ?>
            </div>

            <h2 class="font-semibold text-gray-900 mt-8 mb-3">関連予定</h2>
            <?php if($company->events->isEmpty()): ?>
                <p class="text-sm text-gray-500">関連する予定はまだありません。</p>
            <?php else: ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 divide-y divide-gray-100">
                    <?php $__currentLoopData = $company->events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="/events/<?php echo e($event->id); ?>" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                            <span class="text-gray-900"><?php echo e($event->title); ?></span>
                            <span class="text-sm text-gray-500"><?php echo e($event->start_at->format('Y/m/d H:i')); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
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
<?php /**PATH /var/www/html/resources/views/companies/show.blade.php ENDPATH**/ ?>
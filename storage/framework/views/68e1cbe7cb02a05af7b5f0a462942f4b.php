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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">こんにちは、<?php echo e(Auth::user()->name); ?>さん</h1>
            <p class="text-sm text-gray-500 mb-6">就活の予定と振り返りをまとめて管理しましょう。</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <a href="/calendar" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-indigo-300 hover:shadow transition">
                    <p class="text-sm text-gray-500">登録予定</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($eventCount); ?><span class="text-base font-normal text-gray-400 ml-1">件</span></p>
                </a>
                <a href="/companies" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-indigo-300 hover:shadow transition">
                    <p class="text-sm text-gray-500">応募企業</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($companyCount); ?><span class="text-base font-normal text-gray-400 ml-1">社</span></p>
                </a>
                <a href="/weak-questions" class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-red-500 p-5 hover:shadow transition">
                    <p class="text-sm text-gray-500">苦手質問</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($weakCount); ?><span class="text-base font-normal text-gray-400 ml-1">件</span></p>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-900">これからの予定</h2>
                    <a href="/calendar" class="text-indigo-600 text-sm font-medium hover:underline">カレンダーを見る →</a>
                </div>
                <?php if($upcoming->isEmpty()): ?>
                    <p class="text-sm text-gray-400">予定はまだありません。<a href="/events/create" class="text-indigo-600 hover:underline">予定を追加</a></p>
                <?php else: ?>
                    <ul class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="/events/<?php echo e($event->id); ?>" class="flex items-center justify-between py-3 hover:bg-gray-50 -mx-2 px-2 rounded transition">
                                <span class="text-gray-900"><?php echo e($event->title); ?></span>
                                <span class="text-sm text-gray-500"><?php echo e($event->start_at->format('m/d H:i')); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
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
<?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>
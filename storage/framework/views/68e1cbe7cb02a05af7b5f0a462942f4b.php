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

            
            <?php if($conflictEvents->isNotEmpty()): ?>
                <a href="/calendar" class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-4 mb-3 hover:bg-red-100 transition">
                    <span class="text-red-500 text-xl leading-none">⚠️</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-red-800">予定の重複があります</p>
                        <p class="text-sm text-red-700 mb-1">志望度を見て優先順位を決めましょう。</p>
                        <ul class="text-sm text-red-900 space-y-0.5">
                            <?php $__currentLoopData = $conflictEvents->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>・<?php echo e($ce->start_at->format('n/j H:i')); ?> <?php echo e($ce->title); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </a>
            <?php endif; ?>

            <?php if($soonEvents->isNotEmpty()): ?>
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <span class="text-amber-500 text-xl leading-none">🔔</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-amber-800">7日以内に <?php echo e($soonEvents->count()); ?> 件の予定があります</p>
                        <p class="text-sm text-amber-700 mb-1">ES締切や面接の準備に漏れがないか確認しましょう。</p>
                        <ul class="text-sm text-amber-900 space-y-0.5">
                            <?php $__currentLoopData = $soonEvents->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $se): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex items-center gap-1.5">
                                    <?php if($se->type === 'ES締切'): ?><span class="text-xs font-medium px-1.5 rounded bg-rose-100 text-rose-700">締切</span><?php endif; ?>
                                    <?php echo e($se->start_at->format('n/j H:i')); ?> <?php echo e($se->title); ?>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

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
                            <?php $days = (int) ceil(now()->startOfDay()->diffInDays($event->start_at->copy()->startOfDay(), false)); ?>
                            <a href="/events/<?php echo e($event->id); ?>" class="flex items-center justify-between py-3 hover:bg-gray-50 -mx-2 px-2 rounded transition">
                                <span class="flex items-center gap-2 text-gray-900 min-w-0">
                                    <?php if (isset($component)) { $__componentOriginal659168505750d541ebb18a921ec54e0d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal659168505750d541ebb18a921ec54e0d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.signal-dot','data' => ['status' => $statuses[$event->id] ?? 'normal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('signal-dot'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statuses[$event->id] ?? 'normal')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal659168505750d541ebb18a921ec54e0d)): ?>
<?php $attributes = $__attributesOriginal659168505750d541ebb18a921ec54e0d; ?>
<?php unset($__attributesOriginal659168505750d541ebb18a921ec54e0d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal659168505750d541ebb18a921ec54e0d)): ?>
<?php $component = $__componentOriginal659168505750d541ebb18a921ec54e0d; ?>
<?php unset($__componentOriginal659168505750d541ebb18a921ec54e0d); ?>
<?php endif; ?>
                                    <?php if($event->type === 'ES締切'): ?>
                                        <span class="shrink-0 text-xs font-medium px-1.5 py-0.5 rounded bg-rose-100 text-rose-700">締切</span>
                                    <?php endif; ?>
                                    <span class="truncate"><?php echo e($event->title); ?></span>
                                    <?php if($event->company): ?>
                                        <?php if (isset($component)) { $__componentOriginalb3f3930a96171a12366c9551b2dd3c07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3f3930a96171a12366c9551b2dd3c07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.priority-badge','data' => ['priority' => $event->company->priority]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('priority-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['priority' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event->company->priority)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb3f3930a96171a12366c9551b2dd3c07)): ?>
<?php $attributes = $__attributesOriginalb3f3930a96171a12366c9551b2dd3c07; ?>
<?php unset($__attributesOriginalb3f3930a96171a12366c9551b2dd3c07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb3f3930a96171a12366c9551b2dd3c07)): ?>
<?php $component = $__componentOriginalb3f3930a96171a12366c9551b2dd3c07; ?>
<?php unset($__componentOriginalb3f3930a96171a12366c9551b2dd3c07); ?>
<?php endif; ?>
                                    <?php endif; ?>
                                </span>
                                <span class="shrink-0 text-right">
                                    <span class="block text-sm text-gray-700"><?php echo e($event->start_at->format('m/d H:i')); ?></span>
                                    <span class="block text-xs <?php echo e($days <= 1 ? 'text-rose-600 font-medium' : 'text-gray-400'); ?>">
                                        <?php echo e($days <= 0 ? '今日' : ($days === 1 ? '明日' : "あと{$days}日")); ?>

                                    </span>
                                </span>
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
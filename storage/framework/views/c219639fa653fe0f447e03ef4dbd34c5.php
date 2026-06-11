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
            <a href="/calendar" class="text-sm text-gray-500 hover:text-gray-700">← カレンダーへ戻る</a>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-3">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600"><?php echo e($event->type); ?></span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2"><?php echo e($event->title); ?></h1>
                    </div>
                    <a href="/events/<?php echo e($event->id); ?>/edit" class="text-indigo-600 text-sm font-medium hover:underline">編集</a>
                </div>
                <dl class="mt-4 space-y-1 text-sm text-gray-700">
                    <div class="flex gap-2"><dt class="text-gray-400 w-16">日時</dt><dd><?php echo e($event->start_at->format('Y/m/d H:i')); ?> 〜 <?php echo e($event->end_at->format('H:i')); ?></dd></div>
                    <?php if($event->company): ?>
                        <div class="flex gap-2"><dt class="text-gray-400 w-16">企業</dt><dd><?php echo e($event->company->name); ?></dd></div>
                    <?php endif; ?>
                    <?php if($event->location): ?>
                        <div class="flex gap-2"><dt class="text-gray-400 w-16">場所</dt><dd><?php echo e($event->location); ?></dd></div>
                    <?php endif; ?>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <h2 class="font-semibold text-gray-900 mb-4">面接の振り返り</h2>

                <?php if($event->interviewQuestions->isNotEmpty()): ?>
                    <ul class="space-y-2 mb-6">
                        <?php $__currentLoopData = $event->interviewQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $badge = ['good'=>['◯ うまく','bg-green-50 text-green-700'],'ok'=>['△ 微妙','bg-amber-50 text-amber-700'],'bad'=>['✕ 答えられず','bg-red-50 text-red-700']][$q->result];
                            ?>
                            <li class="border border-gray-100 rounded-lg p-4 bg-gray-50/50">
                                <div class="flex justify-between items-start gap-3">
                                    <span class="font-medium text-gray-900"><?php echo e($q->question); ?></span>
                                    <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full <?php echo e($badge[1]); ?>"><?php echo e($badge[0]); ?></span>
                                </div>
                                <?php if($q->improvement_memo): ?>
                                    <p class="text-sm text-gray-600 mt-2">💡 次はこう答える: <?php echo e($q->improvement_memo); ?></p>
                                <?php endif; ?>
                                <form method="POST" action="/questions/<?php echo e($q->id); ?>" class="mt-2" onsubmit="return confirm('削除しますか？')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="text-red-500 text-xs hover:underline">削除</button>
                                </form>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-sm text-gray-400 mb-6">まだ質問が記録されていません。</p>
                <?php endif; ?>

                <form method="POST" action="/events/<?php echo e($event->id); ?>/questions" class="space-y-3 border-t border-gray-100 pt-5">
                    <?php echo csrf_field(); ?>
                    <input name="question" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="実際にされた質問" required>
                    <select name="result" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="good">◯ うまく答えられた</option>
                        <option value="ok">△ 微妙</option>
                        <option value="bad" selected>✕ 答えられなかった</option>
                    </select>
                    <textarea name="improvement_memo" rows="2" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="次はこう答える（任意）"></textarea>
                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">質問を追加</button>
                    </div>
                </form>
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
<?php /**PATH /var/www/html/resources/views/events/show.blade.php ENDPATH**/ ?>
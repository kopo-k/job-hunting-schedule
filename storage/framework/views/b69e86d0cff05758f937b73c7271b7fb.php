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
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="/companies" class="text-sm text-gray-500 hover:text-gray-700">← 企業一覧へ戻る</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">企業を登録</h1>
            </div>

            <form method="POST" action="/companies" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">企業名</label>
                    <input name="name" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo e(old('name')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">選考状況</label>
                        <select name="status" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <?php $__currentLoopData = ['エントリー','ES提出','書類選考','一次面接','二次面接','最終面接','内定','お祈り']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s); ?>" <?php if(old('status') === $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">志望度</label>
                        <select name="priority" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="3" <?php if(old('priority') == 3): echo 'selected'; endif; ?>>★★★ 第一志望</option>
                            <option value="2" <?php if(old('priority', 2) == 2): echo 'selected'; endif; ?>>★★ 志望</option>
                            <option value="1" <?php if(old('priority') == 1): echo 'selected'; endif; ?>>★ 興味あり</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">メモ</label>
                    <textarea name="memo" rows="3" class="border border-gray-300 rounded-lg w-full p-2.5 focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('memo')); ?></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <a href="/companies" class="px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-800">キャンセル</a>
                    <button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">保存</button>
                </div>
            </form>
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
<?php /**PATH /var/www/html/resources/views/companies/create.blade.php ENDPATH**/ ?>
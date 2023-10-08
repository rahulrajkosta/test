<div class="flash-message">
    <?php $__currentLoopData = ['danger', 'warning', 'success', 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(session()->has('alert-' . $msg)): ?>
            <div class="alert alert-<?php echo e($msg); ?>">
                <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo e(session('alert-' . $msg)); ?>

            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if(session()->has('status')): ?>
        <div class="alert alert-info">
            <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>
</div><?php /**PATH /var/www/html/makesecurepro/resources/views/snippets/flash.blade.php ENDPATH**/ ?>
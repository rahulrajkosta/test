<?php if(count($errors) > 0): ?>
    <div class="alert alert-danger">
        <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>

        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>


<?php /**PATH /var/www/html/makesecure/resources/views/snippets/errors.blade.php ENDPATH**/ ?>
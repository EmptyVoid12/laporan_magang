<div class="flex">
    <?php if (isset($component)) { $__componentOriginal32b9f4abfc80490155cb7c5dfaf8790d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32b9f4abfc80490155cb7c5dfaf8790d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.tenant-menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::tenant-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32b9f4abfc80490155cb7c5dfaf8790d)): ?>
<?php $attributes = $__attributesOriginal32b9f4abfc80490155cb7c5dfaf8790d; ?>
<?php unset($__attributesOriginal32b9f4abfc80490155cb7c5dfaf8790d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32b9f4abfc80490155cb7c5dfaf8790d)): ?>
<?php $component = $__componentOriginal32b9f4abfc80490155cb7c5dfaf8790d; ?>
<?php unset($__componentOriginal32b9f4abfc80490155cb7c5dfaf8790d); ?>
<?php endif; ?>
</div>
<?php /**PATH /var/www/html/vendor/hasnayeen/themes/resources/views/filament/hooks/tenant-menu.blade.php ENDPATH**/ ?>
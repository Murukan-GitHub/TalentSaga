<?php $__env->startSection('content'); ?>
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">FAQ</h2>

            <dl class="faq js-accordion">
                 <?php foreach($faqs as $faq): ?>
                    <dt class="js-accordion-title"><?php echo e($faq->question__trans); ?></dt>
                    <dd class="js-accordion-content">
                        <?php echo htmlspecialchars_decode($faq->answer__trans); ?>

                    </dd>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
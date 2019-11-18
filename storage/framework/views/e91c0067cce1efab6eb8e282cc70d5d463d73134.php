<?php $__env->startSection('content'); ?>
    <div class="site-main-inner">
        <section class="section section--white">
            <div class="container">
                <h2 class="fancy-heading"><?php echo e($content->title__trans); ?></h2>

            <?php if($content->image): ?>
                <div style="width: 100%;">
                    <img style="width: 100%;" src="<?php echo e(($content->image ? $content->image_large_cover : asset('frontend/assets/img/success-story-img-thumb.jpg'))); ?>" alt="<?php echo e($content->title__trans); ?>">
                </div>
                <br>
            <?php endif; ?>

                <div class="">
                    <small><span class="fa fa-lg fa-fw fa-clock-o"></span> <i><?php echo e($content->created_at->format('F d, Y')); ?> by Admin Talentsaga</i></small>
                    <br><br>
                    <?php echo htmlspecialchars_decode($content->content__trans); ?>

                </div>
            </div>
        </section>

    <?php if(isset($contentMedia) && is_array($contentMedia) && isset($contentMedia['title']) && isset($contentMedia['media']) && is_array($contentMedia['media']) && !empty($contentMedia['media'])): ?>
        <section class="section">
            <div class="container">
                <h2 class="fancy-heading"><?php echo e($contentMedia['title']); ?></h2>

                <ul class="team-list list-nostyle">
                    <?php foreach($contentMedia['media'] as $media): ?>
                    <li class="team-list-item">
                        <figure class="team">
                            <img src="<?php echo e($media['thumbnail_image_url']); ?>" alt="">
                            <figcaption>
                                <h3><?php echo e($media['title']); ?></h3>
                                <small><?php echo e($media['subtitle']); ?></small>
                            </figcaption>
                        </figure>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
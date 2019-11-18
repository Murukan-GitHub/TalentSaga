<?php $__env->startSection('content'); ?>

<div class="site-main-inner-padded">
    <div class="container">
        <h2 class="fancy-heading"><?php echo e(trans('label.home.blog')); ?></h2>

        <?php if($contents && count($contents) > 0): ?>
            <ul class="success-stories-list list-nostyle">
            <?php foreach($contents as $content): ?>
                <li class="success-stories-list-item">
                    <article class="success-story-news">
                        <div style="max-height: 160px; overflow-y: hidden; margin: 0px; padding: 0px;">
                            <img src="<?php echo e(($content->image ? $content->image_small_banner : asset('frontend/assets/img/success-story-img-thumb.jpg'))); ?>" alt="<?php echo e($content->title); ?>">
                        </div>

                        <div class="success-story-news-desc">
                            <h3 class="success-story-news-title"><?php echo e($content->title); ?><br>
                                <span style="font-size: 9pt; font-style: italic; font-weight: normal;"><?php echo e($content->created_at->format('F d, Y')); ?></span></h3>

                            <a href="<?php echo e(route('frontend.home.content.'.($routeNode), ['slug' => $content->slug])); ?>">Read more</a>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
            </ul>
            <?php echo $__env->make('frontend.partials.pagination', ['paginator' => $contents], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php else: ?>
            <center>
                <br><br>
                <i>( no content available )</i>
                <br><br>
            </center>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
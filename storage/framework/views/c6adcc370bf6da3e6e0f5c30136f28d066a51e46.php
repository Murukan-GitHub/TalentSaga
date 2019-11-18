<?php $__env->startSection('content'); ?>
<section class="hero" style="background-image: url(<?php echo e(asset('frontend/assets/img/landing-page-bg.jpg')); ?>);">
    <div class="hero-content">
        <h2 class="hero-heading">
            <span class="hero-heading-fit-text"><?php echo e(trans('label.home.heading_1')); ?></span>
            <span class="hero-heading-fit-text"><?php echo e(trans('label.home.heading_2')); ?></span>
            <span class="hero-heading-fit-text"><?php echo e(trans('label.home.heading_3')); ?></span>
        </h2>

        <form class="hero-search-form" action="<?php echo e(route('talent.list.raw')); ?>" method="get">
            <div class="hero-search-form-section hero-search-form-section--pushed">
                <label for="heroSelectCategory"><?php echo e(trans('label.home.categories')); ?></label>
                <select class="hero-input" id="heroSelectCategory" name="category_id" data-enhance-select>
                    <?php foreach(getTalentCategories() as $cat): ?>
                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name__trans); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="hero-search-form-section hero-search-form-section--pushed">
                <label for="heroSelectLocation"><?php echo e(trans('label.home.location')); ?></label>
                <select class="hero-input" id="heroSelectLocation" name="city_id" data-enhance-select>
                <?php foreach(getCities() as $city): ?>
                    <option value="<?php echo e($city->id); ?>"><?php echo e($city); ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="hero-search-form-section">
                <button type="submit" class="btn btn--tosca"><?php echo e(trans('label.home.findtalent')); ?></button>
            </div>
        </form>
    </div>
</section>

<section class="home-section">
    <div class="container">
        <h2 class="home-section-heading"><?php echo e(trans('label.home.favoritetalent')); ?></h2>

        <div class="talent-list">
        <?php foreach($favoriteTalent as $talentProfile): ?>
        <?php if($talentProfile->user): ?>
            <div class="talent-list-item">
                <a class="talent-anchor" href="<?php echo e(route('frontend.user.profile.shortcut', ['user' => $talentProfile->user])); ?>">
                    <figure class="talent">
                        <img class="talent-img" src="<?php echo e($talentProfile->user->picture ? $talentProfile->user->picture_large_square : ($talentProfile->user->firstImageGallery() ? $talentProfile->user->firstImageGallery()->image_media_url_large_square : asset('frontend/assets/img/thumb-talent-1.jpg') )); ?>" alt="">
                        <figcaption class="talent-desc">
                            <span class="talent-label"><?php echo e($talentProfile->talent_profession ? $talentProfile->talent_profession : 'Artist'); ?></span>
                            <h3 class="talent-name"><?php echo e($talentProfile->user->full_name); ?>, <?php echo e($talentProfile->user->age); ?></h3>
                            <span class="talent-location">
                                <span class="fa fa-map-marker"></span>
                                <span><?php echo e($talentProfile->city ? $talentProfile->city->name : 'Unknown City'); ?></span>
                            </span>
                        </figcaption>
                    </figure>
                </a>
            </div>
        <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if($userStoryExist): ?>
    <section class="success-story-section">
        <h2 class="success-story-section-heading"><?php echo e(trans('label.home.successstory')); ?></h2>

        <template id="templateSuccessStorySlider">
            {{#each success_stories}}
            <div>
                <div class="success-story-slider-item" style="background-image: url({{imageBackground}})">
                    <blockquote class="success-story">
                        <p class="success-story-desc">{{ longDesc }}</p>
                        <cite class="success-story-cite">{{ name }}</cite>
                    </blockquote>
                </div>
            </div>
            {{/each}}
        </template>
        <div class="success-story-slider default-slider-style" data-content="<?php echo e(route('user.story.json')); ?>"></div>

        <template id="successStoryPeek">
            <div class="success-story-peek">
                <div class="success-story-peek-img" style="background-image: url({{ imageThumb }});"></div>
                <div class="success-story-peek-desc">
                    <div><b>{{ name }}</b></div>
                    <small>{{ shortDesc }}</small>
                </div>
            </div>
        </template>
        <button class="success-story-nav success-story-nav--prev"></button>
        <button class="success-story-nav success-story-nav--next"></button>
        <div class="text-center">
            <a class="btn btn--tosca success-story-section-more-btn" href="<?php echo e(route('user.story.list')); ?>"><?php echo e(trans('label.home.more')); ?></a>
        </div>
    </section>
<?php endif; ?>

<section class="home-section home-section--white">
    <div class="container">
        <h2 class="home-section-heading"><?php echo e(trans('label.home.findyourperfecttalent')); ?></h2>

        <div class="find-talent-info-slider default-slider-style">
            <div>
                <figure class="find-talent-info-slider-item">
                    <figcaption><?php echo e(trans('label.home.findyourperfecttalent_1')); ?></figcaption>
                    <img src="<?php echo e(asset('frontend/assets/img/section-search-talent-img-1.png')); ?>" alt="<?php echo e(trans('label.home.findyourperfecttalent_1')); ?>" style="height: 175px;">
                </figure>
            </div>
            <div>
                <figure class="find-talent-info-slider-item">
                    <figcaption><?php echo e(trans('label.home.findyourperfecttalent_2')); ?></figcaption>
                    <img src="<?php echo e(asset('frontend/assets/img/section-search-talent-img-2.png')); ?>" alt="<?php echo e(trans('label.home.findyourperfecttalent_2')); ?>" style="height: 175px;">
                </figure>
            </div>
            <div>
                <figure class="find-talent-info-slider-item">
                    <figcaption><?php echo e(trans('label.home.findyourperfecttalent_3')); ?></figcaption>
                    <img src="<?php echo e(asset('frontend/assets/img/section-search-talent-img-3.png')); ?>" alt="<?php echo e(trans('label.home.findyourperfecttalent_3')); ?>" style="height: 175px;">
                </figure>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
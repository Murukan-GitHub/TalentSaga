<?php $__env->startSection('content'); ?>
    <div class="site-main-inner">
        <div class="container">
            <figure class="category-header">
                <img class="category-header-img" src="<?php echo e($talentCategory->banner_image ? $talentCategory->banner_image_large_cover : asset('frontend/assets/img/category-header-image.jpg')); ?>" alt="<?php echo e(ucwords($talentCategory->name__trans)); ?>">
                <figure class="category-header-info">
                    <h2 class="category-header-heading"><?php echo e(ucwords($talentCategory->name__trans)); ?></h2>
                    <p class="category-header-desc"><?php echo e($talentCategory->description); ?></p>
                </figure>
            </figure>

            <div class="category-layout">
                <div class="category-layout-filter-trigger">
                    <br>
                    <button class="btn btn--tosca">
                        <span class="fa fa-fw fa-filter"></span>
                        <?php echo e(trans('label.filter')); ?>

                    </button>
                </div>
                <div class="category-filter" ng-controller="CategoryFilterController">
                    <form class="category-filter-form" action="<?php echo e(route('talent.list', ['categorySlug' => $talentCategory->slug])); ?>" method="get">
                        <div class="block-half">
                            <label class="sr-only" for="categoryFilterSearch"><?php echo e(trans('label.home.search')); ?></label>
                            <input class="form-input" id="categoryFilterSearch" type="text" name="keyword" placeholder="<?php echo e(trans('label.home.search')); ?>" value="<?php echo e($keywordTerms); ?>">
                        </div>
                        
                    <?php if($expertises && $expertises->count() > 0): ?>
                        <fieldset>
                            <legend><?php echo e(trans('label.expertise')); ?></legend>
                            <?php 
                                $selectedExpertise = [];
                                if (isset($param['_talentExpertises_id'])) {
                                    $selectedExpertise = explode(',', $param['_talentExpertises_id']);
                                }
                            ?>
                            <?php foreach($expertises as $expertise): ?>
                                <div>
                                    <label class="custom-checkbox" for="expertise<?php echo e($expertise->id); ?>">
                                        <input id="expertise<?php echo e($expertise->id); ?>" type="checkbox" name="_talentExpertises_id[<?php echo e($expertise->id); ?>]" <?php echo e(in_array($expertise->id, $selectedExpertise) ? 'checked' : ''); ?>>
                                        <span><?php echo e($expertise->name__trans); ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>
                    <?php endif; ?>

                        <fieldset>
                            <legend><?php echo e(trans('label.location')); ?></legend>
                            <?php
                                $routeParam = [];
                                if (isset($param['country_ids']) && !empty($param['country_ids'])) 
                                    $routeParam['selected_country_id'] = $param['country_ids'];
                                if (isset($param['city_ids']) && !empty($param['city_ids'])) 
                                    $routeParam['selected_city_id'] = $param['city_ids'];
                            ?>
                            <div class="block-half normalize-selectize">
                                <select id="selectCountry" name="country_ids" data-countries="<?php echo e(route('frontend.location.fetcher', $routeParam)); ?>" ng-model="filterCountry" ng-options="country.value as country.name for country in countries track by country.value" ng-change="getCitiesByCountry()"></select>
                            </div>

                            <div class="block-half normalize-selectize">
                                <select id="selectCity" name="city_ids" ng-model="filterCities" ng-options="city.value as city.name for city in cities track by city.value" ng-disabled="isGettingCities"></select>
                            </div>
                        </fieldset>

                        <div>
                            <fieldset>
                                <legend><?php echo e(trans('label.price')); ?></legend>

                                <div class="v-center v-center--compact block">
                                    <input class="form-input" id="minimumPrice" name="_min_price_estimation" type="text" placeholder="Min" value="<?php echo e($param['_min_price_estimation']); ?>">
                                    <span>-</span>
                                    <input class="form-input" id="MaximumPrice" name="_max_price_estimation" type="text" placeholder="Max" value="<?php echo e($param['_max_price_estimation']); ?>">
                                </div>

                            </fieldset>
                        </div>

                        <button class="btn btn--block btn--tosca" type="submit"><?php echo e(trans('label.apply')); ?></button>
                    </form>
                </div>
                <div class="category-content">
                <?php if($talentList && count($talentList) > 0): ?>
                    <div class="talent-list talent-list--category">
                    <?php foreach($talentList as $talentProfile): ?>
                        <div class="talent-list-item">
                            <a class="talent-anchor" href="<?php echo e(route('user.profile', ['userId' => $talentProfile->user->id])); ?>">
                                <figure class="talent">
                                    <img class="talent-img" src="<?php echo e($talentProfile->user->picture ? $talentProfile->user->picture_large_square : ($talentProfile->user->firstImageGallery() ? $talentProfile->user->firstImageGallery()->image_media_url_large_square : asset('frontend/assets/img/thumb-talent-1.jpg') )); ?>" alt="">
                                    <figcaption class="talent-desc">
                                        <span class="talent-label"><?php echo e($talentProfile->talent_profession ? $talentProfile->talent_profession : 'Artist'); ?></span>
                                        <h3 class="talent-name"><?php echo e($talentProfile->user->full_name); ?>, <?php echo e($talentProfile->user->age); ?></h3>
                                        <span class="talent-location">
                                            <span class="fa fa-map-marker"></span>
                                            <span><?php echo e($talentProfile->available_cities_name); ?></span>
                                        </span>
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    </div>

                    <?php echo $__env->make('frontend.partials.pagination', ['paginator' => $talentList], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php else: ?>
                    <center>
                        <br><br>
                        <i>( no talent available )</i>
                        <br><br>
                    </center>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layout.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
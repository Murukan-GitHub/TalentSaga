@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <div class="container">
            <figure class="category-header">
                <img class="category-header-img" src="{{ $talentCategory->banner_image ? $talentCategory->banner_image_large_cover : asset('frontend/assets/img/category-header-image.jpg') }}" alt="{{ ucwords($talentCategory->name__trans) }}">
                <figure class="category-header-info">
                    <h2 class="category-header-heading">{{ ucwords($talentCategory->name__trans) }}</h2>
                    <p class="category-header-desc">{{ $talentCategory->description }}</p>
                </figure>
            </figure>

            <div class="category-layout">
                <div class="category-layout-filter-trigger">
                    <br>
                    <button class="btn btn--tosca">
                        <span class="fa fa-fw fa-filter"></span>
                        {{ trans('label.filter') }}
                    </button>
                </div>
                <div class="category-filter" ng-controller="CategoryFilterController">
                    <form class="category-filter-form" action="{{ route('talent.list', ['categorySlug' => $talentCategory->slug]) }}" method="get">
                        <div class="block-half">
                            <label class="sr-only" for="categoryFilterSearch">{{ trans('label.home.search') }}</label>
                            <input class="form-input" id="categoryFilterSearch" type="text" name="keyword" placeholder="{{ trans('label.home.search') }}" value="{{ $keywordTerms }}">
                        </div>
                        
                    @if($expertises && $expertises->count() > 0)
                        <fieldset>
                            <legend>{{ trans('label.expertise') }}</legend>
                            <?php 
                                $selectedExpertise = [];
                                if (isset($param['_talentExpertises_id'])) {
                                    $selectedExpertise = explode(',', $param['_talentExpertises_id']);
                                }
                            ?>
                            @foreach($expertises as $expertise)
                                <div>
                                    <label class="custom-checkbox" for="expertise{{ $expertise->id }}">
                                        <input id="expertise{{ $expertise->id }}" type="checkbox" name="_talentExpertises_id[{{ $expertise->id }}]" {{ in_array($expertise->id, $selectedExpertise) ? 'checked' : '' }}>
                                        <span>{{ $expertise->name__trans }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </fieldset>
                    @endif

                        <fieldset>
                            <legend>{{ trans('label.location') }}</legend>
                            <?php
                                $routeParam = [];
                                if (isset($param['country_ids']) && !empty($param['country_ids'])) 
                                    $routeParam['selected_country_id'] = $param['country_ids'];
                                if (isset($param['city_ids']) && !empty($param['city_ids'])) 
                                    $routeParam['selected_city_id'] = $param['city_ids'];
                            ?>
                            <div class="block-half normalize-selectize">
                                <select id="selectCountry" name="country_ids" data-countries="{{ route('frontend.location.fetcher', $routeParam) }}" ng-model="filterCountry" ng-options="country.value as country.name for country in countries track by country.value" ng-change="getCitiesByCountry()"></select>
                            </div>

                            <div class="block-half normalize-selectize">
                                <select id="selectCity" name="city_ids" ng-model="filterCities" ng-options="city.value as city.name for city in cities track by city.value" ng-disabled="isGettingCities"></select>
                            </div>
                        </fieldset>

                        <div>
                            <fieldset>
                                <legend>{{ trans('label.price') }}</legend>

                                <div class="v-center v-center--compact block">
                                    <input class="form-input" id="minimumPrice" name="_min_price_estimation" type="text" placeholder="Min" value="{{ $param['_min_price_estimation'] }}">
                                    <span>-</span>
                                    <input class="form-input" id="MaximumPrice" name="_max_price_estimation" type="text" placeholder="Max" value="{{ $param['_max_price_estimation'] }}">
                                </div>

                            </fieldset>
                        </div>

                        <button class="btn btn--block btn--tosca" type="submit">{{ trans('label.apply') }}</button>
                    </form>
                </div>
                <div class="category-content">
                @if($talentList && count($talentList) > 0)
                    <div class="talent-list talent-list--category">
                    @foreach($talentList as $talentProfile)
                        <div class="talent-list-item">
                            <a class="talent-anchor" href="{{ route('user.profile', ['userId' => $talentProfile->user->id]) }}">
                                <figure class="talent">
                                    <img class="talent-img" src="{{ $talentProfile->user->picture ? $talentProfile->user->picture_large_square : ($talentProfile->user->firstImageGallery() ? $talentProfile->user->firstImageGallery()->image_media_url_large_square : asset('frontend/assets/img/thumb-talent-1.jpg') ) }}" alt="">
                                    <figcaption class="talent-desc">
                                        <span class="talent-label">{{ $talentProfile->talent_profession ? $talentProfile->talent_profession : 'Artist' }}</span>
                                        <h3 class="talent-name">{{ $talentProfile->user->full_name }}, {{ $talentProfile->user->age }}</h3>
                                        <span class="talent-location">
                                            <span class="fa fa-map-marker"></span>
                                            <span>{{ $talentProfile->available_cities_name }}</span>
                                        </span>
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    @endforeach
                    </div>

                    @include('frontend.partials.pagination', ['paginator' => $talentList])
                @else
                    <center>
                        <br><br>
                        <i>( no talent available )</i>
                        <br><br>
                    </center>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
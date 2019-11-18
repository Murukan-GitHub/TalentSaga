@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <div class="container">
            <div class="category-layout">
                <div class="category-layout-filter-trigger">
                    <br>
                    <button class="btn btn--tosca">
                        <span class="fa fa-fw fa-filter"></span>
                        {{ trans('label.filter') }}
                    </button>
                </div>
                <div class="category-filter" ng-controller="CategoryFilterController">
                    <form class="category-filter-form" action="{{ route('talent.search') }}" method="get">
                        <div class="block-half">
                            <label class="sr-only" for="categoryFilterSearch">{{ trans('label.home.search') }}</label>
                            <input class="form-input" id="categoryFilterSearch" type="text" name="keyword" placeholder="{{ trans('label.home.search') }}" value="{{ $keywordTerms }}">
                        </div>

                        @if($talentCategories && $talentCategories->count() > 0)
                            <div class="block-half">
                                <label class="form-label" for="categoryFilterCategory">{{ trans('label.talent_category') }}</label>

                                <select class="form-input" id="categoryFilterCategory" name="talent_category_id">
                                @foreach($talentCategories as $cat)
                                    <option value="{{ $cat->id }} 1">{{ $cat->name__trans}}</option>
                                @endforeach
                                </select>
                            </div>
                        @endif

                    <!--
                        <fieldset ng-controller='TalentCategoryExpertiseController' data-categories="{{ route('frontend.talentexpertise.fetcher', ['selected_category_id' => $param['talent_category_id'], 'selected_expertise_id' => $param['_talentExpertises_id'] ]) }}">
                            <legend>Expertise</legend>

                            <div>
                                <label class="custom-checkbox" for="expertise1">
                                    <input id="expertise1" type="checkbox" name="expertise">
                                    <span>Singer</span>
                                </label>
                            </div>
                            <div>
                                <label class="custom-checkbox" for="expertise2">
                                    <input id="expertise2" type="checkbox" name="expertise">
                                    <span>Pianist</span>
                                </label>
                            </div>
                            <div>
                                <label class="custom-checkbox" for="expertise3">
                                    <input id="expertise3" type="checkbox" name="expertise">
                                    <span>Guitarist</span>
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="block" ng-show="!isLoading">
                            <legend>{{ trans('label.expertise') }} <span class="text-tosca">*</span></legend>

                            <div ng-repeat="expertise in talentExpertises">
                                <label class="custom-checkbox" for="expertise@{{ $index }}">
                                    <input id="expertise@{{ $index }}" type="checkbox" name="talent_expertise_id[]" ng-value="@{{ expertise.value }}" ng-checked="expertise.selected">
                                    <span>@{{ expertise.description }}</span>
                                </label>
                            </div>
                        </fieldset>
                    -->

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
                    <h2 class="h3 text-light">Search results for "{{ $keywordTerms }}"</h2>
                @if($talentList && count($talentList) > 0)
                    <div class="talent-list talent-list--category">
                    @foreach($talentList as $talentProfile)
                    @if($talentProfile->user)
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
                    @endif
                    @endforeach
                    </div>

                    @include('frontend.partials.pagination', ['paginator' => $talentList])
                @else
                    <center>
                        <br><br>
                        <i>( no talent match your search criteria )</i>
                        <br><br>
                    </center>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
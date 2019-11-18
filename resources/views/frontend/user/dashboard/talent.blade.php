@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <div class="container">
            <div class="category-layout">
                <div class="category-layout-filter-trigger">
                    <br>
                    <button class="btn btn--tosca">Menu</button>
                </div>
                <div class="category-filter">
                    @include('frontend.partials.dashboard.menu')
                </div>
                <div class="category-content">
                    <h2>{{ trans('label.data_talent.title_dashboard') }}</h2>

                    <div class="bzg">
                        <div class="bzg_c" data-col="l9">
                            {!! Form::open(['route' => 'user.dashboard.talent.save', 'class' => 'form-baa', 'data-validate', 'style' => 'opacity: 0;', 'ng-controller' => 'TalentCategoryExpertiseController', 'data-categories' => route('frontend.talentexpertise.fetcher', ['selected_category_id' => $userProfile->talent_category_id, 'selected_expertise_id' => $selectedExpertises])]) !!}
                                <fieldset class="block-half">
                                    <div class="block">
                                        <label class="form-label" for="selectCategory">{{ trans('label.data_talent.category') }} <span class="text-tosca">*</span></label>
                                        <select class="form-input" id="selectCategory" ng-model="talentCategory" ng-options="category.value as category.description for category in talentCategories track by category.value" ng-change="getExpertise()" ng-disabled="isLoading" name="talent_category_id"></select>
                                    </div>

                                    <fieldset class="block" ng-show="!isLoading">
                                        <legend>{{ trans('label.data_talent.expertise') }} <span class="text-tosca">*</span></legend>

                                        <div ng-repeat="expertise in talentExpertises">
                                            <label class="custom-checkbox" for="expertise@{{ $index }}">
                                                <input id="expertise@{{ $index }}" type="checkbox" name="talent_expertise_id[]" ng-value="@{{ expertise.value }}" ng-checked="expertise.selected">
                                                <span>@{{ expertise.description }}</span>
                                            </label>
                                        </div>

                                        <label class="sr-only" for="inputOtherExpertise">Other</label>
                                        <input class="form-input" id="inputOtherExpertise" type="text" name="other_talent_expertise_id" placeholder="{{ trans('label.data_talent.other_expertise') }}" value="{{ $otherSelectedExpertises }}">
                                    </fieldset>

                                    <label class="form-label" for="inputDescribe">{{ trans('label.data_talent.description', ['length' => settings('maxProfileDescLength', 200)]) }} <span class="text-tosca">*</span></label>
                                    <textarea class="form-input" id="inputDescribe" rows="5" name="talent_description" data-max-char="{{ settings('maxProfileDescLength', 200) }}" required>{{ $userProfile->talent_description }}</textarea>
                                    <div class="text-right">
                                        <small>@{{ charCount }}/{{ settings('maxProfileDescLength', 200) }}</small>
                                    </div>
                                </fieldset>

                                <div class="form-baa-actions">
                                    <button class="btn btn--gray" name="saveasdraft" value="true" type="submit">{{ trans('label.save_draft') }}</button>
                                    <button class="btn btn--tosca" type="submit">{{ trans('label.save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- talent -->
@stop

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
                    <h2>{{ trans('label.data_personal.title') }}</h2>

                    <div class="bzg">
                        <div class="bzg_c" data-col="l9">
                            {!!Form::open(['route' => 'user.dashboard.personal.save', 'class' => 'form-baa', 'data-validate'])!!}
                                <fieldset>
                                    <div class="bzg">
                                        <div class="bzg_c" data-col="l6">
                                            <div class="block-half">
                                                <label class="form-label" for="inputFirstName">{{ trans('label.data_personal.first_name') }} <span class="text-tosca">*</span></label>
                                                <input class="form-input" id="inputFirstName" type="text" name="name" value="{{ $user->name }}" required>
                                                {{$errors->first('name')}}
                                            </div>
                                        </div>
                                        <div class="bzg_c" data-col="l6">
                                            <div class="block-half">
                                                <label class="form-label" for="inputLastName">{{ trans('label.data_personal.last_name') }} <span class="text-tosca">*</span></label>
                                                <input class="form-input" id="inputLastName" type="text" name="last_name" value="{{ $user->last_name }}" required>
                                                {{$errors->first('last_name')}}
                                            </div>
                                        </div>
                                    </div>


                                    <fieldset>
                                        <legend>{{ trans('label.data_personal.birthday') }} <span class="text-tosca">*</span></legend>

                                        <div class="bzg">
                                            <div class="bzg_c" data-col="s4">
                                                <div class="block-half">
                                                    <label class="sr-only" for="birthdayDay">Day of birth</label>
                                                    <select class="form-input" id="birthdayDay" name="date" required>
                                                        <option value="">-- {{ trans('label.data_personal.date') }} --</option>
                                                    @for ($d = 1; $d <= 31; $d++)
                                                        <option value="{{$d}}" {{old('date') ?: (isset($user) && $user->birthdate && $user->birthdate->format('Y') != '1900' ? $user->birthdate->format('d') : '') == $d ? 'selected="selected"' : ''}}>{{$d}}</option>
                                                    @endfor
                                                    </select>
                                                    @if ($errors->has('date'))<span class="msg-error">{{$errors->first('date')}}</span>@endif
                                                </div>
                                            </div>
                                            <div class="bzg_c" data-col="s4">
                                                <div class="block-half">
                                                    <label class="sr-only" for="birthdayMonth">Month of birth</label>
                                                    <select class="form-input" id="birthdayMonth" name="month" required>
                                                        <option value="">-- {{ trans('label.data_personal.month') }} --</option>
                                                    @for ($m = 1; $m <= 12; $m++)
                                                        <option value="{{$m}}" {{old('month') ?: (isset($user) && $user->birthdate && $user->birthdate->format('Y') != '1900' ? $user->birthdate->format('m') : '') == $m ? 'selected="selected"' : ''}}>{{trans('datetime.'.strtolower(Carbon\Carbon::now()->month($m)->format('F')))}}</option>
                                                    @endfor
                                                    </select>
                                                    @if ($errors->has('month'))<span class="msg-error">{{$errors->first('month')}}</span>@endif
                                                </div>
                                            </div>
                                            <div class="bzg_c" data-col="s4">
                                                <div class="block-half">
                                                    <label class="sr-only" for="birthdayYear">Year of birth</label>
                                                    <select class="form-input" id="birthdayYear" name="year" required>
                                                        <option value="">-- {{ trans('label.data_personal.year') }} --</option>
                                                    @for ($y = (new Carbon\Carbon('17 years ago'))->format('Y'); $y >= (new Carbon\Carbon('60 years ago'))->format('Y'); $y--)
                                                        <option data-id="year-{{$y}}" value="{{$y}}" {{old('year') ?: (isset($user) && $user->birthdate && $user->birthdate->format('Y') != '1900' ? $user->birthdate->format('Y') : '') == $y ? 'selected="selected"' : ''}}>{{$y}}</option>
                                                    @endfor
                                                    </select>
                                                    @if ($errors->has('year'))<span class="msg-error">{{$errors->first('year')}}</span>@endif
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('birthdate'))<span class="msg-error">Anda harus berusia minimal 17 tahun</span>@endif
                                    </fieldset>

                                    <div class="block-half">
                                        <label class="form-label" for="inputProfession">{{ trans('label.data_personal.profession') }} <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputProfession" type="text" name="talent_profession" value="{{ $userProfile->talent_profession }}" required>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputPhone">{{ trans('label.data_personal.phone_number') }} <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputPhone" type="text" name="phone_number" value="{{ $user->phone_number }}" required>
                                    </div>

                                <!--
                                    <fieldset ng-controller="SelectCountryCityController" data-countries="{{ route('frontend.location.fetcher', ['selected_country_id' => $userProfile->country_id, 'selected_city_id' => $userProfile->city_id]) }}">
                                        <div class="block-half normalize-selectize">
                                            <label class="form-label" for="inputCountry">Your country <span class="text-tosca">*</span></label>
                                            <select class="form-input" id="inputCountry" name="country_id" ng-model="filterCountry" ng-options="country.value as country.name for country in countries track by country.value" ng-change="getCitiesByCountry()" required></select>
                                        </div>

                                        <div class="block-half normalize-selectize">
                                            <label class="form-label" for="inputCity">Your city <span class="text-tosca">*</span></label>
                                            <select class="form-input" id="inputCity" name="city_id" ng-model="filterCities" ng-options="city.value as city.name for city in cities track by city.value" ng-disabled="isGettingCities" required=""></select>
                                        </div>
                                    </fieldset>
                                -->

                                    <div class="block-half">
                                        <label for="selectCity">{{ trans('label.data_personal.city') }}</label>
                                        <select class="form-input" name="city_id" id="selectCity" data-enhance-select>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" @if($userProfile->city_id == $city->id) selected @endif>{{ $city }}</option>
                                        @endforeach
                                        </select>
                                    </div>


                                    <div class="bzg">
                                        <div class="bzg_c" data-col="l9">
                                            <div class="block-half">
                                                <label class="form-label" for="inputAddress">{{ trans('label.data_personal.street_address') }} <span class="text-tosca">*</span></label>
                                                <input class="form-input" id="inputAddress" type="text" name="street_name" value="{{ $userProfile->street_name }}" required>
                                            </div>
                                        </div>
                                        <div class="bzg_c" data-col="l3">
                                            <div class="block-half">
                                                <label class="form-label" for="inputStreetNo">{{ trans('label.data_personal.street_number') }} <span class="text-tosca">*</span></label>
                                                <input class="form-input" id="inputStreetNo" type="text" name="street_number" value="{{ $userProfile->street_number }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputZipCode">{{ trans('label.data_personal.zip_code') }} <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputZipCode" type="text" name="zip_code" value="{{ $userProfile->zip_code }}" required>
                                    </div>

                                    <fieldset class="block-half">
                                        <legend>{{ trans('label.data_personal.gender') }} <span class="text-tosca">*</span></legend>

                                        <div>
                                            <label class="custom-radio" for="genderMale">
                                                <input id="genderMale" type="radio" name="gender" {{ $userProfile->gender == 'male' ? 'checked' : ''}} value="male">
                                                <span>{{ trans('label.data_personal.male') }}</span>
                                            </label>
                                        </div>

                                        <div>
                                            <label class="custom-radio" for="genderFemale">
                                                <input id="genderFemale" type="radio" name="gender" {{ $userProfile->gender == 'female' ? 'checked' : ''}} value="female">
                                                <span>{{ trans('label.data_personal.female') }}</span>
                                            </label>
                                        </div>
                                    </fieldset>

                                    <div class="block-half sr-only">
                                        <label class="form-label" for="inputWeight">Weight (in kilogram)</label>
                                        <input class="form-input" id="inputWeight" type="number" name="weight" value="{{ $userProfile->weight }}">
                                    </div>

                                    <div class="block-half sr-only">
                                        <label class="form-label" for="inputHeight">Height (in cm)</label>
                                        <input class="form-input" id="inputHeight" type="number" name="height" value="{{ $userProfile->height }}" >
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <legend>{{ trans('label.data_personal.social_media') }}</legend>

                                    <div class="block-half">
                                        <label class="sr-only" for="inputFacebook">Facebook</label>
                                        <input class="form-input" id="inputFacebook" type="text" placeholder="Facebook" name="facebook_page" value="{{ $userProfile->facebook_page }}">
                                    </div>

                                    <div class="block-half">
                                        <label class="sr-only" for="inputTwitter">Twitter</label>
                                        <input class="form-input" id="inputTwitter" type="text" placeholder="Twitter" name="twitter_page" value="{{ $userProfile->twitter_page }}" >
                                    </div>

                                    <div class="block-half">
                                        <label class="sr-only" for="inputInstagram">Instagram</label>
                                        <input class="form-input" id="inputInstagram" type="text" placeholder="Instagram" name="instagram_page" value="{{ $userProfile->instagram_page }}">
                                    </div>

                                    <div class="block-half">
                                        <label class="sr-only" for="inputYoutube">Youtube</label>
                                        <input class="form-input" id="inputYoutube" type="text" placeholder="Youtube" name="youtube_page" value="{{ $userProfile->youtube_page }}" >
                                    </div>
                                </fieldset>

                                <div class="form-baa-actions">
                                    <button class="btn btn--gray" name="status" value="true" type="submit">{{ trans('label.save_draft') }}</button>
                                    <button class="btn btn--tosca" type="submit">{{ trans('label.save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- personal -->
@stop

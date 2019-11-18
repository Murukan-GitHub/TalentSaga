@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="sr-only">Become an artist</h2>

            @include('frontend.partials.dashboard.becomeartistmenu')

            {!! Form::open(['route' => 'user.onboarding.pricing.save', 'class' => 'form-baa', 'data-validate']) !!}
                <fieldset class="block-half">
                    <legend class="text-center h3 text-caps">Pricing</legend>

                    <fieldset id="priceEstimationContainer">
                        <legend>Price estimation</legend>

                        <div id="priceEstimationContent">
                            <div class="price-estimation">
                                <div class="bzg">
                                    <div class="bzg_c" data-col="m4">
                                        <div class="block-half">
                                            <label class="form-label"><small>Currency</small></label>
                                            <select class="form-input" name="currency_id">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}" {{ $userProfile->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="bzg_c" data-col="m4">
                                        <div class="block-half">
                                            <label class="form-label"><small>Amount</small></label>
                                            <input class="form-input" type="text" name="price_estimation" value="{{ $userProfile->price_estimation }}">
                                        </div>
                                    </div>
                                    <div class="bzg_c" data-col="m4">
                                        <div class="block-half">
                                            <label class="form-label"><small>Duration</small></label>
                                            <select class="form-input" name="pricing_metric">
                                                <option value="hourly" {{ $userProfile->pricing_metric == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                                <option value="daily" {{ $userProfile->pricing_metric == 'daily' ? 'selected' : '' }}>Daily</option>
                                                <option value="weekly" {{ $userProfile->pricing_metric == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                    </fieldset>

                    <label class="custom-checkbox" for="contactPrice">
                        <input id="contactPrice" type="checkbox" name="contact_for_price" {{ $userProfile->contact_for_price ? 'checked' : '' }} value="1">
                        <span>Contact me for price</span>
                    </label>

                    <fieldset>
                        <legend>Price inclusion <small><sup>*</sup>price offerred should includes these items</small></legend>
                    @foreach($priceInclusions as $inclusion)
                        <div>
                            <label class="custom-checkbox" for="inclusion{{ $inclusion->id }}">
                                <input id="inclusion{{ $inclusion->id }}" type="checkbox" name="inclusion[]" value="{{ $inclusion->id }}" {{ in_array($inclusion->id, $selectedPriceInclusions) ? 'checked' : '' }}>
                                <span>{{ $inclusion->name__trans }}</span>
                            </label>
                        </div>
                    @endforeach
                        <div>
                            <label class="custom-checkbox" for="other_inclusions_enabler">
                                <input id="other_inclusions_enabler" type="checkbox" onclick="$('#other_inclusions').toggle(this.checked);" {{ empty($otherInclusions) ? '' : 'checked' }}>
                                <span>Others</span>
                                &nbsp;&nbsp;&nbsp;
                                <span style="width: 480px;"><input id="other_inclusions" class="form-input" tide="text" placeholder="e.g. travel expenses, equipment, etc" name="other_inclusions" value="{{ $otherInclusions }}" style="{{ empty($otherInclusions) ? 'display: none;' : '' }}"></span>
                            </label>
                        </div>
                    </fieldset>

                    <div class="block-half">
                        <label class="form-label" for="inputPriceNote">Price notes <small><sup>*</sup>if needed</small></label>
                        <textarea class="form-input" id="inputPriceNote" rows="4" name="price_notes">{{ $userProfile->price_notes }}</textarea>
                    </div>

                    <fieldset>
                        <legend><b>Availability area</b></legend>

                        <fieldset class="block-half">
                            <div>
                                <div>
                                    <small>Cities / Countries</small>
                                    <select class="invisible" name="city_availability[]" data-selectize multiple>
                                    @foreach($countries as $country)
                                        @foreach($country->cities as $city)
                                            <option value="{{ $city->id }}" {{ isset($availabilityAreaSet[$city->id]) ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    @endforeach
                                    </select>
                                    <!-- <input class="block-half" type="text" value="Germany City 1, Germany City 2, Germany City 3" data-selectize> -->
                                </div>
                            </div>
                        </fieldset>
                    </fieldset>
                </fieldset>

                <div class="form-baa-actions">
                    <a class="btn btn--tosca btn--outline" href="{{ route('user.onboarding.gallery') }}">{{ trans('label.back') }}</a>
                    <button class="btn btn--gray" name="saveasdraft" value="true" type="submit">{{ trans('label.save_draft') }}</button>
                    <button class="btn btn--tosca" type="submit">{{ trans('label.finish') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- pricing -->
@stop

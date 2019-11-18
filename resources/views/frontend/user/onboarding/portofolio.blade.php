@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="sr-only">Become an artist</h2>

            @include('frontend.partials.dashboard.becomeartistmenu')

            {!! Form::open(['route' => 'user.onboarding.portofolio.save', 'class' => 'form-baa', 'data-validate', 'ng-controller' => 'PortofolioFormController']) !!}
                <fieldset class="block-half">
                    <legend class="text-center h3 text-caps">Portfolio</legend>
                    <p class="text-center">Tell us about your experience performing your talents.</p>

                @if($userPortofolios)
                    @foreach($userPortofolios as $key=>$userPortofolio)
                        <div style="background-color: #dddddd; padding: 12px; margin: 4px;">
                            <div class="block-half">
                                <label class="form-label" for="inputEventDate{{ 100+$key }}">Date of performance <span class="text-tosca">*</span></label>
                                <input class="form-input" id="inputEventDate{{ 100+$key }}" type="text" name="eventDate[{{ 100+$key }}]" value="{{ $userPortofolio->event_date->format('m/d/Y') }}" data-datepicker required>
                            </div>

                            <div class="block-half">
                                <label class="form-label" for="inputEventName{{ 100+$key }}">Name of event <span class="text-tosca">*</span></label>
                                <input class="form-input" id="inputEventName{{ 100+$key }}" type="text" name="eventName[{{ 100+$key }}]" value="{{ $userPortofolio->event_name }}" required>
                            </div>

                            <div class="block-half">
                                <label class="form-label" for="inputExperience{{ 100+$key }}">Describe your experience <span class="text-tosca">*</span></label>
                                <textarea class="form-input" id="inputExperience{{ 100+$key }}" rows="5" name="experience[{{ 100+$key }}]" value="{{ $userPortofolio->description }}" required>{{ $userPortofolio->description }}</textarea>
                            </div>

                            <div class="block-half">
                                <label class="form-label" for="inputEventUrl{{ 100+$key }}">URL of event <span class="text-tosca">*</span></label>
                                <input class="form-input" id="inputEventUrl{{ 100+$key }}" type="text" name="eventUrl[{{ 100+$key }}]" value="{{ $userPortofolio->url }}" required>
                                <small>Can be video or article review</small>
                            </div>
                        </div>
                    @endforeach
                    <br><hr><br>
                @endif

                    <div ng-repeat="portofolio in portofolios" on-finish-render="ngRepeatFinished">
                        <div class="block-half">
                            <label class="form-label" for="inputEventDate@{{$index}}">Date of performance <span class="text-tosca">*</span></label>
                            <input class="form-input" id="inputEventDate@{{$index}}" type="text" name="eventDate[@{{$index}}]" ng-model="portofolios[$index].date" data-datepicker required>
                        </div>

                        <div class="block-half">
                            <label class="form-label" for="inputEventName@{{$index}}">Name of event <span class="text-tosca">*</span></label>
                            <input class="form-input" id="inputEventName@{{$index}}" type="text" name="eventName[@{{$index}}]" ng-model="portofolios[$index].name" required>
                        </div>

                        <div class="block-half">
                            <label class="form-label" for="inputExperience@{{$index}}">Describe your experience <span class="text-tosca">*</span></label>
                            <textarea class="form-input" id="inputExperience@{{$index}}" rows="5" name="experience[@{{$index}}]" ng-model="portofolios[$index].experience" required></textarea>
                        </div>

                        <div class="block-half">
                            <label class="form-label" for="inputEventUrl@{{$index}}">URL of event <span class="text-tosca">*</span></label>
                            <input class="form-input" id="inputEventUrl@{{$index}}" type="text" name="eventUrl[@{{$index}}]" ng-model="portofolios[$index].url" required>
                            <small>Can be video or article review</small>
                        </div>
                        <a href="" ng-click="removePortofolio(portofolio.id)" @if(!$userPortofolios) ng-hide="portofolio.id == 1" @endif><small>Remove</small></a>
                        <hr>
                    </div>

                    <a href="" ng-click="addPortofolio()">
                        <span class="fa fa-fw fa-plus-circle"></span> Add more experience
                    </a>
                </fieldset>

                <div class="form-baa-actions">
                    <a class="btn btn--tosca btn--outline" href="{{ route('user.onboarding.talent') }}">{{ trans('label.back') }}</a>
                    <button class="btn btn--gray" name="saveasdraft" value="true" type="submit">{{ trans('label.save_draft') }}</button>
                    <button class="btn btn--tosca" type="submit">{{ trans('label.next') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- portofolio -->
@stop

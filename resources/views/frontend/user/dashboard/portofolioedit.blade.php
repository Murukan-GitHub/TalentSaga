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
                    <h2><a href="{{ route('user.dashboard.portofolio') }}">Portofolios</a> | {{ $userPortofolio->event_name }}</h2>

                    <div class="bzg">
                        <div class="bzg_c" data-col="l9">
                            {!! Form::open(['url' => route('user.dashboard.portofolio.edit.save', ['id' => $userPortofolio->id]), 'data-validate']) !!}
                                <div>
                                    <div class="block-half">
                                        <label class="form-label" for="inputEventDate">Date of performance <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputEventDate" type="text" name="event_date" value="{{ $userPortofolio->event_date->format('m/d/Y') }}" data-datepicker required>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventName">Name of event <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputEventName" type="text" name="event_name" value="{{ $userPortofolio->event_name }}" required>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputExperience">Describe your experience <span class="text-tosca">*</span></label>
                                        <textarea class="form-input" id="inputExperience" rows="5" name="description" required>{{ $userPortofolio->description }}</textarea>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventUrl">URL of event <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputEventUrl" type="text" name="url" value="{{ $userPortofolio->url }}" required>
                                        <small>Can be video or article review</small>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventYoutubeUrl">Youtube URL of event</label>
                                        <input class="form-input" id="inputEventYoutubeUrl" name="youtube_url" type="text" value="{{ $userPortofolio->youtube_url }}">
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputStatus">Status</label>
                                        <select class="form-input" id="inputStatus" name="status" required>
                                        @foreach($userPortofolio->getStatusOptions() as $key=>$value)
                                            <option value="{{ $key }}" {{ $userPortofolio->status == $key ? "selected" : "" }}>{{ $value }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <button class="btn btn--tosca">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- portofolio -->
@stop

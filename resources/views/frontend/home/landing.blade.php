@extends('frontend.layout.blank')

@section('content')
    <div class="landing" style="background-image: url({{ asset('frontend/assets/img/landing-page-bg.jpg') }});">
        <div class="container">
            <div class="landing-language-chooser" style="display: none;">
                <?php
                    $switchBaseUrl = request()->fullUrl();
                    $switchBaseUrl = $switchBaseUrl . (str_contains($switchBaseUrl, '?') ? '&' : '?') . 'locale=';
                ?>
                <a href="{{ $switchBaseUrl.'de' }}">@if (app()->getLocale() == 'de') <strong> @endif Germany @if (app()->getLocale() == 'de') </strong> @endif</a>
                &middot;
                <a href="{{ $switchBaseUrl.'en' }}">@if (app()->getLocale() == 'en') <strong> @endif English @if (app()->getLocale() == 'en') </strong> @endif</a>
                &middot;
                <a href="{{ $switchBaseUrl.'id' }}">@if (app()->getLocale() == 'id') <strong> @endif Indonesia @if (app()->getLocale() == 'id') </strong> @endif</a>
            </div>
            <div class="landing-content">
                <figure>
                    <img src="{{ asset('frontend/assets/img/logo-standalone.png') }}" alt="">
                </figure>

                <h1>{{ trans('label.landing.title') }}</h1>

                <p>{{ trans('label.landing.description_1') }}</p>

                <p>{{ trans('label.landing.description_2') }}</p>

                <p>{{ trans('label.landing.description_3') }}</p>

                <form method="post" class="landing-form block-half" action="{{ route('frontend.newsletter.subscribe') }}">
                    <label class="sr-only" for="landingEmail">Email</label>
                    <input type="hidden" name="_token" value="{{ $csrfToken or csrf_token() }}">
                    <input id="landingEmail" type="email" name="email" placeholder="{{ trans('label.landing.email_hint') }}">
                    <button class="btn btn--tosca" type="submit">{{ trans('label.landing.send') }}</button>
                </form>
                <!--
                <p><small>{{$errors->first('email')}}</small></p>
                -->
                <p><small>{{ trans('label.landing.email_footer_text') }}</small></p>
                <p>
                    <a href="{{ settings('facebook', 'https://www.facebook.com/talentsaga') }}">
                        <span class="fa fa-fw fa-lg fa-facebook-official"></span>
                    </a>
                    <a href="{{ settings('instagram', 'https://www.instagram.com/freche_lippe') }}">
                        <span class="fa fa-fw fa-lg fa-instagram"></span>
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

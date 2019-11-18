@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">{{ trans('label.registration.title') }}</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    {!!Form::open(['route' => 'frontend.user.registration.save', 'class' => 'text-left', 'data-validate'])!!}
                        {!! Form::hidden("language_setting", app()->getLocale()) !!}
                        <div class="block-half">
                            <label class="form-label sr-only" for="inputUsername">{{ trans('label.registration.username') }}</label>
                            <input class="form-input" id="inputUsername" type="text" name="username" placeholder="{{ trans('label.registration.username') }}" required>
                            {{$errors->first('username')}}
                        </div>
                        <div class="block-half">
                            <label class="form-label sr-only" for="inputEmail">{{ trans('label.registration.email') }}</label>
                            <input class="form-input" id="inputEmail" type="email" name="email" placeholder="{{ trans('label.registration.email') }}" required>
                            {{$errors->first('email')}}
                        </div>
                        <div class="block-half">
                            <label class="form-label sr-only" for="inputPassword">{{ trans('label.registration.password') }}</label>
                            <input class="form-input" id="inputPassword" type="password" name="password" placeholder="{{ trans('label.registration.password') }}" required>
                            {{$errors->first('password')}}
                        </div>
                        <div class="block-half">
                            <label class="form-label sr-only" for="inputConfirmPassword">{{ trans('label.registration.confirmpassword') }}</label>
                            <input class="form-input" id="inputConfirmPassword" type="password" name="password_confirm" placeholder="{{ trans('label.registration.confirmpassword') }}" required>
                            {{$errors->first('password_confirm')}}
                        </div>
                        <button class="btn btn--block btn--tosca block-half" type="submit">{{ trans('label.registration.createaccount') }}</button>

                        <p class="text-center block-half"><small>{{ trans('label.registration.alreadyhaveaccount') }}? <a href="{{ route('sessions.login') }}">{{ trans('label.registration.loginhere') }}</a>.</small></p>
                    </form>

                    <hr>

                    <div class="bzg">
                        <div class="bzg_c" data-col="m6">
                            <a class="btn btn--block btn--outline btn--fb block-half" href="{{route('sessions.auth', ['app' => 'facebook'])}}">
                                <span class="fa fa-fw fa-facebook-official"></span>
                                {{ trans('label.registration.fblogin') }}
                            </a>
                        </div>
                        <div class="bzg_c" data-col="m6">
                            <a class="btn btn--block btn--outline btn--gplus block-half" href="{{route('sessions.auth', ['app' => 'google'])}}">
                                <span class="fa fa-fw fa-google-plus-official"></span>
                                {{ trans('label.registration.googlelogin') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- register -->
@stop

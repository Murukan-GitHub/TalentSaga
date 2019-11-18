@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">{{ trans('label.login.title') }}</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    {!!Form::open(['route' => 'sessions.store', 'class' => 'text-left', 'data-validate'])!!}
                        <div class="block-half">
                            <label class="form-label sr-only" for="inputUsername">{{ trans('label.login.email') }}</label>
                            {!!Form::email('email', null, ['id' => 'inputUsername', 'class'=>'form-input', 'required' => 'true', 'placeholder' => trans('label.login.email')])!!}
                            {{$errors->first('email')}}
                        </div>
                        <div class="block-half">
                            <label class="form-label sr-only" for="inputPassword">{{ trans('label.login.password') }}</label>
                            {!!Form::password('password', ['id' => 'inputPassword', 'class'=>'form-input', 'required' => 'true', 'placeholder' => trans('label.login.password')])!!}
                            {{$errors->first('password')}}
                        </div>
                        <button class="btn btn--block btn--tosca block-half" type="submit">{{ trans('label.login.title') }}</button>

                        <p class="text-center block-half"><small>{{ trans('label.login.newuser') }}? <a href="{{route('frontend.user.registration')}}">{{ trans('label.login.registerhere') }}</a>.</small></p>
                        <p class="text-center block-half"><small>{{ trans('label.login.forgotpassword') }}? <a href="{{route('frontend.user.forgetpassword')}}">{{ trans('label.login.clickhere') }}</a>.</small></p>
                    </form>

                    <hr>

                    <div class="bzg">
                        <div class="bzg_c" data-col="m6">
                            <a class="btn btn--block btn--outline btn--fb block-half" href="{{route('sessions.auth', ['app' => 'facebook'])}}">
                                <span class="fa fa-fw fa-facebook-official"></span>
                                {{ trans('label.login.fblogin') }}
                            </a>
                        </div>
                        <div class="bzg_c" data-col="m6">
                            <a class="btn btn--block btn--outline btn--gplus block-half" href="{{route('sessions.auth', ['app' => 'google'])}}">
                                <span class="fa fa-fw fa-google-plus-official"></span>
                                {{ trans('label.login.googlelogin') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sign-in -->
@stop

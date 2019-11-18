@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">{{ trans('label.forgotpassword.title') }}</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    @if(count($errors))
                        <div class="alert alert-warning">
                            <ul class="list-unstyled">
                                @if($errors->has('email'))<li>{{ $errors->first('email') }}</li>@endif
                                @if($errors->has('password'))<li>{{ $errors->first('password') }}</li>@endif
                            </ul>
                        </div>  
                    @endif
                    {!! Form::open() !!}
                        <fieldset>
                            <div class="bzg">
                                <div class="bzg_c form__row text-center" data-col="l8 m8 s8" data-offset="l2 m2 s2">
                                    <label for="emailForgot" class="sr-only">{{ trans('label.forgotpassword.email') }}</label>
                                    {!! Form::text('email', null, ['id' => 'emailForgot', 'class' => 'form-input form-input--block', 'placeholder' => trans('label.forgotpassword.email')]) !!}
                                </div>
                            </div>
                            <div class="bzg">
                                <div class="bzg_c form__row" data-col="l6 m6 s10" data-offset="l3 m3 s1">
                                    <input class="btn btn--round btn--tosca btn--block" type="submit" value="{{ trans('label.forgotpassword.sendlink') }}"/>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form__row bzg">
                            <div class="bzg_c text-center" data-col="s12">
                                {{ trans('label.forgotpassword.backto') }} <a href="{{route('sessions.login')}}">Login</a>
                            </div>
                        </div>
                        <div class="form__row bzg">
                            &nbsp;
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

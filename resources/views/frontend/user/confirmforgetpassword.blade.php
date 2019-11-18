@extends('frontend.layout.base')


@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">Reset Password</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    @if(count($errors))
                    <div class="alert alert-warning text-center">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>  
                    @endif
                    {!! Form::open() !!}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <fieldset>
                            <div class="bzg">
                                <div class="bzg_c form__row" data-col="l8 m8 s8" data-offset="l2 m2 s2">
                                    <label for="email">Email</label>
                                    {!! Form::text('email', old('email', request()->get('email', null)), ['id' => 'email', 'class' => 'form-input form-input--block', 'readonly']) !!}
                                </div>
                            </div>
                            <div class="bzg">
                                <div class="bzg_c form__row" data-col="l8 m8 s8" data-offset="l2 m2 s2">
                                    <label for="password">New Password</label>
                                    {!! Form::password('password', ['id' => 'password', 'class' => 'form-input form-input--block']) !!}
                                </div>
                            </div>
                            <div class="bzg">
                                <div class="bzg_c form__row" data-col="l8 m8 s8" data-offset="l2 m2 s2">
                                    <label for="password">Confirm New Password</label>
                                    {!! Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-input form-input--block']) !!}
                                </div>
                            </div>
                            <div class="bzg">
                                <div class="bzg_c form__row" data-col="l6 m6 s6" data-offset="l3 m3 s3">
                                    <input class="btn btn--round btn--tosca btn--block" type="submit" value="Reset Password"/>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form__row bzg">
                            <div class="bzg_c text-center" data-col="s12">
                                Back to <a href="{{route('sessions.login')}}">Login</a>
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


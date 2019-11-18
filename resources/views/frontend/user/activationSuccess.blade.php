@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">Congratulation, your account has been activated!</h2>

            <div class="bzg">
                <div class="bzg_c text-center" data-col="l2" data-offset="l5">
                    <a href="{{route('sessions.login')}}" class="btn btn--wider btn--lite-blue text--U">Login Now</a>
                </div>
            </div>
        </div>
    </div>
@stop

@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">Failed Re-activate Your Account</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    <p>
                        Make sure you use <strong>{{$email}}</strong> for your Talentsaga account.
                    </p>
                    <p>
                        Or your account has been activated before so you don't need to re-activate.
                    </p>
                    <a href="{{ route('frontend.reactivation.link') }}" class="btn btn--round btn--tosca">Try Again</a>
                </div>
            </div>
        </div>
    </div>
@stop

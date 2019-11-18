@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">Sorry, something happens</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    <p>
                        <strong>Failed Activate Your Account.</strong>
                    </p>
                    <p>
                        Usually you have expired activation request or your account has been activated. 
                    </p>
                    <a href="{{ route('frontend.reactivation.link') }}" class="btn btn--round btn--tosca">Try Again</a>
                </div>
            </div>
        </div>
    </div>
@stop

@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">Activation email has been sent</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    <p>
                        Please check your email : <b>{{request()->get('email')}}</b> then click activation button.
                    </p>
                    <p>
                        <small>Check your spam box when no email's found.</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop

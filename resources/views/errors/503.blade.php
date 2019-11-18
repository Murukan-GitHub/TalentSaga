@extends('frontend.layout.blank')

@section('content')
    <div class="site-main-inner">
        <section class="section section--white">
            <div class="container">
                <h2 class="fancy-heading">{{ trans('notification.servererrortitle') }}</h2>

                <div class="text-center">
                    {{ trans('notification.servererrorcontent') }} 
                </div>
            </div>
        </section>
    </div>
@endsection

@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <div class="contact">
            <div class="contact-map" data-coords='{ "lat": {{ (empty(settings('latitude')) ? "-6.194288191779069" : settings('latitude')) }}, "lng": {{ (empty(settings('longitude')) ? "106.92647360610965" : settings('longitude'))  }} }'></div>

            <div class="container">
                <div class="contact-card">
                    {!!Form::open(['class' => 'contact-form', 'data-validate'])!!}
                        <fieldset>
                            <legend class="h3">{{ trans('label.contactus.sendmessage') }}</legend>

                            <div class="block-half">
                                <label class="sr-only" for="inputContactName">{{ trans('label.contactus.name') }}</label>
                                <input name="sender_name" class="form-input" id="inputContactName" type="text" placeholder="{{ trans('label.contactus.name') }}" required>
                            </div>

                            <div class="block-half">
                                <label class="sr-only" for="inputContactEmail">{{ trans('label.contactus.email') }}</label>
                                <input name="sender_email" class="form-input" id="inputContactEmail" type="email" placeholder="{{ trans('label.contactus.email') }}" required>
                            </div>

                            <div class="block-half">
                                <label class="sr-only" for="inputContactSubject">{{ trans('label.contactus.selectsubject') }}</label>

                                <input name="category" class="form-input" id="contact_category" type="text" placeholder="{{ trans('label.contactus.selectsubject') }}" required>
                            </div>

                            <div class="block-half">
                                <label class="sr-only" for="inputContactMessage">{{ trans('label.contactus.message') }}</label>
                                <textarea name="content" class="form-input" id="inputContactMessage" rows="2" placeholder="{{ trans('label.contactus.message') }}" required></textarea>
                            </div>

                            <button class="btn btn--tosca">{{ trans('label.contactus.submit') }}</button>
                        </fieldset>
                    {!! Form::close() !!}

                    <section class="contact-address">
                        <h2 class="h3">{{ trans('label.contactus.contactus') }}</h2>

                        <address>
                            <p>
                                {!! nl2br(trim(settings('address', 'Sassenburger Weg 4a'))) !!}<br>
                                {!! nl2br(trim(settings('address2', '22147 Hamburg Germany'))) !!}<br>
                                <br>
                                {{ settings('phone', '+49(0)40 6753 2173') }}<br>
                                {{ settings('email', 'as@ansaworks.com') }}
                            </p>
                        </address>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

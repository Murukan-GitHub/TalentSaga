@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">FAQ</h2>

            <dl class="faq js-accordion">
                 @foreach($faqs as $faq)
                    <dt class="js-accordion-title">{{ $faq->question__trans }}</dt>
                    <dd class="js-accordion-content">
                        {!! htmlspecialchars_decode($faq->answer__trans) !!}
                    </dd>
                @endforeach
            </dl>
        </div>
    </div>
@endsection

@extends('frontend.layout.base')

@section('content')

<div class="site-main-inner-padded">
    <div class="container">
        <h2 class="fancy-heading">{{ trans('label.home.blog') }}</h2>

        @if($contents && count($contents) > 0)
            <ul class="success-stories-list list-nostyle">
            @foreach ($contents as $content)
                <li class="success-stories-list-item">
                    <article class="success-story-news">
                        <div style="max-height: 160px; overflow-y: hidden; margin: 0px; padding: 0px;">
                            <img src="{{ ($content->image ? $content->image_small_banner : asset('frontend/assets/img/success-story-img-thumb.jpg')) }}" alt="{{ $content->title }}">
                        </div>

                        <div class="success-story-news-desc">
                            <h3 class="success-story-news-title">{{ $content->title }}<br>
                                <span style="font-size: 9pt; font-style: italic; font-weight: normal;">{{ $content->created_at->format('F d, Y') }}</span></h3>

                            <a href="{{ route('frontend.home.content.'.($routeNode), ['slug' => $content->slug]) }}">Read more</a>
                        </div>
                    </article>
                </li>
            @endforeach
            </ul>
            @include('frontend.partials.pagination', ['paginator' => $contents])
        @else
            <center>
                <br><br>
                <i>( no content available )</i>
                <br><br>
            </center>
        @endif
    </div>
</div>
@endsection

@extends('frontend.layout.base')

@section('content')

    @if($userStories && count($userStories) > 0)
        <div class="site-main-inner-padded">
            <div class="container">
                <h2 class="fancy-heading">Success stories</h2>

                <ul class="success-stories-list list-nostyle">
                @foreach ($userStories as $userStory)
                    <li class="success-stories-list-item">
                        <article class="success-story-news">
                            <img src="{{ ($userStory->cover_image ? $userStory->cover_image_medium_cover : asset('frontend/assets/img/success-story-img-thumb.jpg')) }}" alt="">

                            <div class="success-story-news-desc">
                                <h3 class="success-story-news-title">{{ $userStory->title }}</h3>

                                <p class="success-story-news-summary">{{ $userStory->highlight }}</p>

                                <a href="{{ route('user.story.detail', ['id' => $userStory->id]) }}">Read more</a>
                            </div>
                        </article>
                    </li>
                @endforeach
                </ul>

                @include('frontend.partials.pagination', ['paginator' => $userStories])
            </div>
        </div>
    @else
        <div class="site-main-inner">
            <div class="container">
                <figure class="category-header block">
                    <img class="category-header-img" src="assets/img/category-header-image.jpg" alt="">
                    <figure class="category-header-info">
                        <h2 class="category-header-heading">Success Stories</h2>
                    </figure>
                </figure>

                <div class="about__content">
                    No User Stories Yet !
                </div>
            </div>
        </div>
    @endif
    
@endsection
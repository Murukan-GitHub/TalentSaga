@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <section class="section section--white">
            <div class="container">
                <h2 class="fancy-heading">{{ $content->title__trans }}</h2>

            @if($content->image)
                <div style="width: 100%;">
                    <img style="width: 100%;" src="{{ ($content->image ? $content->image_large_cover : asset('frontend/assets/img/success-story-img-thumb.jpg')) }}" alt="{{ $content->title__trans }}">
                </div>
                <br>
            @endif

                <div class="">
                    <small><span class="fa fa-lg fa-fw fa-clock-o"></span> <i>{{ $content->created_at->format('F d, Y') }} by Admin Talentsaga</i></small>
                    <br><br>
                    {!! htmlspecialchars_decode($content->content__trans) !!}
                </div>
            </div>
        </section>

    @if(isset($contentMedia) && is_array($contentMedia) && isset($contentMedia['title']) && isset($contentMedia['media']) && is_array($contentMedia['media']) && !empty($contentMedia['media']))
        <section class="section">
            <div class="container">
                <h2 class="fancy-heading">{{ $contentMedia['title'] }}</h2>

                <ul class="team-list list-nostyle">
                    @foreach($contentMedia['media'] as $media)
                    <li class="team-list-item">
                        <figure class="team">
                            <img src="{{ $media['thumbnail_image_url'] }}" alt="">
                            <figcaption>
                                <h3>{{ $media['title'] }}</h3>
                                <small>{{ $media['subtitle'] }}</small>
                            </figcaption>
                        </figure>
                    </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </div>
    @endif
@endsection

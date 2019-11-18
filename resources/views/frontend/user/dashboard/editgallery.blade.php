@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <div class="container">
            <div class="category-layout">
                <div class="category-layout-filter-trigger">
                    <br>
                    <button class="btn btn--tosca">Menu</button>
                </div>
                <div class="category-filter">
                    @include('frontend.partials.dashboard.menu')
                </div>
                <div class="category-content">
                    <h2><a href="{{ route('user.dashboard.gallery') }}">Photo &amp; Video</a> | {{ $userGallery->title }}</h2>

                    @if($userGallery->type && $userGallery->type == \App\Models\UserGallery::TYPE_IMAGE)

                        {!! Form::open(['url' => route('user.dashboard.gallery.edit.save', ['id' => $userGallery->id]), 'files' => true]) !!}

                            <figure class="floating-btn-container js-image-crop" style="display: inline-block;">
                                <img class="js-image-crop-preview" src="{{ $userGallery->image_media_url_medium_square }}" alt="{{ $userGallery->title }}">
                                <figcaption>
                                    <label class="floating-btn is-absolute" for="inputPhoto">
                                        <span class="fa fa-fw fa-pencil"></span>
                                    </label>
                                    <input class="sr-only js-image-crop-input" id="inputPhoto" type="file" name="raw_image_media_url" accept="image/png, image/jpg, image/jpeg">
                                    <input name="image_media_url" class="js-image-crop-hidden-input" type="hidden">
                                </figcaption>
                            </figure>

                            <div class="v-center block">
                                <small>Order</small>
                                <div>
                                    <div class="counter js-counter">
                                        <button class="js-counter-dec" type="button">-</button>
                                        <input name="position_order" class="js-counter-input" type="number" value="{{ $userGallery->position_order ? $userGallery->position_order : 0 }}" min="0" max="100" readonly>
                                        <button class="js-counter-inc" type="button">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="block-half">
                                <label for="inputTitle">Title</label>
                                <input class="form-input" id="inputTitle" type="text" name="title" value="{{ $userGallery->title }}" required>
                            </div>

                            <div>
                                <button class="btn btn--tosca">Update Photo</button>
                            </div>
                        </form>
                    @else
                        <figure>
                        <a href="{{ $userGallery->external_media_url }}" data-fancybox="gallery">
                            <img src="https://img.youtube.com/vi/{{ $userGallery->youtube_code }}/0.jpg" alt="">
                        </a>
                        </figure>

                        {!! Form::open(['url' => route('user.dashboard.gallery.edit.save', ['id' => $userGallery->id]), 'files' => true]) !!}

                            <div class="v-center block">
                                <small>Order</small>
                                <div>
                                    <div class="counter js-counter">
                                        <button class="js-counter-dec" type="button">-</button>
                                        <input name="position_order" class="js-counter-input" type="number" value="{{ $userGallery->position_order ? $userGallery->position_order : 0  }}" min="0" max="100" readonly>
                                        <button class="js-counter-inc" type="button">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="block-half">
                                <label for="inputTitle">Title</label>
                                <input class="form-input" id="inputTitle" type="text" name="title" value="{{ $userGallery->title }}" required>
                            </div>

                            <div class="block-half">
                                <label for="inputPhoto">Change Video URL</label>
                                <input class="form-input" type="text" name="external_media_url" placeholder="video url..." value="{{ $userGallery->external_media_url }}" required>
                            </div>

                            <button class="btn btn--tosca">Update Video</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('custom-modal')
    <div class="image-crop-modal">
        <div class="image-crop-modal-dialog">
            <button class="image-crop-modal-close">&times;</button>
            <div class="image-crop-field"></div>

            <div class="text-center">
                <button class="image-crop-modal-set-btn btn btn--tosca">Crop</button>
            </div>
        </div>
    </div>
@stop

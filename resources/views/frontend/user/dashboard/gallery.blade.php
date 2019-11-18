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
                    <h2>Photo &amp; Video</h2>

                    <div class="bzg">
                        <div class="bzg_c" data-col="m6">
                            <div class="bzg">
                                <div class="bzg_c" data-col="s6">
                                    <div class="v-center block">
                                    <form method="get" id="filterForm">
                                        <small for="sortBy">Sort by</small>
                                        <select class="form-input form-input--small" id="sortBy" name="order" style="width: 120px;" onchange="$('#filterForm').submit();">
                                            <option value="position" @if(request()->get('order') == 'position') selected @endif>Position Order</option>
                                            <option value="newest" @if(request()->get('order') == 'newest') selected @endif>Newest First</option>
                                            <option value="oldest" @if(request()->get('order') == 'oldest') selected @endif>Oldest First</option>
                                        </select>
                                    </form>
                                    </div>
                                </div>
                                <div class="bzg_c" data-col="s6">
                                    <div class="v-center block">
                                    <form method="get" id="categoryForm">
                                        <small for="categoryBy">Category</small>
                                        <select class="form-input form-input--small" id="categoryBy" name="type" style="width: 120px;" onchange="$('#categoryForm').submit();">
                                            <option value="">All</option>
                                            <option value="image" @if(request()->get('type') == 'image') selected @endif>Photo Only</option>
                                            <option value="video" @if(request()->get('type') == 'video') selected @endif>Video Only</option>
                                        </select>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bzg_c text-right block" data-col="m6">
                            <button class="btn btn--sm btn--tosca block-half js-upload-photos-modal-trigger">Add new photo</button>
                            <button class="btn btn--sm btn--tosca block-half" data-modal="#addVideo">Add new video</button>
                            <template id="addVideo">
                                {!! Form::open(['route' => 'user.dashboard.gallery.save', 'files' => true, 'data-validate']) !!}
                                    <fieldset>
                                        <legend>New Video</legend>

                                        <div class="block-half">
                                            <input type="hidden" name="type" value="video">
                                            <input type="hidden" name="status" value="published">
                                            <input type="hidden" name="title" value="Video">
                                            <input class="form-input" type="text" name="external_media_url" placeholder="video url..." required>
                                        </div>
                                    </fieldset>

                                    <button class="btn btn--tosca">Submit</button>
                                </form>
                            </template>
                        </div>
                    </div>

                    @if($userGalleries)
                    <div class="images-grid">
                        @foreach($userGalleries as $key=>$userGallery)
                        @if($userGallery->type && $userGallery->type == \App\Models\UserGallery::TYPE_IMAGE)
                            <div>
                                <div class="images-grid-item">
                                    <a class="images-grid-anchor" href="{{ $userGallery->image_media_url_medium_cover }}" data-fancybox="gallery">
                                        <img class="images-grid-item-img" src="{{ $userGallery->image_media_url_small_square }}" alt="{{ $userGallery->title }}">
                                        <div class="images-grid-item-info text-ellipsis">{{ $userGallery->title }}</div>
                                    </a>

                                    <div class="images-grid-menus">
                                        <a class="images-grid-menu" href="{{ route('user.dashboard.gallery.edit', ['id' => $userGallery->id]) }}">
                                            <span class="fa fa-fw fa-pencil"></span>
                                        </a>
                                        {!! Form::open(['url' => route('user.dashboard.gallery.delete', ['id' => $userGallery->id]), 'files' => false]) !!}
                                            <button class="images-grid-menu" data-ts-confirm="Are you sure to delete this photo?" type="submit"><span class="fa fa-fw fa-trash"></span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div>
                                <div class="images-grid-item">
                                    <a class="images-grid-anchor is-video" href="{{ $userGallery->external_media_url }}" data-fancybox="gallery">
                                        <img class="images-grid-item-img" src="https://img.youtube.com/vi/{{ $userGallery->youtube_code }}/0.jpg" alt="">
                                        <div class="images-grid-item-info text-ellipsis">{{ $userGallery->title }}</div>
                                    </a>

                                    <div class="images-grid-menus">
                                        <a class="images-grid-menu" href="{{ route('user.dashboard.gallery.edit', ['id' => $userGallery->id]) }}">
                                            <span class="fa fa-fw fa-pencil"></span>
                                        </a>
                                        {!! Form::open(['url' => route('user.dashboard.gallery.delete', ['id' => $userGallery->id]), 'files' => false]) !!}
                                            <button class="images-grid-menu" data-ts-confirm="Are you sure to delete this video?" type="submit"><span class="fa fa-fw fa-trash"></span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endforeach
                    </div>
                    @else
                        <div class="text-center">
                                No Gallery Yet
                        </div>
                    @endif

                    @if($userGalleries)
                        @include('frontend.partials.pagination', ['paginator' => $userGalleries])
                    @endif

                </div>
            </div>
        </div>
    </div>
@stop

@section('custom-modal')
    <div class="upload-photos-modal" id="uploadPhotosModal">
        <div class="upload-photos-modal-dialog">
            <button class="upload-photos-modal-dialog-close">&times;</button>
            <form method="post" class="upload-photos-dropzone-form" action="{{ route('user.dashboard.gallery.save') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="type" value="image">
                <input type="hidden" name="status" value="published">
                <div class="upload-photos-dropzone" id="uploadPhotosDropZone">
                    <input class="sr-only" id="uploadPhotosInput" type="file" accept="image/png, image/jpg, image/jpeg" multiple>
                    <label class="upload-photos-dropzone-label" for="uploadPhotosInput">Click or drag pictures here to upload</label>

                    <div class="upload-photos-dropzone-previews" id="uploadPhotosDropZonePreview"></div>
                </div>

                <div class="text-center hidden" id="uploadPhotosCta">
                    <br>
                    <button class="btn btn--tosca">Upload Images</button>
                </div>
            </form>
        </div>
    </div>
@stop


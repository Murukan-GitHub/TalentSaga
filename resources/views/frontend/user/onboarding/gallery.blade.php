@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="sr-only">Become an artist</h2>

            @include('frontend.partials.dashboard.becomeartistmenu')

            {!! Form::open(['route' => 'user.onboarding.gallery.save', 'files' => true, 'class' => 'form-baa', 'data-validate', 'ng-controller' => 'UploadPhotosController']) !!}
                <fieldset class="block-half">
                    <legend class="text-center h3 text-caps">Photos</legend>

                    <div class="upload-photos" ng-class="{ 'is-active': showUploader }">
                    <?php
                        $nbGallery = 0;
                    ?>
                    @if($userGalleries)
                        @foreach($userGalleries as $key=>$userGallery)
                        @if($userGallery->type == \App\Models\UserGallery::TYPE_IMAGE)
                            <label class="upload-photo" for="inputPhoto{{ 100+$key }}">
                                <img class="upload-photo-input" id="inputPhoto{{ 100+$key }}" src="{{ $userGallery->image_media_url_medium_square }}">
                            </label>
                            <?php
                                $nbGallery++;
                            ?>
                        @endif
                        @endforeach
                    @endif
                        <label class="upload-photo" for="inputPhoto@{{$index}}" data-filename="" ng-repeat="photoToUpload in photosToUpload" on-finish-render="attachInputListener">
                            <input class="upload-photo-input sr-only" id="inputPhoto@{{$index}}" type="file" name="photos[]" accept="image/*" ng-required="$index < {{ $nbGallery == 0 ? 1 : 0 }}">
                            <span class="fa fa-2x fa-fw fa-picture-o"></span>
                            <span>Click to add your photo</span>
                        </label>
                        <button class="upload-photo-add" type="button" ng-click="addInputFile()">
                            <span class="fa fa-2x fa-fw fa-plus-circle"></span>
                            Add more photo
                        </button>
                    </div>
                </fieldset>

                <fieldset class="block-half">
                    <legend class="text-center h3 text-caps">Videos</legend>

                    <div class="bzg">
                        <div class="bzg_c" data-col="l9">
                        @if($userGalleries)
                            @foreach($userGalleries as $key=>$userGallery)
                            @if($userGallery->type == \App\Models\UserGallery::TYPE_VIDEO)
                                <div class="block-half">
                                    <input class="form-input" type="text" placeholder="Video URL e.g. YouTube or Vimeo" value="{{ $userGallery->external_media_url }}" readonly>
                                </div>
                            @endif
                            @endforeach
                        @endif
                            <div class="block-half" ng-repeat="videoUrl in videoUrls">
                                <input class="form-input" type="text" placeholder="Video URL e.g. YouTube or Vimeo" name="video[]">
                            </div>
                        </div>
                        <div class="bzg_c" data-col="l3">
                            <button class="btn" type="button" ng-click="addNewURLVideo()">+ Add url</button>
                        </div>
                    </div>
                </fieldset>

                <div class="form-baa-actions">
                    <a class="btn btn--tosca btn--outline" href="{{ route('user.onboarding.portofolio') }}">{{ trans('label.back') }}</a>
                    <button class="btn btn--gray" name="saveasdraft" value="true" type="submit">{{ trans('label.save_draft') }}</button>
                    <button class="btn btn--tosca" type="submit">{{ trans('label.next') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- gallery -->
@stop

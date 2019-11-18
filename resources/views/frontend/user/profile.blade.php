@extends('frontend.layout.base')

@section('content')
    <div class="talent-profile">
        <figure class="talent-profile-main-image-wrapper">
            <img class="talent-profile-main-image" src="{{ $user->background ? $user->background_large_cover : ($user->picture ? $user->picture_large_cover : ($user->firstImageGallery() ? $user->firstImageGallery()->image_media_url_large_cover : asset('frontend/assets/img/talent-detail-main-image.jpg') ) ) }}" alt="">
        </figure>

        <div class="container">
            <section class="talent-profile-main-info">
                <div class="rate rate--display">
                    <span class="rate-label">{{ $user->number_of_review }}</span>

                    <input class="sr-only" type="radio" value="5" @if($user->actual_rating >= 5) checked @endif>
                    <label><span class="fa fa-fw fa-star-o"></span></label>

                    <input class="sr-only" type="radio" value="4" @if($user->actual_rating >= 4) checked @endif>
                    <label><span class="fa fa-fw fa-star-o"></span></label>

                    <input class="sr-only" type="radio" value="3" @if($user->actual_rating >= 3) checked @endif>
                    <label><span class="fa fa-fw fa-star-o"></span></label>

                    <input class="sr-only" type="radio" value="2" @if($user->actual_rating >= 2) checked @endif>
                    <label><span class="fa fa-fw fa-star-o"></span></label>

                    <input class="sr-only" type="radio" value="1" @if($user->actual_rating >= 1) checked @endif>
                    <label><span class="fa fa-fw fa-star-o"></span></label>
                </div>
                <h2 class="talent-profile-name">{{ $user->full_name }}</h2>
                <h3 class="talent-profile-job">{{ $userProfile ? $userProfile->talent_profession : 'Artist' }}</h3>

                <div class="v-center block">
                    <span>{{ $user->profile ? ucwords($user->profile->gender) : '-' }}, {{ $user->age }}</span>
                    <span><i class="fa fa-fw fa-map-pin"></i> {{ $userProfile ? $userProfile->available_cities_name : 'Contact For Availability' }}</span>
                    <span class="sr-only">H: {{ $userProfile ? $userProfile->height : '-' }}cm</span>
                    <span class="sr-only">W: {{ $userProfile ? $userProfile->weight : '-' }}Kg</span>
                </div>

                <hr>

                <p>{!! $userProfile ? nl2br($userProfile->talent_description) : "" !!}</p>

                <p>
                @if($userProfile)
                    @if($userProfile->facebook_page)
                        <a href="{{ $userProfile->facebook_page }}">
                            <span class="fa fa-fw fa-facebook-official"></span>
                        </a>
                    @endif
                    @if($userProfile->twitter_page)
                        <a href="{{ $userProfile->twitter_page }}">
                            <span class="fa fa-fw fa-twitter"></span>
                        </a>
                    @endif
                    @if($userProfile->youtube_page)
                        <a href="{{ $userProfile->youtube_page }}">
                            <span class="fa fa-fw fa-youtube"></span>
                        </a>
                    @endif
                    @if($userProfile->instagram_page)
                        <a href="{{ $userProfile->instagram_page }}">
                            <span class="fa fa-fw fa-instagram"></span>
                        </a>
                    @endif
                @endif
                </p>
                <!--
                <a href="#">
                    <span class="fa fa-fw fa-exclamation-triangle"></span>
                    <small>Report this talent</small>
                </a>
                -->
            </section>

            <section class="talent-profile-description">
                <figure>
                    <img class="talent-profile-avatar circle" src="{{ $user->picture ? $user->picture_small_square : 'http://placehold.it/70x70' }}">
                </figure>
                
                <h3 class="h2">
                @if(app()->getLocale() == 'de')
                    <span class="sr-only">Fee: </span><b>{{ $userProfile && !$userProfile->contact_for_price ? number_format($userProfile->price_estimation, 2, ',', '.') . ($userProfile->currency ? ' ' . $userProfile->currency->code : '') . ' / ' . str_replace('ly', '', ($userProfile->pricing_metric ? $userProfile->pricing_metric : 'hourly')) : 'Call Me for Price' }}</b>
                @else
                    <span class="sr-only">Fee: </span><b>{{ $userProfile && !$userProfile->contact_for_price ? number_format($userProfile->price_estimation, 2, '.', ',') . ' ' . ($userProfile->currency ? $userProfile->currency->code. ' ' : '') . ' / ' . str_replace('ly', '', ($userProfile->pricing_metric ? $userProfile->pricing_metric : 'hourly')) : 'Call Me for Price' }}</b>
                @endif
                </h3>

            @if(!auth()->check() || (auth()->check() && auth()->user()->id != $user->id))
                @if(!auth()->check())
                    <a class="btn btn--tosca btn--outline block" href="{{ route('sessions.login') }}">Contact me</a>
                    <br>
                @else
                    <a class="btn btn--tosca btn--outline block" href="#" data-modal="#templateContact">Contact me</a>
                    <template id="templateContact">
                        <h2>Start Enquiry</h2>

                        {!! Form::open(['route' => 'user.booking.create.save', 'class' => 'form-baa', 'data-validate']) !!}
                            <div class="bzg">
                                <div class="bzg_c" data-col="l6">
                                    <div class="block">
                                        <label for="eventLocation">Where is your event</label>
                                        <input class="form-input" id="eventLocation" type="text" name="location" required>
                                    </div>
                                </div>
                                <div class="bzg_c" data-col="l6">
                                    <div class="block">
                                        <span>When is your event</span>
                                        <div class="bzg">
                                            <div class="bzg_c" data-col="m6">
                                                <input class="form-input" id="eventTimeStart" type="text" placeholder="Start date" data-datepicker data-start-date-for="#eventTimeEnd" data-min-date="TODAY" name="event_date_start" required>
                                            </div>
                                            <div class="bzg_c" data-col="m6">
                                                <input class="form-input" id="eventTimeEnd" type="text" placeholder="End date" data-datepicker name="event_date_end" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bzg">
                                <div class="bzg_c" data-col="m6">
                                    <div class="block">
                                        <label for="eventStartTime">What time will it start</label>
                                        <select class="form-input" id="eventStartTime" name="event_start_time">
                                        @for($i=1;$i<24;$i++)
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:00 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:00</option>
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:15 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:15</option>
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:30 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:30</option>
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:45 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:45</option>
                                        @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="bzg_c" data-col="m6">
                                    <div class="block">
                                        <label for="eventEndTime">What time will it end</label>
                                        <select class="form-input" id="eventEndTime" name="event_end_time">
                                        @for($i=1;$i<24;$i++)
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:00 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:00</option>
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:15 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:15</option>
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:30 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:30</option>
                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:45 am">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}:45</option>
                                        @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="block">
                                <label for="eventTitle">The event title</label>
                                <input type="text" class="form-input" id="eventTitle" name="event_title" required>
                            </div>

                            <div class="block">
                                <label for="eventDetail">The event details</label>
                                <textarea class="form-input" id="eventDetail" rows="4" name="event_detail" required></textarea>
                            </div>

                            <div class="bzg">
                                <div class="bzg_c" data-col="m6">
                                    <div class="block">
                                        <label for="employerEmail">Email address</label>
                                        <input class="form-input" id="employerEmail" type="text" name="email" value="{{ auth()->user()->email }}" required>
                                    </div>
                                </div>
                                <div class="bzg_c" data-col="m6">
                                    <div class="block">
                                        <label for="employerTel">Telephone</label>
                                        <input class="form-input" id="employerTel" type="text" name="phone_number" value="{{ auth()->user()->phone_number }}" required>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="talent_user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn--tosca">Send</button>
                        </form>
                    </template>
                    <br>
                @endif
            @endif
                <small>Price Includes :</small>
                <br>
                <?php
                    $concat = [];
                    foreach($user->priceInclusions as $inclusion) {
                        $concat[] = $inclusion->name;
                    }
                ?>
                {{ implode(', ', $concat) }}
            </section>

            <section class="talent-profile-gallery">
                <h3 class="sr-only">Gallery</h3>

                <figure class="talent-profile-images">
                @foreach($user->galleries as $gallery)
                    @if($gallery->type == App\Models\UserGallery::TYPE_IMAGE)
                        <a href="{{ $gallery->image_media_url_large_cover }}" data-fancybox="gallery">
                            <img src="{{ $gallery->image_media_url_small_cover }}" alt="{{ $gallery->title }}" data-fancybox="gallery">
                        </a>
                    @elseif($gallery->type == App\Models\UserGallery::TYPE_VIDEO)
                        <a href="{{ $gallery->external_media_url }}" data-fancybox="gallery">
                            <img src="https://img.youtube.com/vi/{{ $gallery->youtube_code }}/0.jpg" alt="">
                        </a>
                    @endif
                @endforeach
                </figure>
            </section>
        </div>
    </div>
    <div class="talent-profile-portofolio" id="portofolio">
        <div class="container">
            <div class="ui-tab js-tab">
                <div class="ui-tab-anchors">
                    <a class="ui-tab-anchor js-tab-anchor is-active" href="#tab1">Portofolio</a>
                    <a class="ui-tab-anchor js-tab-anchor" href="#tab2">Review</a>
                </div>

                <div class="ui-tab-panels">
                    <div class="ui-tab-panel js-tab-panel is-active" id="tab1">
                        @if($user->portofolios && count($user->portofolios) > 0)
                            <ul class="portofolio-list list-nostyle">
                            @foreach($user->portofolios as $portofolio)
                                <li>
                                    <article class="portofolio">
                                        <time class="portofolio-time">{{ $portofolio->event_date ? $portofolio->event_date->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'm/d/Y') : '' }}</time>
                                        <h3 class="portofolio-title">{{ $portofolio->event_name }}</h3>
                                        <p class="portofolio-desc">{{ $portofolio->description }}</p>
                                    @if($portofolio->url && filter_var($portofolio->url, FILTER_VALIDATE_URL) !== false)
                                        <a href="{{ $portofolio->url }}">
                                            <span class="fa fa-fw fa-link"></span>
                                        </a>
                                    @else
                                        <a href="#">&nbsp;</a>
                                    @endif
                                    @if($portofolio->youtube_url && filter_var($portofolio->youtube_url, FILTER_VALIDATE_URL) !== false)
                                        <a href="{{ $portofolio->youtube_url }}">
                                            <span class="fa fa-fw fa-youtube-play"></span>
                                        </a>
                                    @endif
                                    </article>
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <center>
                                <br><br>
                                <i>( no portofolio yet )</i>
                                <br><br>
                            </center>
                        @endif
                    <!--
                        <ul class="pagination">
                            <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#"><span class="fa fa-angle-right"></span></a></li>
                        </ul>
                    -->
                    </div>
                    <div class="ui-tab-panel js-tab-panel" id="tab2">
                        @if($user->reviews && count($user->reviews) > 0)
                            <ul class="talent-rates list-nostyle">
                            @foreach($user->reviews as $reviewedBookingAsTalent)
                            @if(!empty($reviewedBookingAsTalent->talent_review))
                                <li>
                                    <div class="talent-rate">
                                        <div class="media block">
                                            <img class="media-img" src="{{ $reviewedBookingAsTalent->user && $reviewedBookingAsTalent->user->picture ? $reviewedBookingAsTalent->user->picture_medium_square : asset('frontend/assets/img/default-avatar.jpg') }}" width="50" alt="">
                                            <div class="media-content">
                                                <b>{{ $reviewedBookingAsTalent->user ? $reviewedBookingAsTalent->user->full_name : 'Anonymous User' }}</b> <br>
                                                <time><small>{{ $reviewedBookingAsTalent->talent_review_date ? $reviewedBookingAsTalent->talent_review_date->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'm/d/Y') : 'Anytime' }}</small></time>
                                            </div>
                                        </div>

                                        <div class="rate rate--display">
                                            <input class="sr-only" type="radio" value="5" {{ $reviewedBookingAsTalent->talent_rate == 5 ? 'checked' : ''}}>
                                            <label><span class="fa fa-fw fa-star-o"></span></label>

                                            <input class="sr-only" type="radio" value="4" {{ $reviewedBookingAsTalent->talent_rate == 4 ? 'checked' : ''}}>
                                            <label><span class="fa fa-fw fa-star-o"></span></label>

                                            <input class="sr-only" type="radio" value="3" {{ $reviewedBookingAsTalent->talent_rate == 3 ? 'checked' : ''}}>
                                            <label><span class="fa fa-fw fa-star-o"></span></label>

                                            <input class="sr-only" type="radio" value="2" {{ $reviewedBookingAsTalent->talent_rate == 2 ? 'checked' : ''}}>
                                            <label><span class="fa fa-fw fa-star-o"></span></label>

                                            <input class="sr-only" type="radio" value="1" {{ $reviewedBookingAsTalent->talent_rate == 1 ? 'checked' : ''}}>
                                            <label><span class="fa fa-fw fa-star-o"></span></label>
                                        </div>

                                        <p>{{ $reviewedBookingAsTalent->talent_review }}</p>
                                    </div>
                                </li>
                            @endif
                            @endforeach
                            </ul>
                        @else
                            <center>
                                <br><br>
                                <i>( no review yet )</i>
                                <br><br>
                            </center>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@extends('frontend.layout.base')

@push('css-style')
<style>
    .additional-item-info {
        width: 100%; 
        text-align: right; 
        margin-top: -20px;
        font-style: italic;
        font-size: 0.7em;
    }
</style>
@endpush

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <div class="bzg">
                <div class="bzg_c" data-col="s7, m6">
                    <h2 class="text-caps">{{ trans('label.menu.booking') }}</h2>
                </div>
                <div class="bzg_c" data-col="s5, m6">
                    <form class="activity-filter-form" method="get" id="filterForm">
                        <label for="selectFilter">Sort by</label>
                        <select class="form-input" id="selectFilter" name="order" onchange="$('#filterForm').submit();">
                            <option value="newest" @if(request()->get('order') == 'newest') selected @endif>Newest Booking First</option>
                            <option value="oldest" @if(request()->get('order') == 'oldest') selected @endif>Oldest Booking First</option>
                            <option value="newestevent" @if(request()->get('order') == 'newestevent') selected @endif>Newest Event First</option>
                            <option value="oldestevent" @if(request()->get('order') == 'oldestevent') selected @endif>Oldest Event First</option>
                        </select>
                    </form>
                </div>
            </div>
            
            @if($bookingList && count($bookingList) > 0)
                <ul class="activity-list list-nostyle">
                    @foreach($bookingList as $booking)
                    <li class="activity-list-item">
                        <div class="activity {{ $booking->status == 'done' ? 'activity--done' : '' }}">
                            {!! Form::open(['route' => ['user.booking.update.save', 'bookingId' => $booking->id], 'class' => 'activity-form']) !!}
                            @if($booking->status == 'created')
                                <fieldset>
                            @else
                                <fieldset disabled>
                            @endif
                                @if($booking->status == 'created')
                                    <button name="canceled" value="true" type="submit" onClick="return confirm('Are you sure to cancel your booking?');">
                                        <span class="fa fa-fw fa-close"></span>
                                        Cancel
                                    </button>
                                @elseif($booking->status == 'rejected')
                                    <button name="rejected" value="true" type="submit" style="background-color: #e74c3c; border: 1px solid #e74c3c;">
                                        <span class="fa fa-fw fa-check"></span>
                                        Rejected
                                    </button>
                                @elseif($booking->status == 'canceled')
                                    <button name="canceled" value="true" type="submit" style="background-color: #e74c3c; border: 1px solid #e74c3c;">
                                        <span class="fa fa-fw fa-check"></span>
                                        Canceled
                                    </button>
                                @else
                                    <button>
                                        <span class="fa fa-fw fa-square-o"></span>
                                        {{ ucfirst(strtolower($booking->status)) }}
                                    </button>
                                @endif
                            </fieldset>
                            </form>

                            <h3>Talent Info</h3>
                            <div class="block-half">
                                <span class="fa fa-lg fa-fw fa-user-circle"></span> <a href="{{ route('frontend.user.profile.shortcut', ['user' => $booking->talentUser->username]) }}">{{ $booking->talentUser->full_name }}</a>
                            </div>
                            <div class="block-half">
                                <span class="fa fa-lg fa-fw fa-phone"></span> {{ $booking->talentUser->phone_number }}
                            </div>
                            <div class="block-half">
                                <span class="fa fa-lg fa-fw fa-envelope"></span>
                                <a href="mailto:{{ $booking->talentUser->email }}">{{ $booking->talentUser->email }}</a>
                            </div>
                            <hr>

                            <div class="additional-item-info">submitted {{ $booking->created_at->diffForHumans() }}</div>

                            <h3>{{ $booking->event_title }}</h3>

                            <p>{{ $booking->event_detail }}</p>

                            <div class="block-half text-tosca">
                                <span class="fa fa-lg fa-fw fa-calendar"></span>
                                {{ ($booking->event_date_start ? $booking->event_date_start->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : 'N/A') . ($booking->event_date_end && ($booking->event_date_start->format('d F Y') != $booking->event_date_end->format('d F Y')) ? " - " . $booking->event_date_end->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : '') }} &nbsp;&nbsp;<small>{{ $booking->event_date_start ? "(" . $booking->event_date_start->diffForHumans() . ")" : "" }}</small>
                            </div>
                            <div class="block-half text-tosca">
                                <span class="fa fa-lg fa-fw fa-clock-o"></span>
                                {{ $booking->event_start_time }} - {{ $booking->event_end_time }} &nbsp;&nbsp;<small>local time @if($duration = $booking->time_duration) (&plusmn; {{$duration}}) @endif </small>
                            </div>
                            <div class="block text-tosca">
                                <span class="fa fa-lg fa-fw fa-map-marker"></span>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($booking->location) }}" target="_blank">{{ $booking->location }}</a>
                            </div>

                            @if($booking->status == 'done')
                            <button class="activity-review-btn btn btn--tosca" data-modal="#modalReview{{ $booking->id }}">{{ $booking->talent_rate ? 'Update' : 'Write' }} review</button>
                            <template id="modalReview{{ $booking->id }}">
                                {!! Form::open(['route' => ['user.booking.review.save', 'bookingId' => $booking->id], 'class' => 'form-baa', 'data-validate']) !!}
                                    <div class="rate">
                                        <span class="rate-label">Rate it</span>

                                        <input class="sr-only" id="rate{{ $booking->id }}-5" type="radio" name="talent_rate" value="5" {{ $booking->talent_rate == 5 ? 'checked' : '' }}>
                                        <label for="rate{{ $booking->id }}-5"><span class="fa fa-fw fa-star-o"></span></label>

                                        <input class="sr-only" id="rate{{ $booking->id }}-4" type="radio" name="talent_rate" value="4" {{ $booking->talent_rate == 4 ? 'checked' : '' }}>
                                        <label for="rate{{ $booking->id }}-4"><span class="fa fa-fw fa-star-o"></span></label>

                                        <input class="sr-only" id="rate{{ $booking->id }}-3" type="radio" name="talent_rate" value="3" {{ $booking->talent_rate == 3 ? 'checked' : '' }}>
                                        <label for="rate{{ $booking->id }}-3"><span class="fa fa-fw fa-star-o"></span></label>

                                        <input class="sr-only" id="rate{{ $booking->id }}-2" type="radio" name="talent_rate" value="2" {{ $booking->talent_rate == 2 ? 'checked' : '' }}>
                                        <label for="rate{{ $booking->id }}-2"><span class="fa fa-fw fa-star-o"></span></label>

                                        <input class="sr-only" id="rate{{ $booking->id }}-1" type="radio" name="talent_rate" value="1" {{ $booking->talent_rate == 1 ? 'checked' : '' }}>
                                        <label for="rate{{ $booking->id }}-1"><span class="fa fa-fw fa-star-o"></span></label>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputReviewMsg{{ $booking->id }}">Write your review</label>
                                        <textarea class="form-input" id="inputReviewMsg{{ $booking->id }}" name="talent_review" rows="3" required>{{ $booking->talent_review }}</textarea>
                                    </div>
                                    <button class="btn btn--tosca">{{ $booking->talent_rate ? 'Update' : 'Add' }} Review</button>
                                </form>
                            </template>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>

                @include('frontend.partials.pagination', ['paginator' => $bookingList])
            @else
                <center>
                    <br><br>
                    <i>( no booking yet )</i>
                    <br><br>
                </center>
            @endif
        </div>
    </div>
@stop

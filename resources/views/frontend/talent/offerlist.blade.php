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
                    <h2 class="text-caps">{{ trans('label.menu.jobrequest') }}</h2>
                </div>
                <div class="bzg_c" data-col="s5, m6">
                    <form class="activity-filter-form" method="get" id="filterForm">
                        <label for="selectFilter">Sort by</label>
                        <select class="form-input" id="selectFilter" name="order" onchange="$('#filterForm').submit();">
                            <option value="newest" @if(request()->get('order') == 'newest') selected @endif>Newest Request First</option>
                            <option value="oldest" @if(request()->get('order') == 'oldest') selected @endif>Oldest Request First</option>
                            <option value="newestevent" @if(request()->get('order') == 'newestevent') selected @endif>Newest Event First</option>
                            <option value="oldestevent" @if(request()->get('order') == 'oldestevent') selected @endif>Oldest Event First</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($requestList && count($requestList) > 0)
                <ul class="activity-list list-nostyle">
                    @foreach($requestList as $request)
                    @if($request->user)
                    <li class="activity-list-item">
                        <div class="activity">
                            {!! Form::open(['route' => ['user.booking.update.save', 'bookingId' => $request->id], 'class' => 'activity-form']) !!}
                                @if(in_array($request->status, ['done', 'canceled', 'rejected']))
                                    <fieldset disabled>
                                @else
                                    <fieldset>
                                @endif
                                @if($request->status == 'created')
                                    <button name="approved" value="true" type="submit" onClick="return confirm('Are you sure to approve this job request?');">
                                        <span class="fa fa-fw fa-check"></span>
                                        Approve
                                    </button>
                                    <button name="rejected" value="true" type="submit" onClick="return confirm('Are you sure to reject this job request?');">
                                        <span class="fa fa-fw fa-close"></span>
                                        Reject
                                    </button>
                                @elseif($request->status == 'approved')
                                    <button name="approved" value="true" style="background-color: #009999; border: 1px solid #009999; color: #fff;" disabled>
                                        <span class="fa fa-fw fa-check"></span>
                                        Approved
                                    </button>
                                    <button name="done" value="true" type="submit" onClick="return confirm('Are you sure to mark this job request as done?');">
                                        <span class="fa fa-fw fa-check"></span>
                                        Mark as Done
                                    </button>
                                @elseif($request->status == 'rejected')
                                    <button name="rejected" value="true" type="submit" style="background-color: #e74c3c; border: 1px solid #e74c3c;">
                                        <span class="fa fa-fw fa-check"></span>
                                        Rejected
                                    </button>
                                @elseif($request->status == 'canceled')
                                    <button name="canceled" value="true" type="submit" style="background-color: #e74c3c; border: 1px solid #e74c3c;">
                                        <span class="fa fa-fw fa-check"></span>
                                        Canceled
                                    </button>
                                @else
                                    <button name="done" value="true" type="submit">
                                        <span class="fa fa-fw fa-check"></span>
                                        Done
                                    </button>
                                @endif
                                </fieldset>
                            </form>

                            <h3>Customer Info</h3>
                            <div class="block-half">
                                <span class="fa fa-lg fa-fw fa-user-circle"></span> {{ $request->user->full_name }}
                            </div>
                            <div class="block-half">
                                <span class="fa fa-lg fa-fw fa-phone"></span> {{ $request->phone_number ? $request->phone_number : $request->user->phone_number }}
                            </div>
                            <div class="block-half">
                                <span class="fa fa-lg fa-fw fa-envelope"></span>
                                <a href="mailto:{{ $request->email ? $request->email : $request->user->email }}">{{ $request->email ? $request->email : $request->user->email }}</a>
                            </div>
                            <hr>

                            <div class="additional-item-info">requested {{ $request->created_at->diffForHumans() }}</div>
                            
                            <h3>{{ $request->event_title }}</h3>

                            <p>{{ $request->event_detail }}</p>

                            <div class="block-half text-tosca">
                                <span class="fa fa-lg fa-fw fa-calendar"></span>
                                {{ ($request->event_date_start ? $request->event_date_start->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : 'N/A') . ($request->event_date_end && ($request->event_date_start->format('d F Y') != $request->event_date_end->format('d F Y')) ? " - " . $request->event_date_end->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : '') }} &nbsp;&nbsp;<small>{{ $request->event_date_start ? "(" . $request->event_date_start->diffForHumans() . ")" : "" }}</small>
                            </div>
                            <div class="block-half text-tosca">
                                <span class="fa fa-lg fa-fw fa-clock-o"></span>
                                {{ $request->event_start_time }} - {{ $request->event_end_time }} &nbsp;&nbsp;<small>local time @if($duration = $request->time_duration) (&plusmn; {{$duration}}) @endif </small>
                            </div>
                            <div class="block text-tosca">
                                <span class="fa fa-lg fa-fw fa-map-marker"></span>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($request->location) }}" target="_blank">{{ $request->location }}</a>
                            </div>
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ul>
                
                @include('frontend.partials.pagination', ['paginator' => $requestList])
            @else
                <center>
                    <br><br>
                    <i>( no offer yet )</i>
                    <br><br>
                </center>
            @endif

        </div>
    </div>
@stop

@extends('backend.layouts.base')

@section('content')
<div class="row form">
    <form id="form-filter" action="" class="form-horizontal">
    <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label">From</label>
        <div class="col-md-3">
            <input class="form-control" id="date-from-filter" name="date_from" type="text" dashboard-date-range-start="form-filter" value="{{ $date_from ? $date_from->format('Y-m-d') : '' }}">
        </div>
        <label class="col-md-2 control-label">To</label>
        <div class="col-md-3">
            <input class="form-control" id="date-to-filter" name="date_to" type="text" dashboard-date-range-end="form-filter" value="{{ $date_to ? $date_to->format('Y-m-d') : '' }}">
        </div>
    </div>
    </form>
</div>
<div class="clearfix"></div>


<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green-meadow" href="#">
            <div class="visual">
                <i class="icon-user"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($appActiveUsers, 0,'.',',') }}">0</span></div>
                <div class="desc">{{trans('label.dashboard.active_app_users')}}</div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green-meadow" href="#">
            <div class="visual">
                <i class="icon-user"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($appInactiveUsers, 0,'.',',') }}">0</span></div>
                <div class="desc">{{trans('label.dashboard.inactive_app_users')}}</div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green-meadow" href="#">
            <div class="visual">
                <i class="icon-user"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($appArtistUsers, 0,'.',',') }}">0</span></div>
                <div class="desc">{{trans('label.dashboard.artist_users')}}</div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green-meadow" href="#">
            <div class="visual">
                <i class="icon-user"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($appBannedUsers, 0,'.',',') }}">0</span></div>
                <div class="desc">{{trans('label.dashboard.banned_users')}}</div>
            </div>
        </a>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-star-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($acceptedBookings, 0,'.',',') }}">0</span></div>
                <div class="desc">{{trans('label.dashboard.accepted_booking')}}</div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-star-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($rejectedBookings, 0,'.',',') }}">0</span></div>
                <div class="desc">{{trans('label.dashboard.rejected_booking')}}</div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-star-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($canceledBoookings, 0,'.',',') }}">0</span>
                </div>
                <div class="desc">{{trans('label.dashboard.canceled_booking')}}</div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-star-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ number_format($doneBookings, 0,'.',',') }}">0</span>
                </div>
                <div class="desc">{{trans('label.dashboard.done_booking')}}</div>
            </div>
        </a>
    </div>
</div>
<div class="clearfix"></div>

<div class="block block-section">
    <div class="row">
        <div class="block col-md-12">
            <div class="chartZoom">
                <div id="chartZ-1" class="chartZoom-container" data-chart="{{ route('backend.home.appuser-summary', ['date_from' => $date_from->format('Y-m-d'), 'date_to' => $date_to->format('Y-m-d')]) }}"></div>
                <a href="#charz-preview" class="chartZoom-btn" data-toggle="modal">
                    <span class="btn-icon fa fa-search"></span>
                </a>
            </div>
        </div>
    </div>

    <!-- <div class="cloned" style="height: 500px;"></div> -->
    <div id="charz-preview" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header text-right">
                    <button type="button" class="btn" data-dismiss="modal">
                        <span class="fa fa-close"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="chartModal-container">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('end_script')
<script type="text/javascript">
    (function ($, window) {

        $('#charz-preview').on('shown.bs.modal', function() {
            $(window).resize();
        });

    })(jQuery, window)
</script>
@endpush


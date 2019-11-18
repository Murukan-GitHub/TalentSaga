@extends('backend.layouts.base')

@section('content')
<div class="row">
    <h2><b>{{$user->username}}</b></h2>
</div>
<div id="user-profile-1" class="user-profile row">
    <div class="col-xs-12 col-sm-3 center">
        <div>
            <span class="profile-picture">
                <img id="avatar" class="editable img-responsive" onError="this.onerror=null;this.src='http://upload.wikimedia.org/wikipedia/commons/d/d3/User_Circle.png';" src="{{ $user->picture ? $user->picture_small_square : 'http://upload.wikimedia.org/wikipedia/commons/d/d3/User_Circle.png' }}" />
            </span>
            <div class="space-4"></div>

            <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                <div class="inline position-relative">
                    <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-circle light-green middle"></i>
                        &nbsp;
                        <span class="white">{{$user->name}}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="space-6"></div>
        <br>
        @if (Auth::user()->id===$user->id)
        <a href="{{url('user/update/'.$user->id)}}" class="btn btn-primary">Update My Information</a>
        @endif
    </div>
    <div class="col-xs-12 col-sm-9">
        <table id="user" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td>{{$user->id}}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>{{$user->name}}</td>
                </tr>
                <tr>
                    <td>Birth Date</td>
                    <td>{{ $user->birthdate == "0000-00-00" ? "N/A" : date("d F Y", strtotime($user->birthdate))}}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Address</b></td>
                </tr>
                <tr>
                    <td>&nbsp;Street</td>
                    <td>{{$user->address_street}}</td>
                </tr>
                <tr>
                    <td>&nbsp;City</td>
                    <td>{{$user->address_city}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Province</td>
                    <td>{{$user->address_province}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Country</td>
                    <td>{{$user->address_country}}</td>
                </tr>
                <tr>
                    <td>Newsletter</td>
                    <td>
                        @if ($user->newsletter ===1)
                        <img widht="5%" height="5%" src="{{asset('backend/img/tick1.png')}}" />
                        @else
                        <img widht="5%" height="5%" src="{{asset('backend/img/tick0.png')}}" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{$user->email}}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td>{{$user->phone_number}}</td>
                </tr>
                <tr>
                    <td>Registration Date</td>
                    <td>{{date("d F Y", strtotime($user->registration_date))}}</td>
                </tr>
                <tr>
                    <td>Active</td>
                    <td>
                        @if ($user->is_active ===1)
                        <img widht="5%" height="5%" src="{{asset('backend/img/tick1.png')}}" />
                        @else
                        <img widht="5%" height="5%" src="{{asset('backend/img/tick0.png')}}" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Last Visit</td>
                    <td>{{date("d F Y", strtotime($user->last_visit))}}</td>
                </tr>
            </tbody>
        </table>
    </div><!-- /span -->
</div>
@stop

@section('page_script')
<script>
    $(function() {
        $( "#order" ).datepicker({ dateFormat: 'yy-mm-dd', changeYear: true,changeMonth: true,defaultDate: new Date('1990-01-01')});
    });
</script>
@stop

@extends('backend.layouts.base')

@section('content')
@include('layouts.flaggedusers_nav')
<br>
<div style="padding: 8px; background-color: white; border: solid 1px #cccccc;">
    <a class='btn btn-info btn-sm'><span class='glyphicon glyphicon-flag'></span></a> <i>Unflag User</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a class='btn btn-default btn-sm'><span class='glyphicon glyphicon-user'></span></a> <i>Ban/Deactivate User</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-minus'></span></a> <i>Change Status to Unnecessary</i>
</div>
<br>
<table id="userflag" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
    <thead>
        <tr>
            <td><b>ID</b></td>
            <td><b>Date</b></td>
            <td><b>Flagger</b></td>
            <td><b>Flagged User</b></td>
            <td><b>Flagged User Status</b></td>
            <td><b>Response</b></td>
            <td><b>Menu</b></td>
        </tr>
    </thead>
    <tbody>
        @foreach($userflags as $userflag)
        <?php $flaggedUser = App\SuitCommerce\Models\User::find($userflag->user_id); ?>
        <tr>
            <td>{{$userflag->id}}</td>
            <td>{{date("d F Y G:i:s", strtotime($userflag->created_at))}}</td>
            <td><a href="{{route('backend.user.view',['id'=>$userflag->flagger_id])}}" target="_BLANK">{{App\SuitCommerce\Models\User::find($userflag->flagger_id)->username}}</a></td>
            <td><a href="{{route('backend.user.view',['id'=>$userflag->user_id])}}" target="_BLANK">{{$flaggedUser->username}}</a></td>
            <td>
                @if ($flaggedUser->isActive())
                <img width="5%" height="5%" src="{{asset('backend/img/tick1.png')}}" />
                @else
                <img width="5%" height="5%" src="{{asset('backend/img/tick0.png')}}" />
                @endif
            </td>
            <td>{{App\SuitCommerce\Models\AppConfig::getStatusName("userflag",$userflag->status)}}</td>
            <td>
                <span style="display:inline-block;">
                    {!! Form::open(['route' => ['backend.userflag.delete', $userflag->id], 'method' => 'post']) !!}
                    {!! Form::button('<span class="glyphicon glyphicon-flag"></span>', ['type' => 'submit', 'class' => 'btn btn-info btn-sm', 'onclick' => 'return confirm("Are you sure to unflag flagged user?")', 'title' => 'Unflag']) !!}
                    {!! Form::close() !!}
                </span>
                <span style="display:inline-block;">
                    {!! Form::open(['route' => ['backend.userflag.deactivateuser', $userflag->id], 'method' => 'post']) !!}
                    {!! Form::button('<span class="glyphicon glyphicon-flag"></span>', ['type' => 'submit', 'class' => 'btn btn-default btn-sm', 'onclick' => 'return confirm("Are you sure to ban flagged user?")', 'title' => 'Ban User']) !!}
                    {!! Form::close() !!}
                </span>
                <span style="display:inline-block;">
                    {!! Form::open(['route' => ['backend.userflag.unnecessary', $userflag->id], 'method' => 'post']) !!}
                    {!! Form::button('<span class="glyphicon glyphicon-minus"></span>', ['type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return confirm("Are you sure to ignore this user?")', 'title' => 'Ignore this']) !!}
                    {!! Form::close() !!}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('page_script')
<script>
    $(document).ready(function() {
        $('#userflag').dataTable({
            "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 6 ] }
            ]
        });
    });
</script>
@stop

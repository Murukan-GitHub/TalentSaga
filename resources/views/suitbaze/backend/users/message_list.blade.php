@extends('backend.layouts.base')

@section('content')
    @include('layouts.users_nav')
    <h2>Message List (Sent)</h2>
    <table id="msg" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
      <thead>
          <tr>
            <td><b>receiver_id</b></td>
            <td><b>title</b></td>
            <td><b>content</b></td>
          </tr>
      </thead>
      <tbody>
        @foreach($messages as $message)
          <tr>
            <td>{{$message->receiver_id}}</a></td>
            <td>{{$message->title}}</a></td>
            <td>{{$message->content}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <h2>Message List (Inbox)</h2>
    <table id="msg" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
      <thead>
          <tr>
            <td><b>receiver_id</b></td>
            <td><b>title</b></td>
            <td><b>content</b></td>
          </tr>
      </thead>
      <tbody>
        @foreach($messages2 as $message)
          <tr>
            <td>{{$message->receiver_id}}</a></td>
            <td>{{$message->title}}</a></td>
            <td>{{$message->content}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
@stop

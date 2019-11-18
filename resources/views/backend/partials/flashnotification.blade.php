@if (\Session::has('webNotification'))
    @foreach(\Session::get('webNotification') as $notif)
        @if ($notif['type'] == 'success')
        <div class="alert alert-success alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <span>{{ $notif['message'] }}</span>
        </div>
        @endif

        @if ($info = $notif['type'] == 'notice')
        <div class="alert alert-info alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <span>{{ $notif['message'] }}</span>
        </div>
        @endif

        @if ($warning = $notif['type'] == 'warning')
        <div class="alert alert-warning alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <span>{{ $notif['message'] }}</span>
        </div>
        @endif

        @if ($danger = $notif['type'] == 'error')
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <span>{{ $notif['message'] }}</span>
        </div>
        @endif

    @endforeach
    {!! \Session::forget('webNotification') !!}
@endif
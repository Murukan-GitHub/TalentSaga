@extends('backend.layouts.base')

@section('content')
    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">Site Management</a></li>
            <li><a href="{{ route('backend.content.index') }}">Contents</a></li>
            <li>List</li>
        </ul>
    </nav>
    
    <h1 class="heading">Content List</h1>
    <hr />

    <div class="block text-right">
      {!! nav_menu(route("backend.content.create"), 'Create New', 'fa-plus') !!}
    </div>

    <table id="content" class="table table--zebra" data-enhance-ajax-table="{{ route('backend.content.index.json') . "?_token=" . csrf_token() }}">
      <thead>
          <tr>
            @foreach($content->getBufferedAttributeSettings() as $key=>$val)
              @if( $val['visible'] )
                <td><b>{{ $val['label'] }}</b></td>
              @endif
            @endforeach
            <td><b>Menu</b></td>
          </tr>
      </thead>
    </table>
@stop

@section('page_script')
  <script>
  </script>
@stop

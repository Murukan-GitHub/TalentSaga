@extends('backend.layouts.base')

@section('content')
    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">User Management</a></li>
            <li><a href="{{ route($routeBaseName . '.index') }}">{{ $baseObject->getLabel() }}</a></li>
            <li>List</li>
        </ul>
    </nav>
    
    <h1 class="heading">{{ $baseObject->getLabel() }} List</h1>
    <hr />

    <div class="block text-right">
      @if( Route::has($routeBaseName . '.exportxls') )
        {!! nav_menu(route($routeBaseName . ".exportxls"), 'Export to XLS', 'fa-download') !!}
      @endif
      {!! nav_menu(route($routeBaseName . ".create"), 'Create New', 'fa-plus') !!}
    </div>

    <table id="{{ class_basename($baseObject) }}" class="table table--zebra" data-enhance-ajax-table="{{ route($routeBaseName . '.index.json') . "?_token=" . csrf_token() }}">
      <thead>
          <tr>
            @foreach($baseObject->getBufferedAttributeSettings() as $key=>$val)
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

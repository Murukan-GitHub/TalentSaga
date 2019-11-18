@extends('backend.layouts.base')

@section('content')
    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">User Management</a></li>
            <li><a href="{{ route($routeBaseName . '.index') }}">{{ $baseObject->getLabel() }}</a></li>
            @if( Route::has($routeBaseName . '.show') )
                <li><a href="{{ route($routeBaseName . '.show', ['id'=>$baseObject->id]) }}">{{ $baseObject->getFormattedValue() }}</a></li>
            @endif
            <li>Update</li>
        </ul>
    </nav>
    
    <h1 class="heading">Update {{ $baseObject->getLabel() }}</h1>
    <hr />

    <div class="bzg">
        <div class="bzg_c" data-col="l8">
            {!! Form::model($baseObject, ['files'=> true, 'id'=>class_basename($baseObject) . '_form']) !!}
                @include($viewBaseClosure . '.form')

                <div class="bzg">
                    <div class="bzg_c" data-col="l8" data-offset="l4">
                        <a class="btn btn--red" onClick="return confirm('Your changes will be not saved! Are you sure?');" href="{{ Route::has($routeBaseName . '.show') ? route($routeBaseName . '.show',['id'=>$baseObject->id]) : route($routeDefaultIndex) }}">Cancel</a>
                        &nbsp;
                        <input class="btn btn--blue" onClick="if ($('#inputPassword').val() != $('#inputPasswordConfirm').val()) { alert('Your password and password confirmation not the same!'); return false;  } else { return true; }" type="submit" value="Save"/>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('page_script')
    <script>
    </script>
@stop
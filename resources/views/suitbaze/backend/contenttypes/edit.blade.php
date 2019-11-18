@extends('backend.layouts.base')

@section('content')
    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">Site Management</a></li>
            <li><a href="{{ route('backend.contenttype.index') }}">Content Type</a></li>
            <li>Update</li>
        </ul>
    </nav>
    
    <h1 class="heading">Update Content Type</h1>
    <hr />

    <div class="bzg">
        <div class="bzg_c" data-col="l8">
            {!! Form::model($contentType, ['files'=> true, 'id'=>'contentType_form']) !!}
                @include('backend.contenttypes.form')

                <div class="bzg">
                    <div class="bzg_c" data-col="l8" data-offset="l4">
                        <a class="btn btn--red" onClick="return confirm('Your changes will be not saved! Are you sure?');" href="{{ route('backend.contenttype.index') }}">Cancel</a>
                        &nbsp;
                        <input class="btn btn--blue" type="submit" value="Save"/>
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

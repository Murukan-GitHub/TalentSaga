@extends('backend.layouts.base')

@section('content')
    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">Site Management</a></li>
            <li><a href="{{ route('backend.content.index') }}">Content</a></li>
            <li>Create New</li>
        </ul>
    </nav>
    
    <h1 class="heading">Create New Content</h1>
    <hr />

    <div class="bzg">
        <div class="bzg_c" data-col="l8">
            {!! Form::model($content, ['files'=> true, 'id'=>'content_form']) !!}
                @include('backend.contents.form')

                <div class="bzg">
                    <div class="bzg_c" data-col="l9" data-offset="l3">
                        <a class="btn btn--red" onClick="return confirm('Your changes will be not saved! Are you sure?');" href="{{ route('backend.content.index') }}">Cancel</a>
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
@extends('backend.layouts.base')

@section('content')
@include('layouts.users_nav')
<h2>Create New Message</h2>
<div class="row">
    <div class="col-sm-12">
        {!! Form::model($message, array('class'=>'form-horizontal','id'=>'form_message')) !!}
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Penerima </label>
            <div class="col-sm-9">{!! Form::select('receiver_id', App\SuitCommerce\Models\User::lists('username','id'),'1',array(
                'id'=> 'receiver_id'
                )) !!}
            </div>
            <br><label id="error_receiver_id" class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color:red;">{{$errors->first('title')}}</label>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title </label>
            <div class="col-sm-9">{!! Form::text('title',null,array(
                'id'=> 'title'
                )) !!}
            </div>
            <br><label id="error_title" class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color:red;">{{$errors->first('title')}}</label>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Content </label>
            <div class="col-sm-9">{!! Form::textarea('content',null,array(
                'id'=> 'content'
                )) !!}
            </div>
            <br><label id="error_content" class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color:red;">{{$errors->first('content')}}</label>
        </div>
        <div class="form-group">
            <div class="col-md-offset-3 col-md-9">
                {!! Form::submit('Kirim', array('class'=>'btn btn-info'))}}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@stop

@section('page_script')
<script>
    tinymce.init({
        selector:'textarea',
        plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
        ],
        // ===========================================
        // PUT PLUGIN'S BUTTON on the toolbar
        // ===========================================
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link jbimages",
        file_browser_callback : function elFinderBrowser (field_name, url, type, win) {
            tinymce.activeEditor.windowManager.open({
                file: '{{url('elfinder/tinymce')}}',// use an absolute path!
                title: 'elFinder 2.0',
                width: 900,
                height: 450,
                resizable: 'yes'
            },
            {
                setUrl: function (url) {
                    win.document.getElementById(field_name).value = url;
                }
            });
            return false;
        },
        // ===========================================
        // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
        // ===========================================
        relative_urls: false,
    });
</script>
<script type="text/javascript">
    jQuery( document ).ready( function( $ ) {
        $( '#form_message' ).on( 'submit', function() {
            //.....
            //show some spinner etc to indicate operation in progress
            //.....
            $.post(
                $( this ).prop( 'action' ),
                {
                    "receiver_id": document.getElementById('receiver_id').value,
                    "title": document.getElementById('title').value,
                    "content": document.getElementById('content').value
                },
                function( data ) {
                    if(data['status']=='success'){
                        var url = "{{url('admin/user/messages')}}";
                        window.location.replace(url);
                    }
                    else{
                        var strName,strValue;
                        for(strName in data['msg']){
                            strValue = data['msg'][strName][0];
                            console.log(strValue);
                            document.getElementById("error_".concat(strName)).innerHTML = strValue;
                        }
                    }
                },
                'json'
            );
            //.....
            //do anything else you might want to do
            //.....
            //prevent the form from actually submitting in browser
            return false;
        });
    });
</script>
@stop

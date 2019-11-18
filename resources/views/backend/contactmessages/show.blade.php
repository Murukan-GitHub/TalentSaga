@extends('backend.layouts.base')

@section('content')
<!-- BEGIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN Portlet PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green-sharp">
                    <i class="fa fa-black-tie font-green-sharp"></i>&nbsp;
                    <span class="caption-subject bold uppercase">{{ $baseObject->getFormattedValue() }}</span>
                    <span class="caption-helper">contact message detail...</span>
                </div>
                <div class="actions">
                    @if( Route::has($routeBaseName . '.destroy') )
                    {!! post_nav_menu(route($routeBaseName . '.destroy', ['id' => $baseObject->id]), '', csrf_token(), 'Are you sure?', 'icon-trash', 'btn btn-circle btn-icon-only btn-default') !!}
                    @endif
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN TABLE -->
                <table id="{{ class_basename($baseObject) }}_detail" class="table table-bordered table-striped">
                    <tbody>
                        @foreach($baseObject->getBufferedAttributeSettings() as $key=>$val)
                        @if($key != 'updated_at')
                        <tr>
                            <td> {{ $val['label'] }} </td>
                            <td>{!! $baseObject->renderAttribute($key) !!}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                <!-- END TABLE -->
            </div>
            <div class="portlet-body form">
                {!! Form::model($baseObject, ['files'=> false, 'id'=>'{{ class_basename($baseObject) }}_form']) !!}
                    <div class="form-body">
                        <div class="form-group form-md-line-input" id="message_reply_container">
                            <label class="col-md-2 control-label" for="message_reply">Reply :</label>
                            <div class="col-md-10">
                                <textarea rows='5' data-autosize class='form-control' id='message_reply' name='reply' required placeholder="type your reply ..." {{ $baseObject->reply ? 'readonly' : '' }}>{{ $baseObject->reply }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-10">
                                <br><br>
                                <a onClick="return confirm('Your changes will be not saved! Are you sure?');" href="{{ route($routeBaseName . '.index') }}" class="btn default">Cancel</a>
                                <input type="submit" class="btn blue" value="Save"/>
                            </div>
                        </div>
                    </div>

                {!! Form::close() !!}
                <!-- FOR REPLY MESSAGE -->
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT -->
@stop

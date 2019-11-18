@extends('backend.layouts.base')

@inject('baseConfig', 'App\Config\BaseConfig')

@section('content')
<?php 

$heading = isset($pageId) && isset($baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]) ? $baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]['label'] : $baseObject->getLabel();

$pageTitle = trans($heading);

?>
<!-- BEGIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-haze">
                    <i class="{{$pageIcon or ''}} font-dark"></i>&nbsp;
                    <span class="caption-subject sbold uppercase font-dark">{{ $pageTitle or '' }}</span>
                </div>
                <div class="actions">
                    @if( Route::has($routeBaseName . '.create') )
                    {!! nav_menu(route($routeBaseName . ".create"), '', 'icon-plus', 'btn btn-circle btn-icon-only btn-default', trans('backendnav.create')) !!}
                    @endif
                    @if( Route::has($routeBaseName . '.show') )
                    {!! nav_menu(route($routeBaseName . ".show", ['id'=>$baseObject->id]), '', 'icon-eye', 'btn btn-circle btn-icon-only btn-default', trans('backendnav.show')) !!}
                    @endif
                    @if( Route::has($routeBaseName . '.destroy') )
                    {!! post_nav_menu(route($routeBaseName . '.destroy', ['id' => $baseObject->id]), '', csrf_token(), 'Are you sure?', 'icon-trash', 'btn btn-circle btn-icon-only btn-default', trans('backendnav.delete')) !!}
                    @endif
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" title="Fullscreen" href="javascript:;" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($baseObject, ['files'=> true, 'id'=>class_basename($baseObject) . '_form', 'class' => 'form-horizontal']) !!}
                @include($viewBaseClosure . '.form')

                @section('form_actions')
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-10">
                            @if( Route::has($routeBaseName . '.index') )
                            <a onClick="return confirm('{{ trans('backendnav.confirmdelete') }}');" href="{{ route($routeBaseName . '.index') }}" class="btn default">{{ trans('backendnav.cancel') }}</a>
                            @endif
                            <input class="btn blue" onClick="if ($('#inputPassword').val() != $('#inputPasswordConfirm').val()) { alert('{{ trans('backendnav.passwordnotsame') }}'); return false;  } else { return true; }" type="submit" value="{{ trans('backendnav.save') }}"/>
                        </div>
                    </div>
                </div>
                @endsection
                @yield('form_actions')
                {!! Form::close() !!}
            </div>
        </div>
        <!-- END FORM PORTLET-->
    </div>
</div>
<!-- END CONTENT -->
@stop

@push('pre-main-js-script')
    <script type="text/javascript" src="/metronic/js/vendor/jquery.inputmask.min.js"></script>
    <script type="text/javascript" src="/metronic/js/vendor/jquery.inputmask.numeric.min.js"></script>
    <script type="text/javascript" src="/metronic/js/vendor/vanilla.masker.min.js"></script>
@endpush

@section('page_script')
<script>
</script>
@stop

@extends('backend.layouts.base')

@section('content')
<!-- BEGIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN Portlet PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    {{-- <i class="fa fa-black-tie font-dark"></i>&nbsp; --}}
                    <span class="caption-subject bold uppercase">{{ $baseObject->getFormattedValue() }}</span>
                    <span class="caption-helper">{{ $title or '' }}</span>
                    @yield('title-addition')
                </div>
                <div class="actions">
                    @if( Route::has($routeBaseName . '.create') )
                    {!! nav_menu(route($routeBaseName . ".create"), '', 'icon-plus', 'btn btn-circle btn-icon-only btn-default', trans('backendnav.create')) !!}
                    @endif
                    @if( Route::has($routeBaseName . '.edit') )
                    {!! nav_menu(route($routeBaseName . ".edit", ['id'=>$baseObject->id]), '', 'icon-pencil', 'btn btn-circle btn-icon-only btn-default', trans('backendnav.edit')) !!}
                    @endif
                    @if( Route::has($routeBaseName . '.destroy') )
                    {!! post_nav_menu(route($routeBaseName . '.destroy', ['id' => $baseObject->id]), '', csrf_token(), trans('label.model.delete_confirmation_text'), 'icon-trash', 'btn btn-circle btn-icon-only btn-default', trans('backendnav.delete')) !!}
                    @endif
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" title="Fullscreen" href="javascript:;"> </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-lg-3 form-horizontal pull-right">
                        @if ($baseObject->timestamps)
                        <div class="note note-warning pull-limit-bottom">
                            <p>
                                <em>
                                    {{ trans('backendnav.updatedat') }} {!! $baseObject->renderAttribute('updated_at') !!}
                                </em>
                            </p>
                        </div>
                        <div class="note note-warning pull-limit-bottom">
                            <p>
                                <em>
                                    {{ trans('backendnav.createdat') }} {!! $baseObject->renderAttribute('created_at') !!}
                                </em>
                            </p>
                            <hr>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-9 form-horizontal form-bordered form-row-stripped">
                        @foreach($baseObject->getBufferedAttributeSettings() as $key => $attribute)
                        @if(!in_array($key, $baseObject->getHidden()) && (!$baseObject->timestamps || !in_array($key, $baseObject->timestampFields)))
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">{{ $attribute['label'] }}</label>
                            <div class="col-md-8">
                                <div class="form-control-static">{!! !empty($baseObject->renderAttribute($key)) ? $baseObject->renderAttribute($key) : '-'  !!}</div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT -->
@stop

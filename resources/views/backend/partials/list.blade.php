@extends('backend.layouts.base')

@inject('baseConfig', 'App\Config\BaseConfig')

@section('content')
<?php 

$heading = isset($pageId) && isset($baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]) ? $baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]['label'] : $baseObject->getLabel();

$pageTitle = trans($heading);

$anyFilterable = false;
foreach($baseObject->getBufferedAttributeSettings() as $key=>$val) {
  if ( isset($val['visible']) && $val['visible'] && isset($val['filterable']) && $val['filterable'] ) {
    $anyFilterable = true;
    break;
  }
}
$yadcfContainer = [];
$yadcfIdx = 0;

?>
<!-- BEGIN CONTENT -->
<div class="row">
  <div class="col-md-12">
      <!-- Begin: life time stats -->
      <div class="portlet light portlet-fit portlet-datatable bordered">
          <div class="portlet-title">
              <div class="caption">
                  <i class="{{$pageIcon or ''}} font-dark"></i>&nbsp;
                  <span class="caption-subject font-dark sbold uppercase">{{ $pageTitle or '' }}</span>
              </div>
              <div class="actions">
              </div>
          </div>
          <div class="portlet-body table-manages">
            <div class="table-toolbar">
              @if($anyFilterable)
                <div class="row">
                  <div class="col-md-12">
                    <div class="toggles">
                      <div class="block">
                          <a class="toggles__trigger btn green text-left" data-target="#filterColumn">
                              Filter
                              <span class="fa fa-fw fa-caret-down"></span>
                          </a>
                      </div>
                      <div class="toggles__content hide-element filter-custom-area" id="filterColumn">
                        @foreach($baseObject->getBufferedAttributeSettings() as $key=>$val)
                          @if( isset($val['visible']) && $val['visible'] )
                            @if( isset($val['filterable']) && $val['filterable'] )
                              <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label">Filter by {{ $val['label'] }}</label>
                                <div class="col-md-8">
                                    <span id="{{$key}}Filter"
                                      data-filter-column="{{ $yadcfIdx }}"
                                      data-filter-type="{{ in_array($val['type'], ['datetime', 'date']) ? $val['type'] : 'select' }}"
                                      @if($key == 'country_id')
                                        data-filter-url-ajax="{{ route('backend.country.options.json') }}"
                                      @elseif($key == 'talent_expertise_id')
                                        data-filter-url-ajax="{{ route('backend.talentexpertise.options.json') }}"
                                      @elseif($key == 'user_id' || $key == 'talent_user_id')
                                        data-filter-url-ajax="{{ route('backend.user.options.json') }}"
                                      @endif
                                      data-filter-default-label="-- select {{ strtolower($val['label']) }} --"></span>
                                </div>
                              </div>
                              <?php $yadcfContainer[] = $key . "Filter"; ?>
                            @endif
                            <?php $yadcfIdx++; ?>
                          @endif
                        @endforeach
                        <div class="clearfix">&nbsp;</div>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
                <div class="row">
                  <div class="col-md-6">
                    <!-- <div class="btn-group"> -->
                      @if( Route::has($routeBaseName . '.create') )
                      {!! nav_menu(route($routeBaseName . ".create"), trans('backendnav.create'), 'fa fa-plus', 'btn sbold green') !!}
                      @endif
                    <!-- </div> -->
                    <div class="table-actions" style="background-color: #eeeeee;">
                      &nbsp;&nbsp;Action for Selected Item : &nbsp;&nbsp;
                      <!--
                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                            <i class="icon-cloud-upload"></i>
                        </a>
                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                            <i class="icon-wrench"></i>
                        </a>
                      -->
                      @if( Route::has($routeBaseName . '.destroyall') )
                        {!! post_nav_menu(route($routeBaseName . ".destroyall"), '', csrf_token(), 'Are you sure to delete selected item?', 'icon-trash', "btn btn-circle btn-icon-only btn-default", 'Delete selected item') !!}
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                  @if( Route::has($routeBaseName . '.exportxls') )
                    <div class="btn-group pull-right">
                      <button class="btn green dropdown-toggle" data-toggle="dropdown">Tools
                          <i class="fa fa-angle-down"></i>
                      </button>
                      <ul class="dropdown-menu pull-right">
                        @if( Route::has($routeBaseName . '.exportxls') )
                          <li>
                              <a href='{{ route($routeBaseName . ".exportxls") }}'>
                                  <i class="fa fa-file-excel-o"></i> Export to Excel </a>
                          </li>
                        @endif
                        <!--
                          <li>
                              <a href="javascript:;">
                                  <i class="fa fa-print"></i> Print </a>
                          </li>
                          <li>
                              <a href="javascript:;">
                                  <i class="fa fa-file-pdf-o"></i> Save as PDF </a>
                          </li>
                        -->
                      </ul>
                    </div>
                  @endif
                  </div>
                </div>
            </div>
            <table id="{{ class_basename($baseObject) }}" class="table table-striped table-bordered table-hover table-checkable"
            data-datatable-yadcf="{{ route($routeBaseName . '.index.json') . "?_token=" . csrf_token() . (isset($paramSerialized) && !empty($paramSerialized) ? "&".$paramSerialized : "") }}"
              data-datatable-yadcf-container="{{ implode(',', $yadcfContainer) }}"
              @if(Route::has($routeBaseName . '.select'))
                data-datatable-yadcf-nb-checked="0"
                data-datatable-yadcf-order-disabled="0"
              @endif
              data-start="{{ session()->get('datatable['.$pageId.'][iDisplayStart]', 0) }}"
              data-length="{{ session()->get('datatable['.$pageId.'][iDisplayLength]', 10) }}">
              <thead>
                  <tr>
                    @if(Route::has($routeBaseName . '.select'))
                      <th>
                          #
                      </th>
                    @endif
                    @foreach($baseObject->getBufferedAttributeSettings() as $key=>$val)
                      @if( isset($val['visible']) && $val['visible'] )
                        <th>{{ $val['label'] }}</th>
                      @endif
                    @endforeach
                    <td><b>{{ trans('backendnav.menu') }}</b></td>
                </tr>
              </thead>
            </table>
          </div>
      </div>
      <!-- End: life time stats -->
  </div>
</div>
<!-- END CONTENT -->
@stop

@push('end_script')
<script>
</script>
@endpush

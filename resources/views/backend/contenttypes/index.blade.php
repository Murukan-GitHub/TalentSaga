@extends('backend.layouts.base')

@section('page_title')
@endsection

@section('content')
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title">Content Type &amp; Category
    <!-- <small>subtitle</small> -->
</h3>
<!-- END PAGE TITLE-->
<!-- BEGIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <div class="tabbable-line boxless tabbable-reversed">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_ctype" data-toggle="tab"> Type </a>
                </li>
                <li>
                    <a href="{{ route('backend.contentcategory.index') }}"> Category </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_ctype">
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-tag font-dark"></i>&nbsp;
                                <span class="caption-subject font-dark sbold uppercase">Content Type</span>
                            </div>
                            <div class="actions">
                                @if( Route::has('backend.contenttype.create') )
                                {!! nav_menu(route("backend.contenttype.create"), 'Create New', 'fa fa-sw fa-plus', 'btn btn-sm green btn-outline active') !!}
                                @endif
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table id="contentType" class="table table-striped table-bordered table-hover table-checkable" data-enhance-ajax-table="{{ route('backend.contenttype.index.json') . "?_token=" . csrf_token() }}">
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
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT -->
@stop

@section('page_script')
<script>
</script>
@stop

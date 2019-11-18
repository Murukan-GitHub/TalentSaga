<!-- (14) Table Menu -->
<div class="btn-group">

	@if(isset($menuSetting['url_delete']) && !empty($menuSetting['url_delete']) &&isset($menuSetting['session_token']) && !empty($menuSetting['session_token']))
        <form method='post' action="{{ $menuSetting['url_delete'] }}">
        	<input type='hidden' name='_token' value="{{ $menuSetting['session_token'] }}" />
    @endif

 	<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> {{ trans('backendnav.menu') }}
        <i class="fa fa-angle-down"></i>
    </button>

    <ul class="dropdown-menu pull-right" role="menu">
       	@if(isset($menuSetting['url_detail']) && !empty($menuSetting['url_detail']))
            <li>
                <a href="{{ $menuSetting['url_detail'] }}"><i class="icon-info"></i>&nbsp;{{ trans('backendnav.show') }}&nbsp;</a>
            </li>
        @endif
		@if(isset($menuSetting['url_edit']) && !empty($menuSetting['url_edit']))
            <li>
                <a href="{{ $menuSetting['url_edit'] }}"><i class="icon-pencil"></i>&nbsp;{{ trans('backendnav.edit') }}&nbsp;</a>
            </li>
        @endif
		@if(isset($menuSetting['url_delete']) && !empty($menuSetting['url_delete']) &&isset($menuSetting['session_token']) && !empty($menuSetting['session_token']))
            <li>
    	 		<a onClick='return (confirm("{{ trans('backendnav.areusure') }}") ? $(this).closest("form").submit() : false);'><i class="icon-trash"></i>&nbsp;{{ trans('backendnav.delete') }}&nbsp;</a>
            </li>
        @endif
    </ul>

    @if(isset($menuSetting['url_delete']) && !empty($menuSetting['url_delete']) &&isset($menuSetting['session_token']) && !empty($menuSetting['session_token']))
        </form>
    @endif
</div>

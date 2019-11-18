<!-- (14) Table Menu -->
@if(isset($menuSetting['url_detail']) && !empty($menuSetting['url_detail']))
    <a href="{{ $menuSetting['url_detail'] }}" class='btn btn--lime'><span class='fa fa-fw fa-search'></span></a> &nbsp; 
@endif
@if(isset($menuSetting['url_edit']) && !empty($menuSetting['url_edit']))
    <a href="{{ $menuSetting['url_edit'] }}" class='btn btn--blue'><span class='fa fa-fw fa-pencil'></span></a> &nbsp;
@endif
@if(isset($menuSetting['url_delete']) && !empty($menuSetting['url_delete']) &&isset($menuSetting['session_token']) && !empty($menuSetting['session_token']))
    <form method='post' action="{{ $menuSetting['url_delete'] }}"><input type='hidden' name='_token' value="{{ $menuSetting['session_token'] }}"><button type='submit' class='btn btn--red' onClick="return confirm('Are you sure?');"><span class='fa fa-fw fa-remove'></span></button></form>
@endif

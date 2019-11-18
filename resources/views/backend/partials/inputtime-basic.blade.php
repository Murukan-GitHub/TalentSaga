<input class='form-control' id='{{ $formSetting['id'] }}' type='time' name='{{ $formSetting['name'] }}' value='{{ $formSetting['value'] }}' {{ $formSetting['required'] ? 'required' : '' }}>
@if($formSetting['errors'])
    <div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
@endif

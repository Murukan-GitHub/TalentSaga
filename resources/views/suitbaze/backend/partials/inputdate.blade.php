<!-- (7) Date Input -->
<div class='form-row' id='{{ $formSetting['container_id'] }}'>
    <div class='bzg'>
        <div class='bzg_c' data-col='l4'>
            <label class='label-inline' for='{{ $formSetting['id'] }}'>{{ $formSetting['label'] }}</label>
        </div>
        <div class='bzg_c' data-col='l8'>
            <input class='form-input' id='{{ $formSetting['id'] }}' type='date' name='{{ $formSetting['name'] }}' value='{{ $formSetting['value'] }}' {{ $formSetting['required'] ? 'required' : '' }}>
            @if($formSetting['errors'])
                <br><label class='label-inline' style='color:red;''>{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</label>
            @endif
        </div>
    </div>
</div>

<!-- (5) Boolean Input -->
<div class='form-row' id='{{ $formSetting['container_id'] }}'>
    <div class='bzg'>
        <div class='bzg_c' data-col='l4'>
            <label class='label-inline' for='{{ $formSetting['id'] }}'>{{ $formSetting['label'] }}</label>
        </div>
        <div class='bzg_c' data-col='l8'>
            <input type='radio' value='1' name='{{ $formSetting['name'] }}' {{ $formSetting['value'] ? 'checked' : '' }} {{ $formSetting['required'] ? 'required' : '' }} {{ $formSetting['readonly'] ? 'readonly' : '' }}> Yes 
            &nbsp;&nbsp; 
            <input type='radio' value='0' name='{{ $formSetting['name'] }}' {{ !$formSetting['value'] ? 'checked' : '' }} {{ $formSetting['required'] ? 'required' : '' }} {{ $formSetting['readonly'] ? 'readonly' : '' }}> No
            @if($formSetting['errors'])
                <br><label class='label-inline' style='color:red;''>{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</label>
            @endif
        </div>
    </div>
</div>

<!-- (12) Password Input -->
<div class='form-row' id='{{ $formSetting['container_id'] }}'>
    <div class='bzg'>
        <div class='bzg_c' data-col='l4'>
            <label class='label-inline' for='{{ $formSetting['id'] }}'>{{ $formSetting['label'] }}</label>
        </div>
        <div class='bzg_c' data-col='l8'>
            <div class='bzg'>
                <div class='bzg_c' data-col='l5'>
                    <input class='form-input' id='{{ $formSetting['id'] }}' type='password' name='{{ $formSetting['name'] }}' value='' {{ $formSetting['required'] ? 'required' : '' }}>
                </div>
                <div class='bzg_c' data-col='l2'> Confirm </div>
                <div class='bzg_c' data-col='l5'>
                    <input class='form-input' id='{{ $formSetting['id'] }}Confirm' type='password' name='{{ $formSetting['name'] }}_confirmation' value='' {{ $formSetting['required'] ? 'required' : '' }}>
                </div>
            </div>
            @if($formSetting['errors'])
                <br><label class='label-inline' style='color:red;''>{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</label>
            @endif
        </div>
    </div>
</div>

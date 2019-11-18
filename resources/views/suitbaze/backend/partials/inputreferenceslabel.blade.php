<!-- (1) Readonly Input Text References -->
<div class='form-row' id='{{ $formSetting['container_id'] }}'>
    <div class='bzg'>
        <div class='bzg_c' data-col='l4'>
            <label class='label-inline' for='{{ $formSetting['id'] }}'>{{ $formSetting['label'] }}</label>
        </div>
        <div class='bzg_c' data-col='l8'>
            <input class='form-input' id='{{ $formSetting['masked_id'] }}' type='text' value='{{ $formSetting['masked_value'] }}' readonly>
            <input id='{{ $formSetting['id'] }}' type='hidden' name='{{ $formSetting['name'] }}' value='{{ $formSetting['value'] }}' {{ $formSetting['required'] ? 'required' : '' }}>
            @if($formSetting['errors'])
                <br><label class='label-inline' style='color:red;''>{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</label>
            @endif
        </div>
    </div>
</div>

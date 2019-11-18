<!-- (10) Richtext Input -->
<div class='form-row' id='{{ $formSetting['container_id'] }}'>
    <div class='bzg'>
        <div class='bzg_c' data-col='l4'>
            <label class='label-inline' for='{{ $formSetting['id'] }}'>{{ $formSetting['label'] }}</label>
        </div>
        <div class='bzg_c' data-col='l8'>
            <textarea rows='5' data-autosize data-wysiwyg data-wysiwyg-upload-source='{{ $formSetting['action_handler_route'] }}' class='form-input' id='{{ $formSetting['id'] }}' name='{{ $formSetting['name'] }}' {{ $formSetting['required'] ? 'required' : '' }} {{ $formSetting['readonly'] ? 'readonly' : '' }}>{{ $formSetting['value'] }}</textarea>
            @if($formSetting['errors'])
                <br><label class='label-inline' style='color:red;''>{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</label>
            @endif
        </div>
    </div>
</div>

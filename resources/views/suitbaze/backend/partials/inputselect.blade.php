<!-- (2) Dropdown List Options -->
<div class='form-row' id='{{ $formSetting['container_id'] }}'>
    <div class='bzg'>
        <div class='bzg_c' data-col='l4'>
            <label class='label-inline' for='{{ $formSetting['id'] }}'>{{ $formSetting['label'] }}</label>
        </div>
        <div class='bzg_c' data-col='l8'>
            <select data-select-autocomplete name='{{ $formSetting['name'] }}' class='form-input' id='{{ $formSetting['id'] }}' {{ $formSetting['required'] ? 'required' : '' }}>
                <option value=''>-- select {{ strtolower($formSetting['label']) }} --</option>
                @foreach($formSetting['options'] as $key=>$value)
                    <option value='{{ $key }}' {{ $key == $formSetting['value'] ? "selected" : "" }}>{{ $value }}</option>
                @endforeach
            </select>
            @if($formSetting['errors'])
                <br><label class='label-inline' style='color:red;''>{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</label>
            @endif
        </div>
    </div>
</div>

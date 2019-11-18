<div class="md-radio-inline">
    <div class="md-radio">
        <input type="radio" value='1' id="{{ $formSetting['id'] }}_yes" name="{{ $formSetting['name'] }}" class="md-radiobtn" {{ $formSetting['value'] ? 'checked' : '' }} {{ $formSetting['required'] ? 'required' : '' }} {{ $formSetting['readonly'] ? 'readonly' : '' }}>
        <label for="{{ $formSetting['id'] }}_yes">
            <span></span>
            <span class="check"></span>
            <span class="box"></span> Yes </label>
    </div>
    <div class="md-radio has-error">
        <input type="radio" value='0' id="{{ $formSetting['id'] }}_no" name="{{ $formSetting['name'] }}" class="md-radiobtn" {{ !$formSetting['value'] ? 'checked' : '' }} {{ $formSetting['required'] ? 'required' : '' }} {{ $formSetting['readonly'] ? 'readonly' : '' }}>
        <label for="{{ $formSetting['id'] }}_no">
            <span></span>
            <span class="check"></span>
            <span class="box"></span> No </label>
    </div>
</div>
@if($formSetting['errors'])
<div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
@endif

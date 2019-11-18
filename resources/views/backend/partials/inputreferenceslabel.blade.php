<!-- (1) Readonly Input Text References -->
<div class="form-group {{ $formSetting['errors'] ? 'has-error' : '' }}" id="{{ $formSetting['container_id'] }}">
    <label class="col-md-3 control-label" for="{{ $formSetting['id'] }}">{{ $formSetting['label'] }}</label>
    <div class="col-md-9">
        <input class='form-control' id='{{ $formSetting['masked_id'] }}' type='text' value='{{ $formSetting['masked_value'] }}' readonly>
        <input id='{{ $formSetting['id'] }}' type='hidden' name='{{ $formSetting['name'] }}' value='{{ $formSetting['value'] }}' {{ $formSetting['required'] ? 'required' : '' }}>
        @if($formSetting['errors'])
            <div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
        @endif
    </div>
</div>

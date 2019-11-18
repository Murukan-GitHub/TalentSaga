<div class="form-group">
    <div class="col-md-5">
        <input type="password" class="form-control" id="{{ $formSetting['id'] }}" placeholder="type to changes..." name='{{ $formSetting['name'] }}' value='' {{ $formSetting['required'] ? 'required' : '' }}>
    </div>
    <label class="col-md-2" for="{{ $formSetting['id'] }}Confirm">
        Confirm
    </label>
    <div class="col-md-5">
        <input type="password" class="form-control" id="{{ $formSetting['id'] }}Confirm" placeholder="confirm changes..." name='{{ $formSetting['name'] }}_confirmation' value='' {{ $formSetting['required'] ? 'required' : '' }}>
    </div>
</div>
@if($formSetting['errors'])
<div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
@endif

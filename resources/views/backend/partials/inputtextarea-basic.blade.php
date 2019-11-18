{{ Form::textarea($formSetting['name'], $formSetting['value'],
    array_merge([
        'class' => 'form-control',
        'id' => $formSetting['id'],
        'rows' => '5',
        'data-autosize' => '',
        'placeholder' => 'type ' . strtolower($formSetting['label'])],
    $formSetting['required'] ? ['required' => ''] : [],
    $formSetting['readonly'] ? ['readonly' => ''] : [],
    $formSetting['attributes']))
}}
@if($formSetting['errors'])
<div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
@endif

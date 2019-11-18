@if ($formSetting['selecttype'] == 'multiselect')
{{ Form::select(($formSetting['name'] . ($formSetting['multiple'] ? '[]' : '')), $formSetting['options'], $value = old($formSetting['name'], ($formSetting['value'] ?: [])),
    array_merge([
        'class' => 'multi-select',
        'id' => $formSetting['id'],
        'data-multi-select' => '',
        'data-value' => is_array($value) ? json_encode($value) : $value],
    $formSetting['required'] ? ['required' => 'required'] : [],
    $formSetting['readonly'] ? ['readonly' => 'readonly'] : [],
    $formSetting['multiple'] ? ['multiple' => ''] : [],
    ['placeholder' => 'select ' . strtolower($formSetting['label']), 'data-placeholder' => 'select ' . strtolower($formSetting['label'])],
    $formSetting['attributes']))
}}
@else
{!! Form::select(($formSetting['name'] . ($formSetting['multiple'] ? '[]' : '')), $formSetting['options'], $value = old($formSetting['name'], ($formSetting['value'] ?: [])),
    array_merge([
        'class' => ('form-control select2' . ($formSetting['multiple'] ? '-multiple' : '')),
        'id' => $formSetting['id'],
        'data-value' => is_array($value) ? json_encode($value) : $value],
    $formSetting['required'] ? ['required' => 'required'] : [],
    $formSetting['readonly'] ? ['readonly' => 'readonly'] : [],
    $formSetting['multiple'] ? ['multiple' => ''] : [],
    ['placeholder' => 'select ' . strtolower($formSetting['label']), 'data-placeholder' => 'select ' . strtolower($formSetting['label'])],
    $formSetting['attributes']))
!!}
@endif
@if($formSetting['errors'])
<span class="help-block help-block-error">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</span>
<div class="form-control-focus"> </div>
@endif

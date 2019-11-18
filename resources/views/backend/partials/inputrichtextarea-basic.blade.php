{{ Form::textarea($formSetting['name'], $formSetting['value'],
    array_merge([
            'class' => 'form-control',
            'id' => $formSetting['id'],
            'placeholder' => 'type ' . strtolower($formSetting['label']),
            'rows' => '5',
            'data-wysiwyg' => '',
            'data-wysiwyg-upload-source' => $formSetting['action_handler_route']],
        $formSetting['required'] ? ['required' => ''] : [],
        $formSetting['readonly'] ? ['readonly' => ''] : [],
        $formSetting['attributes']))
}}
@if($formSetting['errors'])
<div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
@endif

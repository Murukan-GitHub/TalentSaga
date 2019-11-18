<!-- (10) Richtext Input -->
<div class="form-group {{ $formSetting['errors'] ? 'has-error' : '' }}" id="{{ $formSetting['container_id'] }}">
    <label class="col-md-3 control-label" for="{{ $formSetting['id'] }}">{{ $formSetting['label'] }}</label>
    <div class="col-md-9">
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
    </div>
</div>

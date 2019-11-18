<!-- (2) Dropdown List Options -->
<div class="form-group {{ $formSetting['errors'] ? 'has-error' : '' }}" id="{{ $formSetting['container_id'] }}">
    <label class="col-md-3 control-label" for="{{ $formSetting['id'] }}">{{ $formSetting['label'] }}</label>
    <div class="col-md-9">
        {{ Form::select($formSetting['name'], ($formSetting['options'] ? $formSetting['options'] : ((isset($formSetting['data_url']) && !empty($formSetting['data_url'])) ? [$formSetting['value'] => (isset($formSetting['value_text']) ?  $formSetting['value_text'] : 'Unknown ' . ucfirst(strtolower($formSetting['label'])))] : [])), $value = old($formSetting['name'], $formSetting['value']),
            array_merge([
                'class' => ('form-control select2' . ($formSetting['multiple'] ? '-multiple' : '')),
                'id' => $formSetting['id'],
                'data-value' => $value],
            $formSetting['required'] ? ['required' => ''] : [],
            $formSetting['readonly'] ? ['readonly' => ''] : [],
            $formSetting['multiple'] ? ['multiple' => ''] : [],
            ['placeholder' => 'select ' . strtolower($formSetting['label']), 'data-placeholder' => 'select ' . strtolower($formSetting['label'])],
            (!$formSetting['multiple'] && isset($formSetting['data_url']) && !empty($formSetting['data_url'])) ? [
                'metronics-select-autocomplete' => $formSetting['data_url'],
                'metronics-select-autocomplete-init-value' => $formSetting['value'],
                'metronics-select-autocomplete-init-text' => (isset($formSetting['value_text']) ? $formSetting['value_text'] : 'Unknown ' . ucfirst(strtolower($formSetting['label']))),
                'metronics-select-autocomplete-empty-text' => "-- select " . strtolower($formSetting['label']) . " --" 
            ] : [],
            $formSetting['attributes']))
        }}
        @if($formSetting['errors'])
        <span class="help-block help-block-error">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</span>
        <div class="form-control-focus"> </div>
        @endif
    </div>
</div>

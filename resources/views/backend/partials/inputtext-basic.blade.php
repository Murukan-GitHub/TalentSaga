@if($formSetting['group'] && isset($formSetting['group']['type']))
@if($formSetting['group']['type'] == 'icon')
<div {!! HTML::attributes(['class' => 'input-group' . ' ' . (isset($formSetting['group']['icon-position']) ? $formSetting['group']['icon-position'] : '') . ' ' . (isset($formSetting['attributes']['class']) ? $formSetting['attributes']['class'] : '')]) !!}>
    <?php $value = is_numeric($thevalue = old($formSetting['name'], $formSetting['value'])) ? $thevalue : $formSetting['value']; ?>
    {{ Form::number($formSetting['name'], $value,
        array_merge([
            'class' => 'form-control',
            'id' => $formSetting['id'],
            'placeholder' => trans('label.enter').' ' . strtolower($formSetting['label']),
            'data-value' => $value],
        $formSetting['required'] ? ['required' => ''] : [],
        $formSetting['readonly'] ? ['readonly' => ''] : [],
        isset($formSetting['attributes']['class']) ? array_diff_key($formSetting['attributes'], ['class' => '']) : $formSetting['attributes']))
    }}
    @if($formSetting['errors'])
    <div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
    @endif
    @if(isset($formSetting['group']['icon']))
    <i class="{{ $formSetting['group']['icon'] }}"></i>
    @endif
</div>
@elseif($formSetting['group']['type'] == 'addon')
<div {!! HTML::attributes(['class' => 'input-group ' . (isset($formSetting['attributes']['class']) ? $formSetting['attributes']['class'] : '')]) !!}>
    @if(isset($formSetting['group']['addon-left']))
    <span class="input-group-addon">{!! $formSetting['group']['addon-left'] !!}</span>
    @endif
    <?php $value = is_numeric($thevalue = old($formSetting['name'], $formSetting['value'])) ? $thevalue : $formSetting['value']; ?>
    {{ Form::number($formSetting['name'], $value,
        array_merge([
            'class' => 'form-control',
            'id' => $formSetting['id'],
            'placeholder' => trans('label.enter').' ' . strtolower($formSetting['label']),
            'data-value' => $value],
        $formSetting['required'] ? ['required' => ''] : [],
        $formSetting['readonly'] ? ['readonly' => ''] : [],
        isset($formSetting['attributes']['class']) ? array_diff_key($formSetting['attributes'], ['class' => '']) : $formSetting['attributes']))
    }}
    @if($formSetting['errors'])
    <div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
    @endif
    @if(isset($formSetting['group']['addon-right']))
    <span class="input-group-addon">{!! $formSetting['group']['addon-right'] !!}</span>
    @endif
</div>
@elseif($formSetting['group']['type'] == 'button')
<div {!! HTML::attributes(array_merge($formSetting['attributes'], ['class' => 'input-group ' . (isset($formSetting['attributes']['class']) ? $formSetting['attributes']['class'] : '')])) !!}>
    @if(isset($formSetting['group']['button-left']))
    <span class="input-group-button btn-left">
        <button class="btn {!! isset($formSetting['group']['button-left-color']) ? $formSetting['group']['button-left-color'] : 'default' !!}" type="{!! $formSetting['group']['button-left-type'] ? $formSetting['group']['button-left-type'] : 'button' !!}">{!! $formSetting['group']['button-left-text'] ? $formSetting['group']['button-left-text'] : '' !!}</button>
    </span>
    @endif
    <?php $value = is_numeric($thevalue = old($formSetting['name'], $formSetting['value'])) ? $thevalue : $formSetting['value']; ?>
    {{ Form::number($formSetting['name'], $value,
        array_merge([
            'class' => 'form-control',
            'id' => $formSetting['id'],
            'placeholder' => trans('label.enter').' ' . strtolower($formSetting['label']),
            'data-value' => $value],
        $formSetting['required'] ? ['required' => ''] : [],
        $formSetting['readonly'] ? ['readonly' => ''] : [],
        isset($formSetting['attributes']['class']) ? array_diff_key($formSetting['attributes'], ['class' => '']) : $formSetting['attributes']))
    }}
    @if($formSetting['errors'])
    <div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
    @endif
    @if(isset($formSetting['group']['button-right']))
    <span class="input-group-button btn-right">
        <button class="btn {!! isset($formSetting['group']['button-right-color']) ? $formSetting['group']['button-right-color'] : 'default' !!}" type="{!! $formSetting['group']['button-right-type'] ? $formSetting['group']['button-right-type'] : 'button' !!}">{!! $formSetting['group']['button-right-text'] ? $formSetting['group']['button-right-text'] : '' !!}</button>
    </span>
    @endif
</div>
@endif
@else
<?php
$value = is_string($thevalue = old($formSetting['name'], $formSetting['value'])) ? $thevalue : $formSetting['value'];
?>
{{ Form::text($formSetting['name'], $value,
    array_merge([
        'class' => 'form-control',
        'id' => $formSetting['id'],
        'placeholder' => trans('label.enter').' ' . strtolower($formSetting['label']),
        'data-value' => $value],
    $formSetting['required'] ? ['required' => ''] : [],
    $formSetting['readonly'] ? ['readonly' => ''] : [],
    $formSetting['attributes']))
}}
@if($formSetting['errors'])
<div class="form-control-focus help-block">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
@endif
@endif

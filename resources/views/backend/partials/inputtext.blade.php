<!-- (13) Standard Input Text -->
<div class="form-group {{ $formSetting['errors'] ? 'has-error' : '' }}" id="{{ $formSetting['container_id'] }}">
    <label class="col-md-3 control-label" for="{{ $formSetting['id'] }}">{{ $formSetting['label'] }}</label>
    <div class="col-md-9">
        @if($formSetting['group'] && isset($formSetting['group']['type']))
        @if($formSetting['group']['type'] == 'icon')
        <div {!! HTML::attributes(['class' => 'input-group' . ' ' . (isset($formSetting['group']['icon-position']) ? $formSetting['group']['icon-position'] : '') . ' ' . (isset($formSetting['attributes']['class']) ? $formSetting['attributes']['class'] : '')]) !!}>
            {{ Form::number($formSetting['name'], $value = is_numeric($value = old($formSetting['name'], $formSetting['value'])) ? $value : $formSetting['value'],
                array_merge([
                    'class' => 'form-control',
                    'id' => $formSetting['id'],
                    'placeholder' => 'enter ' . strtolower($formSetting['label']),
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
            {{ Form::number($formSetting['name'], $value = is_numeric($value = old($formSetting['name'], $formSetting['value'])) ? $value : $formSetting['value'],
                array_merge([
                    'class' => 'form-control',
                    'id' => $formSetting['id'],
                    'placeholder' => 'enter ' . strtolower($formSetting['label']),
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
            {{ Form::number($formSetting['name'], $value = is_numeric($value = old($formSetting['name'], $formSetting['value'])) ? $value : $formSetting['value'],
                array_merge([
                    'class' => 'form-control',
                    'id' => $formSetting['id'],
                    'placeholder' => 'enter ' . strtolower($formSetting['label']),
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
        {{ Form::text($formSetting['name'], $value = is_string($value = old($formSetting['name'], $formSetting['value'])) ? $value : $formSetting['value'],
            array_merge([
                'class' => 'form-control',
                'id' => $formSetting['id'],
                'placeholder' => 'enter ' . strtolower($formSetting['label']),
                'data-value' => $value],
            $formSetting['required'] ? ['required' => ''] : [],
            $formSetting['readonly'] ? ['readonly' => ''] : [],
            $formSetting['attributes']))
        }}
        @if($formSetting['errors'])
        <div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
        @endif
        @endif
    </div>
</div>

<!-- (11) File Input -->
<div class="form-group {{ $formSetting['errors'] ? 'has-error' : '' }}" id="{{ $formSetting['container_id'] }}">
    <label class="col-md-3 control-label" for="{{ $formSetting['id'] }}">{{ $formSetting['label'] }}</label>
    <div class="col-md-9">
        <div class='form-group'>
            <div class="col-md-12">
                <input class='form-control' id='{{ $formSetting['id'] }}' name='{{ $formSetting['name'] }}' type='file' {{ $formSetting['required'] ? 'required' : '' }}><br>
                @if(empty($formSetting['value']))
                    @if($formSetting['image_file_url'])
                        <i>( No Image )</i>
                    @else
                        <i>( No File )</i>
                    @endif
                @else
                    @if($formSetting['image_file_url'])
                        <figure>
                            <figcaption>
                                Current Image :
                                <label id='selected{{ $formSetting['id'] }}'>{{ $formSetting['value'] }}</label>
                            </figcaption>
                            <img class='thumbnail' src='{{ $formSetting['image_file_url'] }}' style='max-height: 120px' alt=''>
                        </figure>
                    @else
                        Current File : <br>
                        <a id='selected{{ $formSetting['id'] }}' href='{{ $formSetting['file_url'] }}' target='_BLANK'>{{ $formSetting['value'] }}</a>
                    @endif
                @endif
            </div>
        </div>
        @if($formSetting['errors'])
            <div class="form-control-focus">{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</div>
        @endif
    </div>
</div>

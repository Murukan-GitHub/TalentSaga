<!-- (11) File Input -->
<div class='form-row' id='{{ $formSetting['container_id'] }}'>
    <div class='bzg'>
        <div class='bzg_c' data-col='l4'>
            <label class='label-inline' for='{{ $formSetting['id'] }}'>{{ $formSetting['label'] }}</label>
        </div>
        <div class='bzg_c' data-col='l8'>
            <div class='bzg block'>
                <div class='bzg_c' data-col='m12'>
                    <input class='form-input' id='{{ $formSetting['id'] }}' name='{{ $formSetting['name'] }}' type='file' {{ $formSetting['required'] ? 'required' : '' }}><br>
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
                <br><label class='label-inline' style='color:red;''>{{ $formSetting['errors'] ? $formSetting['errors'] : "" }}</label>
            @endif
        </div>
    </div>
</div>

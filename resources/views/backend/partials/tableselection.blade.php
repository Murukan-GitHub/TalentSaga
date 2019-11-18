<!-- (14) Table Menu -->
@if(isset($selectionSetting['url_selection']) && !empty($selectionSetting['url_selection']) &&isset($selectionSetting['session_token']) && !empty($selectionSetting['session_token']))
    <form id="select#id#" method='post' action="{{ $selectionSetting['url_selection'] }}">
        <input type='hidden' name='_token' value="{{ $selectionSetting['session_token'] }}" />
@endif
<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
    <input type="checkbox" name="is_selected" class="checkboxes" value="1" #checked# onClick="$(this).closest('form').submit()" />
    <span></span>
</label>
@if(isset($selectionSetting['url_selection']) && !empty($selectionSetting['url_selection']) &&isset($selectionSetting['session_token']) && !empty($selectionSetting['session_token']))
    </form>
    <script>
        $('#select#id#').submit(function(event) {
            var nbChecked = $(this).closest('table').attr('data-datatable-yadcf-nb-checked') || 0;
            try {
                nbChecked = parseInt(nbChecked);
            } catch(e) { 
                nbChecked = 0;
            }
            nbChecked = nbChecked + ($('#select#id# input[name=is_selected]').is(':checked') ? 1 : -1);
            $(this).closest('table').attr('data-datatable-yadcf-nb-checked', nbChecked);
            var formData = {
                '_token'         : $('#select#id# input[name=_token]').val(),
                'is_selected'    : $('#select#id# input[name=is_selected]').is(':checked') ? 1 : 0
            };
            $.ajax({
                type        : 'POST', 
                url         : '{{ $selectionSetting["url_selection"] }}', 
                data        : formData,
                dataType    : 'json', 
                encode      : true
            })
            .done(function(data) { });
            event.preventDefault();
        });
    </script>
@endif
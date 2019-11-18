<fieldset class="block">
    <!-- Default Entry Form -->
    @foreach($content->getBufferedAttributeSettings() as $key=>$val)
        @if( $val['formdisplay'] )
            {!! $content->renderFormView($key, null, $errors) !!}
        @endif
    @endforeach
</fieldset>

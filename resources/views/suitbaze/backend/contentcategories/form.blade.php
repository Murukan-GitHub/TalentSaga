<fieldset class="block">
    <!-- Default Entry Form -->
    @foreach($contentCategory->getBufferedAttributeSettings() as $key=>$val)
        @if( $val['formdisplay'] )
            {!! $contentCategory->renderFormView($key, null, $errors) !!}
        @endif
    @endforeach
</fieldset>

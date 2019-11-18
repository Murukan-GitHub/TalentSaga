<div class="form-body">
    <!-- Default Entry Form -->
    @foreach($baseObject->getBufferedAttributeSettings() as $key=>$val)
    @if( $val['formdisplay'] && (!isset($hiddenInputs) || !in_array($key, $hiddenInputs)) )
    {!! $baseObject->renderFormView($key, null, $errors, null, false, null, null) !!}
    @endif
    @endforeach
</div>

var WysiwygEditors = function () {

    var handleEditors = function () {
        var elmts = $('[data-wysiwyg]');

        if (!elmts.length) return;

        $.each(elmts, function() {
            var elmt    = $(this);
            var editor  = elmt.data('wysiwyg');

            switch (editor) {
                case 'summernote':
                    initSummernote(elmt);
                    break;
                case 'ckeditor':
                    initCKEditor(elmt);
                    break;
                default:
                    initCKEditor(elmt);
                    break;
            }
        });

        function initCKEditor(elmt) {
            var action = elmt.data('wysiwyg-upload-source');

            if (action == null || action == '') {
                $(elmt).ckeditor({
                    htmlEncodeOutput: true,
                    allowedContent: true
                });
            } else {
                $(elmt).ckeditor({
                    filebrowserImageUploadUrl: action,
                    htmlEncodeOutput: true,
                    allowedContent: true
                });
            }
        }

        function initSummernote(elmt) {
            var height = elmt.data('wysiwyg-height') || 300;

            elmt.summernote({height: height});
        }
    }

    return {
        //main function to initiate the module
        init: function () {
            handleEditors();
        }
    };

}();

jQuery(document).ready(function() {
   WysiwygEditors.init();
});

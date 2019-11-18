var TagsInputObject = function() {

    var handleInput = function() {
        var elmts = $('[data-tags-input]');

        if (!elmts.length) return;

        $.each(elmts, function() {
            var elmt = $(this);

            elmt.tagsinput({
                tagClass: 'label label-primary',
                itemValue: 'value',
                itemText: 'text',
                allowDuplicates: false
            });
        });
    }

    var handleSelect = function() {
        var elmts = $('[data-tags-select]');

        if (!elmts.length) return;

        $.each(elmts, function() {
            var elmt = $(this);

            var select = elmt.data('tags-select');

            if (!select.length) return;

            var $select = $('#' + select);

            $select.on('change', function() {
                addTag($select, elmt);
            });

            addTag($select, elmt);
        });

        function addTag(selector, input) {
            if ($("option:selected", selector).val()){
                var val = $("option:selected", selector).val();

                input.tagsinput('add', {
                    "value": !isNaN(val) ? parseInt(val) : val,
                    "text": $("option:selected", selector).text()
                });

                input.tagsinput('refresh');
            }
        }
    }

    var handleValue = function() {
        var elmts = $('[data-tags-value]');

        if (!elmts.length) return;

        $.each(elmts, function() {
            var elmt = $(this);

            var value = elmt.data('tags-value');

            if (!value) return;

            var data = JSON.stringify(value);

            $.each(data, function(i, item) {
                elmt.tagsinput('add', item);
            });

            elmt.tagsinput('refresh');
        });
    }

    var handleJson = function() {
        var elmts = $('[data-tags-json]');

        if (!elmts.length) return;

        $.each(elmts, function() {
            var elmt = $(this);

            var value = elmt.data('tags-json');

            if (!value.length) return;

            $.getJSON(value, function(data) {
                var items = [];
                $.each( data, function( key, val ) {
                    elmt.tagsinput('add', val);
                });
            });

            elmt.tagsinput('refresh');
        });
    }

    return {
        //main function to initiate the module
        init: function() {
            handleInput();
            handleValue();
            handleJson();
            handleSelect();
        }
    };

}();

jQuery(document).ready(function() {
    TagsInputObject.init();
});

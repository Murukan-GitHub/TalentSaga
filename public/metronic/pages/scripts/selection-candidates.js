var SelectionCandidates = function() {

    var handleCandidates = function() {

        var elmts = $('[data-selected-candidate]');

        if (!elmts.length) return;

        $.each(elmts, function() {
            var elmt = $(this);
            var form = elmt.data('form');

            if (!form) return;

            var url = $(form).attr('action');

            var token = $('input[name=_token]').val();

            if (!token) return;

            var selected = elmt.data('selected-candidate');

            var content = $('.list-candidate');

            if (!form) return;

            elmt.click(function() {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: { user_id: selected, _token: token },
                    beforeSend: function () {
                        App.blockUI();
                    },
                    complete: function () {
                        ContentCandidates.handlePage(content, url);
                    },
                    success: function (data) {
                        window.setTimeout(function() {
                            App.unblockUI();
                        }, 1000);
                    },
                });
            });
        });
    }

    return {
        //main function to initiate the module
        init: function() {
            handleCandidates();
        }
    };
}();

jQuery(document).ready(function() {
   SelectionCandidates.init();
});

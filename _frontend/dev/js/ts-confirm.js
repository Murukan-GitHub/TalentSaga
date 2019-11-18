
(function (window, document, $, undefined) {

    var $body = $(document.body);

    $body.on('click.tsconfirm', '.ts-confirm-cancel', function (e) {
        hideAndDestroyConfirm();
    });

    $body.on('click.tsconfirm', '.ts-confirm-ok', function (e) {
        var id = $(this).data('id');
        var $target = $('#' + id);
        $target.parents('form').trigger('submit')
        hideAndDestroyConfirm();
    });

    $(document).on('keyup.tsconfirm', function (e) {
        if ( e.keyCode === 27 ) {
            hideAndDestroyConfirm()
        }
    })

    $('[data-ts-confirm]').on('click', function (e) {
        e.preventDefault();
        var msg = $(this).data('ts-confirm');
        $(this).attr('id', generateId());
        appendConfirm(msg, $(this).attr('id'));
    });

    function appendConfirm(msg, id) {
        console.log(id)
        var confirmTemplate = '';
        confirmTemplate += '<div class="ts-confirm-overlay">';
        confirmTemplate += '    <div class="ts-confirm">';
        confirmTemplate += '        <div class="ts-confirm-content">';
        confirmTemplate += '            <p>' + msg + '</p>';
        confirmTemplate += '            <button class="ts-confirm-cancel">Cancel</button>';
        confirmTemplate += '            <button class="ts-confirm-ok" data-id="' + id + '">OK</button>';
        confirmTemplate += '        </div>';
        confirmTemplate += '    </div>';
        confirmTemplate += '</div>';

        $body.append(confirmTemplate);
    }

    function hideAndDestroyConfirm() {
        $('.ts-confirm-overlay').remove();
    }

    function generateId() {
        return Date.now();
    }

})(window, document, jQuery);

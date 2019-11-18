
(function (window, document, $, undefined) {

    buildNotification()
    bindEvent()

    const IS_ACTIVE = 'is-active'
    const AUTO_HIDE_DURATION = 7000
    const STATES = {
        success: 'tf-notification--success',
        warning: 'tf-notification--warning',
        error: 'tf-notification--error'
    }

    let $notif = $('.tf-notification')
    let $notifContent = $('.tf-notification-content')

    function show(msg, state) {
        $notifContent
            .empty()
            .append(msg)

        $notif
            .addClass(STATES[state])
            .addClass(IS_ACTIVE)

        setTimeout(removeNotification, AUTO_HIDE_DURATION)
    }

    function removeNotification() {
        $('.tf-notification').removeClass(IS_ACTIVE)
    }

    function buildNotification() {
        let $el = $(`
            <div class="tf-notification">
                <div class="container">
                    <div class="tf-notification-content"></div>
                    <button type="button">&times;</button>
                </div>
            </div>
        `)

        $(document.body).append($el)
    }

    function bindEvent() {
        $(document.body).on('click', '.tf-notification button', removeNotification)
    }

    window.tsNotif = {
        show
    }

})(window, document, jQuery);

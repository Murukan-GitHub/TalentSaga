
(function (window, document, $, undefined) {

    $(document.body).append(`
        <div class="ts-alert">
            <div class="ts-alert-content"></div>
            <button class="ts-alert-btn">ok</button>
        </div>
    `)

    let $tsAlert = $('.ts-alert')
    let $tsAlertContent = $('.ts-alert-content')
    let $tsAlertBtn = $('.ts-alert-btn')
    let currentActiveElement

    $tsAlertBtn.on('click', e => {
        $tsAlert.hide()
        currentActiveElement.focus()
    })

    window.tsAlert = function (msg) {
        currentActiveElement = document.activeElement

        $tsAlertContent
            .empty()
            .append(msg)
        $tsAlert.fadeIn(100)
        $tsAlertBtn.focus()
    }

})(window, document, jQuery);

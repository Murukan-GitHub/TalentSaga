/*! [PROJECT_NAME] | Suitmedia */

((window, document, undefined) => {

    const app = angular.module('talentSaga', [])

    app.directive('onFinishRender', ['$timeout', function ($timeout) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                if ( scope.$last ) {
                    $timeout(function(){
                        scope.$emit(attr.onFinishRender)
                    })
                }
            }
        }
    }])

    const IS_ACTIVE = 'is-active'

    const path = {
        css: `${myPrefix}assets/css/`,
        js : `${myPrefix}assets/js/vendor/`
    }

    const assets = {
        _slick      : `${path.js}slick.min.js`,
        _handlebars : `${path.js}handlebars.min.js`,
        _headroom   : `${path.js}headroom.min.js`,
        _objectFit  : `${path.js}object-fit.min.js`,
        _fitText    : `${path.js}fitter-happier-text.min.js`,
        _validate   : `${path.js}baze.validate.min.js`,
        _sprintf    : `${path.js}sprintf.min.js`,
        _datepicker : `${path.js}datepicker.min.js`,
        _selectize  : `${path.js}selectize.min.js`,
        _rangeSlider: `${path.js}rangeslider.min.js`,
        _numeral    : `${path.js}numeral.min.js`,
        _scrollbar  : `${path.js}perfect-scrollbar.min.js`,
        _tsAlert    : `${myPrefix}assets/js/ts-alert.min.js`,
        _gmaps      : `https://maps.googleapis.com/maps/api/js?key=AIzaSyBs1a7-LDLm06tVeneBPvKhY_FBTR51dpE`,
        _debounce   : `${path.js}jquery.ba-throttle-debounce.min.js`
    }

    const Site = {

        enableActiveStateMobile() {
            if ( document.addEventListener ) {
                document.addEventListener('touchstart', () => {}, true)
            }
        },

        WPViewportFix() {
            if ( navigator.userAgent.match(/IEMobile\/10\.0/) ) {
                let style   = document.createElement('style'),
                    fix     = document.createTextNode('@-ms-viewport{width:auto!important}')

                style.appendChild(fix)
                document.getElementsByTagName('head')[0].appendChild(style)
            }
        },

        objectFitPolyfill() {
            load(assets._objectFit).then(() => {
                objectFitImages()
            })
        },

        siteHeader() {
            Promise.all([
                exist('.site-header--home'),
                load(assets._headroom)
            ]).then(res => {
                let [ $header ] = res
                let headroom = new Headroom($header[0])
                headroom.init()
            }).catch(noop)
        },

        heroHeading() {
            Promise.all([
                exist('.hero-heading'),
                load(assets._fitText)
            ]).then(res => {
                let [ $heading ] = res

                fitterHappierText($heading.children('.hero-heading-fit-text').toArray(), {
                    paddingY: -1
                })
            }).catch(noop)
        },

        enhanceSelect() {
            Promise.all([
                exist('[data-enhance-select]'),
                load(assets._selectize)
            ]).then( res => {
                let [ $input ] = res

                $input.selectize()
            }).catch(noop)
        },

        popup() {
            exist('.popup').then( $popup => {
                $popup.on('click', e => {
                    let $target = $(e.currentTarget)

                    if ( !underMedium() && (window.innerWidth - $target.offset().left) < 300 ) {
                        $target.addClass('popup--right')
                    }

                    e.stopPropagation()
                })

                $popup.on('click', '.popup-btn', e => {
                    e.preventDefault()
                    let $parent = $(e.currentTarget).parent()

                    if ( $parent.hasClass(IS_ACTIVE) ) {
                        $parent.removeClass(IS_ACTIVE)
                    } else {
                        $popup.removeClass(IS_ACTIVE)
                        $(e.currentTarget).parent().toggleClass(IS_ACTIVE)
                    }
                })

                $(document.body).on('click', e => {
                    $('.popup').removeClass(IS_ACTIVE)
                })
            }).catch(noop)
        },

        toggleSearch() {
            let $search = $('.site-header-search')

            $('.site-header-search-toggle').on('click', e => {
                $search.toggleClass(IS_ACTIVE)
            })
        },

        findTalentInfoSlider() {
            exist('.find-talent-info-slider').then( $slider => {
                $slider.slick({
                    arrows: false,
                    accessibility: false,
                    dots: true,
                    mobileFirst: true,
                    draggable: false,
                    autoplay: true,
                    autoplaySpeed: 3000,
                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                centerMode: true,
                                variableWidth: true,
                            }
                        }
                    ]
                })
            }).catch(noop)
        },

        successStory() {
            Promise.all([
                exist('.success-story-slider'),
                load(assets._handlebars)
            ])
            .then(getDataUrl)
            .then(loadJSON)
            .then(preloadImages)
            .then(initSlider)
            .then(handleSliderNav)
            .catch(noop)

            function getDataUrl(res) {
                return new Promise((resolve, reject) => {
                    let uri = res[0].data('content')

                    if ( uri ) {
                        resolve(uri)
                    } else {
                        reject(uri)
                    }
                })
            }

            function preloadImages(stories) {
                return new Promise((resolve, reject) => {
                    stories.success_stories.forEach( story => {
                        preload(story.imageThumb)
                        preload(story.imageBackground)
                    })

                    resolve(stories)
                })
            }

            function initSlider(stories) {
                return new Promise((resolve, reject) => {
                    let $elem = $('.success-story-slider')
                    let templateSlider = $('#templateSuccessStorySlider').html()
                    templateSlider = Handlebars.compile(templateSlider)

                    $elem.append(templateSlider(stories))

                    createSlider($elem, {
                        arrows: false,
                        infinite: false
                    })

                    resolve([
                        stories,
                        $elem
                    ])
                })
            }

            function handleSliderNav(res) {
                return new Promise((resolve, reject) => {
                    let [ stories, $elem ] = res
                    let $navPrev = $('.success-story-nav--prev')
                    let $navNext = $('.success-story-nav--next')
                    let templatePeek = $('#successStoryPeek').html()
                    templatePeek = Handlebars.compile(templatePeek)

                    $navPrev.addClass(IS_ACTIVE)
                    $navNext.addClass(IS_ACTIVE)

                    $navPrev.on('mouseover', '.success-story-peek', preventBubbling)
                    $navNext.on('mouseover', '.success-story-peek', preventBubbling)

                    $navPrev.on('mouseover', getSneakPeak('prev'))
                    $navNext.on('mouseover', getSneakPeak('next'))

                    $navPrev.on('click', changeSlide('prev'))
                    $navNext.on('click', changeSlide('next'))

                    function getSneakPeak(action) {
                        return function (e) {
                            if ( underMedium() ) return;

                            let $this = $(e.currentTarget)
                            let currentIndex = $elem.slick('slickCurrentSlide')
                            let peakIndex;

                            if ( action === 'prev' ) {
                                peakIndex = currentIndex - 1

                                $this.empty()

                                if ( peakIndex < 0 ) return;
                            } else if ( action === 'next' ) {
                                peakIndex = currentIndex + 1

                                $this.empty()

                                if ( peakIndex === stories.success_stories.length ) return;
                            }

                            $(e.currentTarget).append(templatePeek(stories.success_stories[peakIndex]))
                        }
                    }

                    function changeSlide(action) {
                        return function (e) {
                            if ( action === 'prev' ) {
                                $elem.slick('slickPrev')
                            } else if ( action === 'next' ) {
                                $elem.slick('slickNext')
                            }

                            $(e.currentTarget).trigger('mouseover')
                        }
                    }
                })
            }

            function preventBubbling(e) {
                e.stopPropagation()
            }
        },

        formValidation() {
            Promise.all([
                exist('[data-validate]'),
                load(assets._sprintf),
                load(assets._validate)
            ]).then(res => {
                let [ $form ] = res

                $form.bazeValidate()
            }).catch(noop)
        },

        datepicker() {
            Promise.all([
                exist('[data-datepicker]')
            ]).then(res => {
                let [ $inputs ] = res

                $inputs.each((i, input) => {
                    let $input = $(input)
                    const minDate = $input.data('min-date')
                    const opts = {
                        autoHide: true
                    }

                    if ( minDate === 'TODAY' ) {
                        opts.startDate = new Date()
                    }

                    handleChange($input)

                    $input.datepicker(opts)
                })
            }).catch(noop)

            function handleChange($input) {
                if ( $input.data('start-date-for') ) {
                    let $endDateInput = $(`${$input.data('start-date-for')}`)

                    $input.on('pick.datepicker', e => {
                        let startDate = $input.datepicker('getDate')
                        let endDate = $endDateInput.datepicker('getDate')

                        $endDateInput.datepicker('setStartDate', startDate)

                        if ( startDate.getTime() > endDate.getTime() ) {
                            $endDateInput.datepicker('setDate', startDate)
                        }
                    })
                }
            }
        },

        timepicker() {
            exist('[data-timepicker]').then( $inputs => {
                $inputs.timepicker({
                    disableTextInput: true,
                    disableTouchKeyboard: true,
                    show2400: true,
                    timeFormat: 'H:i'
                })
            }).catch(noop)
        },

        portofolioForm() {
            app.controller('PortofolioFormController', ['$scope', function ($scope) {
                $scope.portofolios = [
                    {
                        id: 1,
                        date: '',
                        name: '',
                        experience: '',
                        url: ''
                    }
                ]

                $scope.addPortofolio = () => {
                    $scope.portofolios.push({
                        id: getUID(),
                        date: '',
                        name: '',
                        experience: '',
                        url: ''
                    })
                }

                $scope.removePortofolio = (portofolioId) => {
                    $scope.portofolios.forEach((portofolio, index, array) => {
                        if ( portofolio.id === portofolioId ) {
                            array.splice(index, 1)
                        }
                    })
                }

                $scope.$on('ngRepeatFinished', function () {
                    Site.datepicker()
                })
            }])

            function getUID() {
                return (new Date()).getTime()
            }
        },

        uploadPhotosForm() {
            app.controller('UploadPhotosController', ['$scope', function ($scope) {
                $scope.showUploader = false
                $scope.photosToUpload = [{},{}]
                $scope.videoUrls = [{}]

                $scope.addInputFile = () => {
                    $scope.photosToUpload.push({})
                }

                $scope.addNewURLVideo = () => {
                    $scope.videoUrls.push({})
                }

                $scope.$on('attachInputListener', () => {
                    $scope.showUploader = true

                    // $('.upload-photo')
                    //     .off('drag dragstart dragend dragover dragenter dragleave drop', preventDefaultEvent)
                    //     .off('dragover dragenter', addDragEnterClass)
                    //     .off('dragleave dragend drop', removeDragEnterClass)
                    //     .on('drag dragstart dragend dragover dragenter dragleave drop', preventDefaultEvent)
                    //     .on('dragover dragenter', addDragEnterClass)
                    //     .on('dragleave dragend drop', removeDragEnterClass)

                    $('.upload-photo-input')
                        .off('change', handleEvent)
                        .on('change', handleEvent)
                })

                function preventDefaultEvent(e) {
                    e.preventDefault()
                    e.stopPropagation()
                }

                function addDragEnterClass(e) {
                    $(e.currentTarget).addClass('is-dragover')
                }

                function removeDragEnterClass(e) {
                    $(e.currentTarget).removeClass('is-dragover')
                }

                function handleEvent(e) {
                    let $this = $(e.currentTarget)
                    let $label = $this.parent()
                    $label.attr('data-filename', $this[0].files[0].name)
                    $label.addClass(IS_ACTIVE)
                }
            }])
        },

        selectizeInput() {
            Promise.all([
                exist('[data-selectize]'),
                load(assets._selectize)
            ]).then( res => {
                let [ $input ] = res

                $input.removeClass('invisible')
                $input.selectize({
                    plugins: ['remove_button'],
                    persist: false
                })
            }).catch(noop)
        },

        cityAvailability() {
            exist('[data-city-availability]').then( $input => {
                $input.on('click', e => {
                    let $target = $(e.currentTarget).data('city-availability')
                    $target = $($target)
                    if ( e.currentTarget.checked ) {
                        $target.removeClass('city-availability-hidden')
                    } else {
                        $target.addClass('city-availability-hidden')
                    }
                })
            }).catch(noop)
        },

        priceRange() {
            Promise.all([
                exist('#inputPriceRange'),
                load(assets._numeral),
                load(assets._rangeSlider)
            ]).then( res => {
                let [ $input ] = res

                numeral.language('id', {
                    delimiters: {
                        thousands: '.',
                        decimal: ','
                    }
                });

                numeral.language('id');

                $('input[type="range"]').rangeslider({
                    polyfill: false,
                    onInit() {
                        this.$handle.attr('data-value', numeral(this.value).format('0,0'))
                    },
                    onSlide(position, value) {
                        this.$handle.attr('data-value', numeral(value).format('0,0'))
                    }
                })
            }).catch(noop)
        },

        categoryFilter() {
            exist('.category-filter').then( $filter => {
                $('.category-layout-filter-trigger').on('click', 'button', e => {
                    e.preventDefault()

                    $filter.toggleClass(IS_ACTIVE)
                })
            }).catch(noop)

            app.controller('CategoryFilterController', ['$scope', '$http', '$timeout', '$element', function ($scope, $http, $timeout, $element) {
                let countriesUrl = $('#selectCountry').data('countries')
                let $selectCountry = $('#selectCountry')
                let $selectCity = $('#selectCity')

                $scope.isGettingCities = true

                getCountries(countriesUrl)
                    .then(cities => {
                        $scope.$apply(() => {
                            $scope.cities = cities
                            $scope.filterCities = $scope.filterCountry.cities
                                .filter(c => c.selected === true)
                                .reduce(c => c)

                            $scope.isGettingCities = false

                            $timeout(function () {
                                $selectCountry.selectize()
                                $selectCity.selectize()
                            }, 100);
                        })
                    })
                    .catch(noop)

                $scope.getCitiesByCountry = () => {
                    $scope.isGettingCities = true

                    let cities = $scope.countries
                        .filter( c => c.value === $scope.filterCountry )
                        .map( c => c.cities )
                        .reduce( cities => cities)

                        $scope.cities = cities
                        $scope.filterCities = $scope.cities[0]
                        $scope.isGettingCities = false
                        $selectCity[0].selectize.destroy()

                        $timeout(function () {
                            $selectCity.selectize();
                            $selectCountry[0].selectize.setValue($scope.filterCountry)
                        }, 0);
                }

                function getCountries(url) {
                    return new Promise((resolve, reject) => {
                        $http.get(url).then( res => {
                            $scope.countries = res.data
                            $scope.filterCountry = res.data
                                .filter(c => c.selected === true)
                                .reduce(c => c)

                            resolve($scope.filterCountry.cities)
                        }, res => {
                            reject(res)
                        })
                    })
                }
            }])
        },

        talentGallery() {
            exist('.talent-profile-images').then( $gallery => {
                $gallery.find('a').fancybox({
                    slideShow: false,
                    fullScreen: false,
                    thumbs: false
                })
            }).catch(noop)
        },

        talentGalleryCustomScroll() {
            Promise.all([
                exist('.talent-profile-gallery'),
                load(assets._scrollbar),
                load(assets._debounce)
            ]).then( res => {
                let [ $el ] = res


                if ( isLarge() ) {
                    Ps.initialize($el[0], {
                        maxScrollbarLength: 20
                    })
                }

                $(window).on('resize', $.debounce(300, () => {
                    if ( isLarge() ) {
                        Ps.initialize($el[0], {
                            maxScrollbarLength: 20
                        })
                    } else {
                        Ps.destroy($el[0])
                    }
                }))
            }).catch(noop)
        },

        modal() {
            const DEFAULT_OVERFLOW = document.documentElement.style.overflow
            let $modal = $('.modal')
            let $dialog = $modal.find('.modal-dialog')
            let $content = $modal.find('.modal-dialog-content')
            let $closeBtn = $modal.find('.modal-dialog-close')
            let $trigger = $('[data-modal]')

            $modal.on('click', closeModal)
            $closeBtn.on('click', closeModal)
            $(document).on('keyup', e => {
                if ( e.keyCode === 27 ) {
                    closeModal()
                }
            })

            $dialog.on('click', e => {
                e.stopPropagation()
            })

            $trigger.on('click', e => {
                e.preventDefault()

                let content = $(e.currentTarget).data('modal')
                content = $(content).html()
                showModal(content)
            })

            function showModal(content) {
                setDocOverflow('hidden')

                $content
                    .empty()
                    .append(content)

                $modal.addClass(IS_ACTIVE)

                Site.formValidation()
                Site.datepicker()
                Site.timepicker()
            }

            function closeModal() {
                setDocOverflow(DEFAULT_OVERFLOW)
                $modal.removeClass(IS_ACTIVE)
                $(document).trigger('click')
            }

            function setDocOverflow(value) {
                document.documentElement.style.overflow = value
            }
        },

        contactMap() {
            Promise.all([
                exist('.contact-map'),
                load(assets._gmaps)
            ]).then( res => {
                let [ $container ] = res
                let container = $container[0]
                let coords = $container.data('coords')

                let map = new google.maps.Map(container, {
                    center: coords,
                    zoom: 14,
                    scrollwheel: false,
                    clickableIcons: false
                })

                let marker = new google.maps.Marker({
                    position: coords,
                    map
                })
            }).catch( e => {

            })
        },

        editAvatarField() {
            exist('.edit-avatar-field').then( $container => {
                let $input = $container.find('#inputAvatar')
                let $img = $container.find('img')
                const $cropField = $('.avatar-crop-field')
                const DIMENSION = 200

                $input.on('change', e => {
                    let { files } = e.currentTarget

                    if ( files && files[0] ) {
                        let reader = new FileReader()

                        reader.onload = e => {
                            showModal()
                            initCroppie($img, e.target.result)
                        }

                        reader.readAsDataURL(files[0])
                    }
                })

                $('.avatar-crop-modal-close').on('click', e => {
                    hideModalAndDestroyCroppie($cropField)
                })

                function showModal() {
                    $('.avatar-crop-modal').addClass(IS_ACTIVE)
                }

                function hideModalAndDestroyCroppie($croppieInstance) {
                    $croppieInstance.croppie('destroy')
                    $('.avatar-crop-modal').removeClass(IS_ACTIVE)
                    $input.val(null)
                }

                function initCroppie($preview, base64Img) {
                    $cropField.croppie({
                        url: base64Img,
                        boundary: {
                            width: DIMENSION,
                            height: DIMENSION
                        },
                        viewport: {
                            width: DIMENSION,
                            height: DIMENSION,
                            type: 'square'
                        },
                        mouseWheelZoom: false
                    })

                    $('.avatar-crop-modal-set-btn')
                        .off('click.setAvatar')
                        .on('click.setAvatar', e => {
                            $cropField.croppie('result', {
                                type: 'base64',
                                size: {
                                    width: DIMENSION,
                                    height: DIMENSION
                                },
                                quality: 1
                            }).then(res => {
                                document.querySelector('#croppedInputAvatar').value = res
                                $preview.attr('src', res)
                                hideModalAndDestroyCroppie($cropField)
                            })
                        })
                }
            }).catch(noop)
        },

        imageCrop() {
            exist('.js-image-crop').then( $container => {
                const $input = $container.find('.js-image-crop-input')
                const $img = $container.find('.js-image-crop-preview')
                const $hiddenInput = $container.find('.js-image-crop-hidden-input')
                const $cropField = $('.image-crop-field')

                $input.on('change', e => {
                    let { files } = e.currentTarget
                    let dummyImg = document.createElement('img')

                    if ( files && files[0] ) {
                        let reader = new FileReader()

                        dummyImg.onload = (e) => {
                            initCroppie($img, e.target.src, {
                                width: e.target.width,
                                height: e.target.height
                            })
                        }

                        reader.onload = e => {
                            showModal()
                            dummyImg.src = e.target.result
                        }

                        reader.readAsDataURL(files[0])
                    }
                })

                $('.image-crop-modal-close').on('click', e => {
                    hideModalAndDestroyCroppie($cropField)
                })

                function showModal() {
                    $('.image-crop-modal').addClass(IS_ACTIVE)
                }

                function hideModalAndDestroyCroppie($croppieInstance) {
                    $croppieInstance.croppie('destroy')
                    $('.image-crop-modal').removeClass(IS_ACTIVE)
                    $input.val(null)
                }

                function initCroppie($preview, base64Img, dimension) {
                    const size = getMinimumImageSize(dimension.width, dimension.height)
                    $cropField.croppie({
                        url: base64Img,
                        boundary: {
                            width: size.width,
                            height: size.height
                        },
                        viewport: {
                            width: Math.floor(size.width / 1.1),
                            height: Math.floor(size.height / 1.1)
                        },
                        mouseWheelZoom: false
                    })

                    $('.image-crop-modal-set-btn').off('click.setAvatar').on('click.setAvatar', e => {
                        $cropField.croppie('result', {
                            type: 'base64',
                            size: {
                                width: dimension.width,
                                height: dimension.height
                            },
                            quality: 1
                        }).then(res => {
                            $hiddenInput.val(res)
                            $preview.attr('src', res)
                            hideModalAndDestroyCroppie($cropField)
                        })
                    })
                }

                function getImageRatio(width, height) {
                    if ( width > height )
                        return Number(parseFloat(width / height).toFixed(2))
                    return Number(parseFloat(height / width).toFixed(2))
                }

                function getMinimumImageSize(width, height) {
                    const minimumSize = 400
                    const ratio = getImageRatio(width, height)

                    if ( width > height ) {
                        return {
                            width: minimumSize,
                            height: Math.floor(minimumSize / ratio)
                        }
                    }

                    return {
                        height: minimumSize,
                        width: Math.floor(minimumSize / ratio)
                    }
                }
            }).catch(noop)
        },

        formAccInfo() {
            Promise.all([
                exist('.js-form-acc-information'),
                load(assets._sprintf),
                load(assets._validate)
            ]).then( res => {
                let [ $form ] = res

                $form.bazeValidate({
                    onValidated(e) {
                        let pass = $('#inputPassword').val()
                        let confirmPass = $('#inputConfirmPassword').val()

                        if ( pass !== confirmPass ) {
                            tsAlert('Password and password confirmation do not match')
                            e.preventDefault()
                        }
                    }
                })
            }).catch(noop)
        },

        accordion() {
            exist('.js-accordion').then( $accordion => {
                $accordion.on('click', '.js-accordion-title', e => {
                    let $this = $(e.currentTarget)
                    let $content = $this.next('.js-accordion-content')

                    if ( $content.length ) {
                        $this.toggleClass(IS_ACTIVE)
                        $content.slideToggle()
                    }
                })
            }).catch(noop)
        },

        tab() {
            exist('.js-tab').then( $tab => {
                $tab.on('click', '.js-tab-anchor', e => {
                    e.preventDefault()
                    let $this = $(e.currentTarget)

                    if ( $this.hasClass(IS_ACTIVE) ) return;

                    let $target = $this.attr('href') || $this.attr('data-target')
                    $target = $($target)

                    $this.siblings('.js-tab-anchor').removeClass(IS_ACTIVE)
                    $this.addClass(IS_ACTIVE)

                    $target.siblings('.js-tab-panel').removeClass(IS_ACTIVE)
                    $target.addClass(IS_ACTIVE)
                })
            }).catch(noop)
        },

        selectCountryCityController() {
            app.controller('SelectCountryCityController', ['$scope', '$http', '$element', '$timeout', function ($scope, $http, $element, $timeout) {
                load(assets._selectize).then(() => {
                    let countriesUrl = $element.data('countries')

                    $scope.isGettingCities = true

                    getCountries(countriesUrl)
                        .then(cities => {
                            $scope.$apply(() => {
                                $scope.cities = cities

                                let selectedCity = $scope.cities.filter(c => c.selected === true)

                                $scope.filterCities = selectedCity[0].value || $scope.filterCountry.cities[0].value

                                $scope.isGettingCities = false

                                $timeout(() => {
                                    $element.find('select').selectize()
                                }, 100)
                            })
                        })
                        .catch(noop)

                    $scope.getCitiesByCountry = () => {
                        $scope.isGettingCities = true

                        let cities = $scope.countries
                            .filter( c => c.value === $scope.filterCountry )
                            .map( c => c.cities )
                            .reduce( cities => cities)

                        $scope.cities = cities

                        if ( $scope.cities.length ) {
                            $scope.filterCities = $scope.cities[0].value
                        }

                        $scope.isGettingCities = false

                        $('[ng-model="filterCities"]')[0].selectize.destroy();
                        $timeout(function () {
                            $('[ng-model="filterCities"]').selectize();
                        }, 0);
                    }

                    function getCountries(url) {
                        return new Promise((resolve, reject) => {
                            $http.get(url).then( res => {
                                $scope.countries = res.data

                                let selectedCountry = $scope.countries.filter(c => c.selected === true)
                                let countries = selectedCountry[0] || $scope.countries[0]

                                $scope.filterCountry = selectedCountry[0].value || $scope.countries[0].value

                                resolve(countries.cities)
                            }, res => {
                                reject(res)
                            })
                        })
                    }
                })
            }])
        },

        talentCategoryExpertiseController() {
            app.controller('TalentCategoryExpertiseController', ['$scope', '$http', '$element', function ($scope, $http, $element) {
                let categoriesUrl = $element.data('categories')
                const maxCount = Number(inputDescribe.getAttribute('data-max-char')) || 200

                $scope.isLoading = true

                $scope.charCount = inputDescribe.value.length || 0

                $http.get(categoriesUrl).then( res => {
                    $scope.talentCategories = res.data
                    let selectedCategory = $scope.talentCategories.filter(c => c.selected === true)

                    $scope.talentCategory = selectedCategory[0] || $scope.talentCategories[0]
                    $scope.talentExpertises = getExpertiseByCategory($scope.talentCategory.value)
                    $scope.isLoading = false
                    $element.css('opacity', 1)
                })

                $scope.getExpertise = () => {
                    $scope.talentExpertises = getExpertiseByCategory($scope.talentCategory)
                    $scope.talentCategory = $scope.talentCategories
                        .filter(c => c.value === $scope.talentCategory)
                        .reduce(c => c)
                }

                inputDescribe.addEventListener('keyup', (e) => {
                    if ( inputDescribe.value.length > maxCount ) {
                        inputDescribe.value = inputDescribe.value.substr(0, inputDescribe.value.length - 1)
                        return false
                    }

                    $scope.$apply(() => {
                        $scope.charCount = inputDescribe.value.length
                    })
                })

                function getExpertiseByCategory(value) {
                    return $scope.talentCategories
                        .filter(c => c.value === value)
                        .map(c => c.expertise)
                        .reduce(expertise => expertise)
                }
            }])
        },

        priceEstimation() {
            exist('#priceEstimationContainer').then( $container => {
                let $contentBody = $container.find('#priceEstimationContent')
                let $addBtn = $container.find('.price-estimation-add-btn')
                let template = $container.find('template').html()

                $addBtn.on('click', () => {
                    $contentBody.append(template)
                })

                $container.on('click', '.price-estimation-remove-btn', (e) => {
                    $(e.currentTarget).parent().remove()
                })
            }).catch(noop)
        },

        drop() {
            const dropzone = document.querySelector('#uploadPhotosDropZone')

            if ( !dropzone ) return

            const previewContainer = document.querySelector('#uploadPhotosDropZonePreview')
            const modal = document.querySelector('#uploadPhotosModal')
            const modalClose = modal.querySelector('.upload-photos-modal-dialog-close')
            const modalTrigger = document.querySelector('.js-upload-photos-modal-trigger')
            const inputFile = dropzone.querySelector('#uploadPhotosInput')
            const cta = document.querySelector('#uploadPhotosCta')
            const label = dropzone.querySelector('.upload-photos-dropzone-label')
            const IS_DRAGGING = 'is-dragging'

            modalTrigger.addEventListener('click', e => {
                modal.classList.toggle(IS_ACTIVE)
                disablePageScroll()
            })

            inputFile.addEventListener('change', e => {
                const files = [...e.currentTarget.files]
                processFiles(files)
            })

            dropzone.addEventListener('dragover', e => {
                e.stopPropagation()
                e.preventDefault()
                e.target.classList.add(IS_DRAGGING)
                e.dataTransfer.dropEffect = 'copy'
            })

            label.addEventListener('dragover', e => {
                dropzone.classList.add(IS_DRAGGING)
            })

            dropzone.addEventListener('dragleave', e => {
                e.target.classList.remove(IS_DRAGGING)
            })

            dropzone.addEventListener('drop', e => {
                e.stopPropagation()
                e.preventDefault()
                e.target.classList.remove(IS_DRAGGING)
                const files = [...e.dataTransfer.files]
                processFiles(files)
            })

            modalClose.addEventListener('click', closeModal)
            $(document).on('keyup.modalPhotos', e => {
                if ( e.keyCode === 27 ) {
                    closeModal()
                }
            })

            $(document.body).on('click.modalDrop', '.upload-photos-dropzone-previews-item-remove', e => {
                $(e.currentTarget).parent().remove()

                if ( !$('.upload-photos-dropzone-previews-item-remove').length ) {
                    label.classList.remove('hidden')
                }
            })

            function processFiles(files) {
                files.map(file => {
                    const reader = new FileReader()
                    const wrapper = document.createElement('div')
                    const inputHiddenImage = document.createElement('input')
                    const inputHiddenFileName = document.createElement('input')
                    const preview = document.createElement('img')
                    const btnRemove = document.createElement('button')

                    wrapper.classList.add('upload-photos-dropzone-previews-item')
                    btnRemove.innerHTML = '<span class="fa fa-fw fa-trash"></span>'
                    btnRemove.classList.add('upload-photos-dropzone-previews-item-remove')
                    wrapper.appendChild(btnRemove)

                    reader.onload = (evt) => {
                        inputHiddenImage.type = 'hidden'
                        inputHiddenImage.value = evt.target.result
                        inputHiddenImage.name = 'imagefiles[]'

                        inputHiddenFileName.type = 'hidden'
                        inputHiddenFileName.value = file.name
                        inputHiddenFileName.name = 'imagefilenames[]'

                        preview.width = 150
                        preview.draggable = false
                        preview.style.margin = '5px'
                        preview.src = evt.target.result
                        wrapper.appendChild(inputHiddenImage)
                        wrapper.appendChild(inputHiddenFileName)
                        wrapper.appendChild(preview)
                        previewContainer.appendChild(wrapper)

                        cta.classList.remove('hidden')
                        label.classList.add('hidden')
                    }

                    reader.readAsDataURL(file)
                })
            }

            function closeModal() {
                modal.classList.remove(IS_ACTIVE);

                [...previewContainer.children].map(child => {
                    child.parentElement.removeChild(child)
                })

                label.classList.remove('hidden')
                cta.classList.add('hidden')
                inputFile.value = null
                enablePageScroll()
            }
        },

        inputAutoPreview() {
            const inputs = document.querySelectorAll('[data-input-auto-preview]')

            if ( !inputs.length ) return;

            [...inputs].forEach(input => {
                let preview = input.getAttribute('data-input-auto-preview')
                preview = document.querySelector(preview)

                input.addEventListener('change', e => {
                    const file = e.target.files[0]
                    const reader = new FileReader()
                    reader.onload = (evt) => {
                        preview.src = evt.target.result
                    }
                    reader.readAsDataURL(file)
                })
            })
        },

        counter() {
            exist('.js-counter').then($counter => {
                const $input = $counter.find('input')
                const $btnDec = $counter.find('.js-counter-dec')
                const $btnInc = $counter.find('.js-counter-inc')
                const min = Number($input.attr('min'))
                const max = Number($input.attr('max'))
                const value = currentValue()

                $btnDec.on('click', e => {
                    if ( currentValue() === min ) return;
                    $input.val(currentValue() - 1)
                })

                $btnInc.on('click', e => {
                    if ( currentValue() === max ) return;
                    $input.val(currentValue() + 1)
                })

                function currentValue() {
                    return Number($input.val())
                }
            }).catch(noop)
        }
    }

    Promise.all([
        load(assets._slick),
        load(assets._tsAlert),
        load(assets._datepicker)
    ]).then(() => {
        for (let fn in Site) {
            Site[fn]()
        }
        window.Site = Site

        angular.bootstrap(document, ['talentSaga'])
    })

    function exist(selector) {
        return new Promise((resolve, reject) => {
            let $elem = $(selector)

            if ( $elem.length ) {
                resolve($elem)
            } else {
                reject(`no element found for ${selector}`)
            }
        })
    }

    function load(url) {
        return new Promise((resolve, reject) => {
            Modernizr.load({
                load: url,
                complete: resolve
            })
        })
    }

    function loadJSON(url) {
        return new Promise((resolve, reject) => {
            fetch(url).then(res => {
                if ( res.ok )
                    return res.json()

                reject('Network response not ok')
            }).then(data => {
                resolve(data)
            }).catch(noop)
        })
    }

    function underMedium() {
        return Modernizr.mq('screen and (max-width: 767px)')
    }

    function isLarge() {
        return Modernizr.mq('screen and (min-width: 64em)')
    }

    function createSlider($elem, opts) {
        let defaults = {
            accessibility: false,
            draggable: false
        }

        $elem.slick($.extend(true, opts, defaults))
    }

    function preload(src) {
        (new Image()).src = src
    }

    function noop(e) {

    }

    function disablePageScroll() {
        document.documentElement.style.overflow = 'hidden'
    }

    function enablePageScroll() {
        document.documentElement.style.overflow = 'scroll'
    }

})(window, document)

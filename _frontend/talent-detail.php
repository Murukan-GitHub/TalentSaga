<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="talent-profile">
                    <figure class="talent-profile-main-image-wrapper">
                        <img class="talent-profile-main-image" src="assets/img/talent-detail-main-image.jpg" alt="">
                    </figure>

                    <div class="container">
                        <section class="talent-profile-main-info">
                            <div class="rate rate--display">
                                <span class="rate-label">70</span>

                                <input class="sr-only" type="radio" value="5">
                                <label><span class="fa fa-fw fa-star-o"></span></label>

                                <input class="sr-only" type="radio" value="4">
                                <label><span class="fa fa-fw fa-star-o"></span></label>

                                <input class="sr-only" type="radio" value="3" checked>
                                <label><span class="fa fa-fw fa-star-o"></span></label>

                                <input class="sr-only" type="radio" value="2">
                                <label><span class="fa fa-fw fa-star-o"></span></label>

                                <input class="sr-only" type="radio" value="1" checked>
                                <label><span class="fa fa-fw fa-star-o"></span></label>
                            </div>

                            <h2 class="talent-profile-name">Iman Aditya</h2>
                            <h3 class="talent-profile-job">Model professional</h3>

                            <div class="v-center block">
                                <span>Male, 25</span>
                                <span><i class="fa fa-fw fa-map-pin"></i> Bandung</span>
                                <span>H: 178cm</span>
                                <span>W: 70Kg</span>
                            </div>

                            <hr>

                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium numquam beatae veritatis, nesciunt ratione tempore quam, velit impedit libero error repellat doloribus eligendi? Reiciendis, animi molestiae facere minima cum mollitia.</p>

                            <p>
                                <a href="#">
                                    <span class="fa fa-fw fa-facebook-official"></span>
                                </a>
                                <a href="#">
                                    <span class="fa fa-fw fa-twitter"></span>
                                </a>
                                <a href="#">
                                    <span class="fa fa-fw fa-youtube"></span>
                                </a>
                                <a href="#">
                                    <span class="fa fa-fw fa-instagram"></span>
                                </a>
                            </p>

                            <a href="#">
                                <span class="fa fa-fw fa-exclamation-triangle"></span>
                                <small>Report this talent</small>
                            </a>
                        </section>

                        <section class="talent-profile-description">
                            <figure>
                                <img class="talent-profile-avatar circle" src="//placehold.it/150x150">
                            </figure>

                            <h3 class="h2">
                                <span class="sr-only">Fee: </span><b>Rp 5.000.000</b>
                            </h3>

                            <a class="btn btn--tosca btn--outline block" href="#" data-modal="#templateContact">Contact me</a>
                            <template id="templateContact">
                                <h2>Start Enquiry</h2>

                                <form action="" data-validate>
                                    <div class="bzg">
                                        <div class="bzg_c" data-col="l6">
                                            <div class="block">
                                                <label for="eventLocation">Where is your event</label>
                                                <input class="form-input" id="eventLocation" type="text" required>
                                            </div>
                                        </div>
                                        <div class="bzg_c" data-col="l6">
                                            <div class="block">
                                                <span>When is your event</span>
                                                <div class="bzg">
                                                    <div class="bzg_c" data-col="m6">
                                                        <input class="form-input" id="eventTimeStart" type="text" placeholder="Start date" data-datepicker data-start-date-for="#eventTimeEnd" data-min-date="TODAY" required>
                                                    </div>
                                                    <div class="bzg_c" data-col="m6">
                                                        <input class="form-input" id="eventTimeEnd" type="text" placeholder="End date" data-datepicker required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bzg">
                                        <div class="bzg_c" data-col="m6">
                                            <div class="block">
                                                <label for="eventStartTime">What time will it start</label>
                                                <input class="form-input" id="eventStartTime" type="text" data-timepicker required>
                                            </div>
                                        </div>
                                        <div class="bzg_c" data-col="m6">
                                            <div class="block">
                                                <label for="eventEndTime">What time will it end</label>
                                                <input class="form-input" id="eventEndTime" type="text" data-timepicker required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block">
                                        <label for="eventDetail">The event details</label>
                                        <textarea class="form-input" id="eventDetail" rows="4" required></textarea>
                                    </div>

                                    <div class="bzg">
                                        <div class="bzg_c" data-col="m6">
                                            <div class="block">
                                                <label for="employerEmail">Email address</label>
                                                <input class="form-input" id="employerEmail" type="text" required>
                                            </div>
                                        </div>
                                        <div class="bzg_c" data-col="m6">
                                            <div class="block">
                                                <label for="employerTel">Telephone</label>
                                                <input class="form-input" id="employerTel" type="text" required>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn--tosca">Send</button>
                                </form>
                            </template>

                            <ul>
                                <li>Accomodation (not included)</li>
                                <li>Fittings, make up and hair</li>
                                <li>Minimum of two hours on all bookings</li>
                            </ul>
                        </section>

                        <section class="talent-profile-gallery">
                            <h3 class="sr-only">Gallery</h3>

                            <figure class="talent-profile-images">
                                <a href="https://www.youtube.com/watch?v=anMYu17aZT4" data-fancybox="gallery">
                                    <img src="https://img.youtube.com/vi/anMYu17aZT4/0.jpg" alt="">
                                </a>
                                <?php for ($i=0; $i < 12; $i++) { ?>
                                <a href="assets/img/gallery-img.jpg" data-fancybox="gallery">
                                    <img src="assets/img/gallery-thumb.jpg" alt="">
                                </a>
                                <?php } ?>
                            </figure>
                        </section>
                    </div>
                </div>
                <div class="talent-profile-portofolio" id="portofolio">
                    <div class="container">
                        <div class="ui-tab js-tab">
                            <div class="ui-tab-anchors">
                                <a class="ui-tab-anchor js-tab-anchor" href="#tab1">Portofolio</a>
                                <a class="ui-tab-anchor js-tab-anchor is-active" href="#tab2">Review</a>
                            </div>

                            <div class="ui-tab-panels">
                                <div class="ui-tab-panel js-tab-panel" id="tab1">
                                    <ul class="portofolio-list list-nostyle">
                                        <?php for ($i=0; $i < 8; $i++) { ?>
                                        <li>
                                            <article class="portofolio">
                                                <time class="portofolio-time">11 Agustus 2014</time>
                                                <h3 class="portofolio-title">Indonesia Fashion Week</h3>
                                                <p class="portofolio-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil veniam accusamus, suscipit dolorum dicta corrupti. Minus voluptas, unde commodi rem cumque officia, esse tempora perspiciatis.</p>
                                                <a href="#">
                                                    <span class="fa fa-fw fa-link"></span>
                                                </a>
                                                <a href="#">
                                                    <span class="fa fa-fw fa-youtube-play"></span>
                                                </a>
                                            </article>
                                        </li>
                                        <?php } ?>
                                    </ul>

                                    <ul class="pagination">
                                        <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
                                        <li><a class="active" href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#"><span class="fa fa-angle-right"></span></a></li>
                                    </ul>
                                </div>
                                <div class="ui-tab-panel js-tab-panel is-active" id="tab2">
                                    <ul class="talent-rates list-nostyle">
                                        <?php for ($i=0; $i < 5; $i++) { ?>
                                        <li>
                                            <div class="talent-rate">
                                                <div class="media block">
                                                    <img class="media-img" src="assets/img/default-avatar.jpg" width="50" alt="">
                                                    <div class="media-content">
                                                        <b>Rizal Mantofani</b> <br>
                                                        <time><small>1 Desember 2016</small></time>
                                                    </div>
                                                </div>

                                                <div class="rate rate--display">
                                                    <input class="sr-only" type="radio" value="5" checked>
                                                    <label><span class="fa fa-fw fa-star-o"></span></label>

                                                    <input class="sr-only" type="radio" value="4">
                                                    <label><span class="fa fa-fw fa-star-o"></span></label>

                                                    <input class="sr-only" type="radio" value="3">
                                                    <label><span class="fa fa-fw fa-star-o"></span></label>

                                                    <input class="sr-only" type="radio" value="2">
                                                    <label><span class="fa fa-fw fa-star-o"></span></label>

                                                    <input class="sr-only" type="radio" value="1" checked>
                                                    <label><span class="fa fa-fw fa-star-o"></span></label>
                                                </div>

                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet perspiciatis voluptas expedita magnam fuga, aperiam ab iste molestias laudantium? Debitis exercitationem possimus facere expedita necessitatibus deleniti eos architecto fuga voluptatem!</p>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="sticky-footer-container-item">
            <?php include '_include/footer.php'; ?>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

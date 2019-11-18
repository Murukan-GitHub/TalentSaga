<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner-padded">
                    <div class="container">
                        <div class="bzg">
                            <div class="bzg_c" data-col="s7, m6">
                                <h2 class="text-caps">Booking list</h2>
                            </div>
                            <div class="bzg_c" data-col="s5, m6">
                                <form class="activity-filter-form" action="">
                                    <label for="selectFilter">Sort by</label>
                                    <select class="form-input" id="selectFilter">
                                        <option value="Newest">Newest</option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <ul class="activity-list list-nostyle">
                            <?php for ($i=0; $i < 4; $i++) { ?>
                            <li class="activity-list-item">
                                <div class="activity <?= $i > 0 ? 'activity--done': ''; ?>">
                                    <form action="#" class="activity-form">
                                        <?php if ($i > 0) { ?>
                                        <fieldset disabled>
                                            <button>
                                                <span class="fa fa-fw fa-square-o"></span>
                                                done
                                            </button>
                                        </fieldset>
                                        <?php } ?>
                                    </form>

                                    <h3>Talent info</h3>
                                    <div class="block-half">
                                        <span class="fa fa-lg fa-fw fa-user-circle"></span> Adina Wirasti
                                    </div>
                                    <div class="block-half">
                                        <span class="fa fa-lg fa-fw fa-phone"></span> 081237513
                                    </div>
                                    <div class="block-half">
                                        <span class="fa fa-lg fa-fw fa-envelope"></span>
                                        anggita.kel@gmail.com
                                    </div>
                                    <hr>
                                    <h3>Event detail</h3>

                                    <p>Acara peringatan ulang tahun Provinsi Riau. Terdiri dari berbagai macam hiburan</p>

                                    <div class="block-half text-tosca">
                                        <span class="fa fa-lg fa-fw fa-calendar"></span>
                                        Saturday, 13 December 2016
                                    </div>
                                    <div class="block-half text-tosca">
                                        <span class="fa fa-lg fa-fw fa-clock-o"></span>
                                        17.00 - 19.00 (2 hours)
                                    </div>
                                    <div class="block text-tosca">
                                        <span class="fa fa-lg fa-fw fa-map-marker"></span>
                                        Kantor Bupati Riau
                                    </div>


                                    <?php if ($i > 0) { ?>
                                    <button class="activity-review-btn btn btn--tosca" data-modal="#modalReview<?=$i?>">Write review</button>
                                    <template id="modalReview<?=$i?>">
                                        <form data-validate action="">
                                            <div class="rate">
                                                <span class="rate-label">Rate it</span>

                                                <input class="sr-only" id="rate<?= $i ?>-5" type="radio" name="rate<?= $i ?>" value="5">
                                                <label for="rate<?= $i ?>-5"><span class="fa fa-fw fa-star-o"></span></label>

                                                <input class="sr-only" id="rate<?= $i ?>-4" type="radio" name="rate<?= $i ?>" value="4">
                                                <label for="rate<?= $i ?>-4"><span class="fa fa-fw fa-star-o"></span></label>

                                                <input class="sr-only" id="rate<?= $i ?>-3" type="radio" name="rate<?= $i ?>" value="3">
                                                <label for="rate<?= $i ?>-3"><span class="fa fa-fw fa-star-o"></span></label>

                                                <input class="sr-only" id="rate<?= $i ?>-2" type="radio" name="rate<?= $i ?>" value="2">
                                                <label for="rate<?= $i ?>-2"><span class="fa fa-fw fa-star-o"></span></label>

                                                <input class="sr-only" id="rate<?= $i ?>-1" type="radio" name="rate<?= $i ?>" value="1" checked>
                                                <label for="rate<?= $i ?>-1"><span class="fa fa-fw fa-star-o"></span></label>
                                            </div>

                                            <div class="block-half">
                                                <label class="form-label" for="inputReviewMsg<?= $i ?>">Write your review</label>
                                                <textarea class="form-input" id="inputReviewMsg<?= $i ?>" rows="3" required></textarea>
                                            </div>
                                            <button class="btn btn--tosca">Add review</button>
                                        </form>
                                    </template>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>

                        <ul class="pagination">
                            <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#"><span class="fa fa-angle-right"></span></a></li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
        <div class="sticky-footer-container-item">
            <?php include '_include/footer.php'; ?>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

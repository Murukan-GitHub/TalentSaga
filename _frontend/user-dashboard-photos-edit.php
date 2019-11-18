<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner">
                    <div class="container">
                        <div class="category-layout">
                            <div class="category-layout-filter-trigger">
                                <br>
                                <button class="btn btn--tosca">Menu</button>
                            </div>
                            <div class="category-filter">
                                <ul class="user-dashboard-nav list-nostyle">
                                    <li><a href="user-dashboard.php">Account information</a></li>
                                    <li><a href="user-dashboard-personal-information.php">Personal information</a></li>
                                    <li><a href="user-dashboard-talent-information.php">Talent information</a></li>
                                    <li><a href="user-dashboard-portofolios.php">Portofolios</a></li>
                                    <li><a class="is-active" href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Photos: Name</h2>

                                <form action="">
                                    <figure class="floating-btn-container js-image-crop" style="display: inline-block;">
                                        <img class="js-image-crop-preview" src="assets/img/gallery-img.jpg" alt="">
                                        <figcaption>
                                            <label class="floating-btn is-absolute" for="inputPhoto">
                                                <span class="fa fa-fw fa-pencil"></span>
                                            </label>
                                            <input class="sr-only js-image-crop-input" id="inputPhoto" type="file" accept="image/png, image/jpg, image/jpeg">
                                            <input class="js-image-crop-hidden-input" type="hidden">
                                        </figcaption>
                                    </figure>

                                    <div class="v-center block">
                                        <small>Order</small>
                                        <div>
                                            <div class="counter js-counter">
                                                <button class="js-counter-dec" type="button">-</button>
                                                <input class="js-counter-input" type="number" value="0" min="0" max="6" readonly>
                                                <button class="js-counter-inc" type="button">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button class="btn btn--tosca">Update photo</button>
                                    </div>
                                </form>
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

    <div class="image-crop-modal">
        <div class="image-crop-modal-dialog">
            <button class="image-crop-modal-close">&times;</button>
            <div class="image-crop-field"></div>

            <div class="text-center">
                <button class="image-crop-modal-set-btn btn btn--tosca">Crop</button>
            </div>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

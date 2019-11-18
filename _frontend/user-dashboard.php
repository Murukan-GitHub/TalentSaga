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
                                    <li><a class="is-active" href="user-dashboard.php">Account information</a></li>
                                    <li><a href="user-dashboard-personal-information.php">Personal information</a></li>
                                    <li><a href="user-dashboard-talent-information.php">Talent information</a></li>
                                    <li><a href="user-dashboard-portofolios.php">Portofolios</a></li>
                                    <li><a href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Account information</h2>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l9">
                                        <form class="js-form-acc-information" action="#">
                                            <fieldset class="block">
                                                <div class="edit-avatar-field-container">
                                                    <div class="block-half">
                                                        <label class="form-label">Profile background</label>
                                                        <figure class="floating-btn-container">
                                                            <img class="edit-avatar-field-cover-img" id="previewBackground" src="//placehold.it/500x200" style="width: 100%;">
                                                            <figcaption>
                                                                <input class="sr-only" id="inputProfileBackground" type="file" accept="image/png, image/jpg, image/jpeg" data-input-auto-preview="#previewBackground">
                                                                <label class="floating-btn is-absolute" for="inputProfileBackground">
                                                                    <span class="fa fa-fw fa-camera"></span>
                                                                    <span>Edit cover photo</span>
                                                                </label>
                                                            </figcaption>
                                                        </figure>
                                                    </div>
                                                    <div class="edit-avatar-field floating-btn-container">
                                                        <img class="edit-avatar-field-img" src="assets/img/default-avatar.jpg" alt="">
                                                        <label class="edit-avatar-field-btn" for="inputAvatar">
                                                            <span class="fa fa-fw fa-camera"></span>
                                                            <small>Edit avatar</small>
                                                        </label>
                                                        <input class="sr-only" id="inputAvatar" type="file" accept="image/png, image/jpg, image/jpeg">
                                                        <input id="croppedInputAvatar" type="hidden">
                                                    </div>
                                                </div>
                                                <div class="block-half">
                                                    <label class="form-label" for="inputUsername">Username</label>
                                                    <input class="form-input" id="inputUsername" type="text" value="Jane Doe" required>
                                                </div>
                                                <div class="block-half">
                                                    <label class="form-label" for="inputEmail">Email</label>
                                                    <input class="form-input" id="inputEmail" type="email" value="jane.doe@missing.com" required>
                                                </div>
                                            </fieldset>

                                            <fieldset>
                                                <legend>Change password</legend>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputPassword">New password</label>
                                                    <input class="form-input" id="inputPassword" type="password">
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputConfirmPassword">Confirm new password</label>
                                                    <input class="form-input" id="inputConfirmPassword" type="password">
                                                </div>
                                            </fieldset>

                                            <button class="btn btn--tosca">Save</button>
                                        </form>
                                    </div>
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

    <div class="avatar-crop-modal">
        <div class="avatar-crop-modal-dialog">
            <button class="avatar-crop-modal-close">&times;</button>
            <div class="avatar-crop-field"></div>

            <div class="text-center">
                <button class="avatar-crop-modal-set-btn btn btn--tosca">Set Avatar</button>
            </div>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

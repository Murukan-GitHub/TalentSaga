<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner-padded">
                    <div class="container">
                        <h2 class="text-center text-caps text-light">Your account information</h2>

                        <div class="bzg">
                            <div class="bzg_c" data-col="l6" data-offset=l3>
                                <form class="js-form-acc-information" action="#">
                                    <fieldset class="block">
                                        <div class="block-half">
                                            <label class="form-label" for="inputAvatar">Avatar</label>
                                            <div class="edit-avatar-field">
                                                <img class="edit-avatar-field-img" src="assets/img/default-avatar.jpg" alt="">
                                                <label class="btn btn--gray" for="inputAvatar">Change avatar</label>
                                                <input class="sr-only" id="inputAvatar" type="file" accept="image/*">
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
            </main>
        </div>
        <div class="sticky-footer-container-item">
            <?php include '_include/footer.php'; ?>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

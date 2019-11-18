<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner-padded">
                    <div class="container">
                        <h2 class="fancy-heading">Register</h2>

                        <div class="bzg">
                            <div class="bzg_c" data-col="l6" data-offset="l3">
                                <form action="" data-validate>
                                    <div class="block-half">
                                        <label class="form-label" for="inputUsername">Username</label>
                                        <input class="form-input" id="inputUsername" type="text" required>
                                    </div>
                                    <div class="block-half">
                                        <label class="form-label" for="inputEmail">Email</label>
                                        <input class="form-input" id="inputEmail" type="email" required>
                                    </div>
                                    <div class="block-half">
                                        <label class="form-label" for="inputPassword">Password</label>
                                        <input class="form-input" id="inputPassword" type="password" required>
                                    </div>
                                    <button class="btn btn--block btn--tosca block-half">Create account</button>

                                    <p class="text-center block-half"><small>Have accont? <a href="login.php">Login here</a>.</small></p>
                                </form>

                                <hr>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="m6">
                                        <a class="btn btn--block btn--fb block-half" href="#">Connect via Facebook</a>
                                    </div>
                                    <div class="bzg_c" data-col="m6">
                                        <a class="btn btn--block btn--gplus block-half" href="#">Connect via Google Plus</a>
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

<?php include '_include/scripts.php'; ?>

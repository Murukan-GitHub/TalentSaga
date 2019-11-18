<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner">
                    <div class="contact">
                        <div class="contact-map" data-coords='{"lat": 52.512609, "lng": 13.383493}'></div>

                        <div class="container">
                            <div class="contact-card">
                                <form class="contact-form" action="#" data-validate>
                                    <fieldset>
                                        <legend class="h3">Send us a message</legend>

                                        <div class="block-half">
                                            <label class="sr-only" for="inputContactName">Your name</label>
                                            <input class="form-input" id="inputContactName" type="text" placeholder="Your name" required>
                                        </div>

                                        <div class="block-half">
                                            <label class="sr-only" for="inputContactEmail">Email address</label>
                                            <input class="form-input" id="inputContactEmail" type="email" placeholder="Email address" required>
                                        </div>

                                        <div class="block-half">
                                            <label class="sr-only" for="inputContactSubject">Subject</label>
                                            <input class="form-input" id="inputContactSubject" type="text" placeholder="Subject" required>
                                        </div>

                                        <div class="block-half">
                                            <label class="sr-only" for="inputContactMessage">Your message</label>
                                            <textarea class="form-input" id="inputContactMessage" rows="2" placeholder="Your message" required></textarea>
                                        </div>

                                        <button class="btn btn--tosca">Send</button>
                                    </fieldset>
                                </form>

                                <section class="contact-address">
                                    <h2 class="h3">Contact us</h2>

                                    <address>
                                        <p>Sassenburger Weg 4a 22147 Hamburg Germany</p>
                                        <p>+49 (0)176 322 82 722</p>
                                        <p>as@ansaworks.com</p>
                                    </address>
                                </section>
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

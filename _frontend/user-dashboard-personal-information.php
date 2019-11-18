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
                                    <li><a class="is-active" href="user-dashboard-personal-information.php">Personal information</a></li>
                                    <li><a href="user-dashboard-talent-information.php">Talent information</a></li>
                                    <li><a href="user-dashboard-portofolios.php">Portofolios</a></li>
                                    <li><a href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Personal information</h2>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l9">
                                        <form action="#" data-validate>
                                            <fieldset>
                                                <div class="bzg">
                                                    <div class="bzg_c" data-col="l6">
                                                        <div class="block-half">
                                                            <label class="form-label" for="inputFirstName">First name</label>
                                                            <input class="form-input" id="inputFirstName" type="text" required>
                                                        </div>
                                                    </div>
                                                    <div class="bzg_c" data-col="l6">
                                                        <div class="block-half">
                                                            <label class="form-label" for="inputLastName">Last name</label>
                                                            <input class="form-input" id="inputLastName" type="text" required>
                                                        </div>
                                                    </div>
                                                </div>


                                                <fieldset>
                                                    <legend>Birthday</legend>

                                                    <div class="bzg">
                                                        <div class="bzg_c" data-col="s4">
                                                            <div class="block-half">
                                                                <label class="sr-only" for="birthdayDay">Day of birth</label>
                                                                <input class="form-input" id="birthdayDay" type="text" placeholder="DD" required>
                                                            </div>
                                                        </div>
                                                        <div class="bzg_c" data-col="s4">
                                                            <div class="block-half">
                                                                <label class="sr-only" for="birthdayMonth">Month of birth</label>
                                                                <input class="form-input" id="birthdayMonth" type="text" placeholder="MM" required>
                                                            </div>
                                                        </div>
                                                        <div class="bzg_c" data-col="s4">
                                                            <div class="block-half">
                                                                <label class="sr-only" for="birthdayYear">Year of birth</label>
                                                                <input class="form-input" id="birthdayYear" type="text" placeholder="YYYY" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputPhone">Your phone no.</label>
                                                    <input class="form-input" id="inputPhone" type="text" required>
                                                </div>

                                                <div class="block-half">
                                                    <label for="selectCity">City</label>
                                                    <select class="form-input" id="selectCity" data-enhance-select>
                                                        <option value="City 1">City 1</option>
                                                        <option value="City 2">City 2</option>
                                                        <option value="City 3" selected>City 3</option>
                                                    </select>
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputAddress">Your address</label>
                                                    <textarea class="form-input" id="inputAddress" rows="3" required></textarea>
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputZipCode">Zip code</label>
                                                    <input class="form-input" id="inputZipCode" type="text" required>
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputGender">Gender</label>
                                                    <select class="form-input" id="inputGender">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputWeight">Weight (in kilogram)</label>
                                                    <input class="form-input" id="inputWeight" type="number" required>
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputHeight">Height (in cm)</label>
                                                    <input class="form-input" id="inputHeight" type="number" required>
                                                </div>
                                            </fieldset>

                                            <fieldset>
                                                <legend>Social media account</legend>

                                                <div class="block-half">
                                                    <label class="sr-only" for="inputFacebook">Facebook</label>
                                                    <input class="form-input" id="inputFacebook" type="text" placeholder="Facebook" required>
                                                </div>

                                                <div class="block-half">
                                                    <label class="sr-only" for="inputTwitter">Twitter</label>
                                                    <input class="form-input" id="inputTwitter" type="text" placeholder="Twitter" required>
                                                </div>

                                                <div class="block-half">
                                                    <label class="sr-only" for="inputInstagram">Instagram</label>
                                                    <input class="form-input" id="inputInstagram" type="text" placeholder="Instagram" required>
                                                </div>

                                                <div class="block-half">
                                                    <label class="sr-only" for="inputYoutube">Youtube</label>
                                                    <input class="form-input" id="inputYoutube" type="text" placeholder="Youtube" required>
                                                </div>
                                            </fieldset>

                                            <div class="form-baa-actions">
                                                <a class="btn btn--gray" href="#">Save as draft</a>
                                                <button class="btn btn--tosca">Next</button>
                                            </div>
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

<?php include '_include/scripts.php'; ?>

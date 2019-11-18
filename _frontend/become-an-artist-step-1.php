<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner-padded">
                    <div class="container">
                        <h2 class="sr-only">Become an artist</h2>

                        <ol class="baa-steps">
                            <li class="baa-steps-item is-active">Personal</li>
                            <li class="baa-steps-item">Talent</li>
                            <li class="baa-steps-item">Portofolio</li>
                            <li class="baa-steps-item">Upload</li>
                            <li class="baa-steps-item">Pricing</li>
                        </ol>

                        <form class="form-baa" action="become-an-artist-step-2.php" data-validate>
                            <fieldset>
                                <legend class="text-center h3 text-caps">Personal Information</legend>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l6">
                                        <div class="block-half">
                                            <label class="form-label" for="inputFirstName">First name <span class="text-tosca">*</span></label>
                                            <input class="form-input" id="inputFirstName" type="text" required>
                                        </div>
                                    </div>
                                    <div class="bzg_c" data-col="l6">
                                        <div class="block-half">
                                            <label class="form-label" for="inputLastName">Last name <span class="text-tosca">*</span></label>
                                            <input class="form-input" id="inputLastName" type="text" required>
                                        </div>
                                    </div>
                                </div>


                                <fieldset>
                                    <legend>Birthday <span class="text-tosca">*</span></legend>

                                    <div class="bzg">
                                        <div class="bzg_c" data-col="s4">
                                            <div class="block-half">
                                                <label class="sr-only" for="birthdayDay">Day of birth</label>
                                                <select class="form-input" id="birthdayDay">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="bzg_c" data-col="s4">
                                            <div class="block-half">
                                                <label class="sr-only" for="birthdayMonth">Month of birth</label>
                                                <select class="form-input" id="birthdayMonth">
                                                    <option value="January">January</option>
                                                    <option value="February">February</option>
                                                    <option value="March">March</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="bzg_c" data-col="s4">
                                            <div class="block-half">
                                                <label class="sr-only" for="birthdayYear">Year of birth</label>
                                                <select class="form-input" id="birthdayYear">
                                                    <option value="2014">2014</option>
                                                    <option value="2015">2015</option>
                                                    <option value="2016">2016</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="block-half">
                                    <label class="form-label" for="inputPhone">Your phone no. <span class="text-tosca">*</span></label>
                                    <input class="form-input" id="inputPhone" type="text" required>
                                </div>

                                <fieldset ng-controller="SelectCountryCityController" data-countries="dev/countries.json">
                                    <div class="block-half normalize-selectize">
                                        <label class="form-label" for="inputCountry">Your country <span class="text-tosca">*</span></label>
                                        <select class="form-input" id="inputCountry" ng-model="filterCountry" ng-options="country.value as country.name for country in countries track by country.value" ng-change="getCitiesByCountry()"></select>
                                    </div>

                                    <div class="block-half normalize-selectize">
                                        <label class="form-label" for="inputCity">Your city <span class="text-tosca">*</span></label>
                                        <select class="form-input" id="inputCity" ng-model="filterCities" ng-options="city.value as city.name for city in cities track by city.value" ng-disabled="isGettingCities"></select>
                                    </div>
                                </fieldset>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l9">
                                        <div class="block-half">
                                            <label class="form-label" for="inputAddress">Street address <span class="text-tosca">*</span></label>
                                            <input class="form-input" id="inputAddress" type="text" required>
                                        </div>
                                    </div>
                                    <div class="bzg_c" data-col="l3">
                                        <div class="block-half">
                                            <label class="form-label" for="inputStreetNo">Street number <span class="text-tosca">*</span></label>
                                            <input class="form-input" id="inputStreetNo" type="text" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="block-half">
                                    <label class="form-label" for="inputZipCode">Zip code <span class="text-tosca">*</span></label>
                                    <input class="form-input" id="inputZipCode" type="text" required>
                                </div>

                                <fieldset class="block-half">
                                    <legend>Gender <span class="text-tosca">*</span></legend>

                                    <div>
                                        <label class="custom-radio" for="genderMale">
                                            <input id="genderMale" type="radio" name="gender" checked>
                                            <span>Male</span>
                                        </label>
                                    </div>

                                    <div>
                                        <label class="custom-radio" for="genderFemale">
                                            <input id="genderFemale" type="radio" name="gender">
                                            <span>Female</span>
                                        </label>
                                    </div>
                                </fieldset>

                                <div class="block-half">
                                    <label class="form-label" for="inputWeight">Weight (in kilogram)</label>
                                    <input class="form-input" id="inputWeight" type="number">
                                </div>

                                <div class="block-half">
                                    <label class="form-label" for="inputHeight">Height (in cm)</label>
                                    <input class="form-input" id="inputHeight" type="number">
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Social media account <span class="text-tosca">*</span></legend>

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
            </main>
        </div>
        <div class="sticky-footer-container-item">
            <?php include '_include/footer.php'; ?>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

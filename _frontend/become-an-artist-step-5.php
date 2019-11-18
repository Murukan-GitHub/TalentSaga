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
                            <li class="baa-steps-item is-active">Talent</li>
                            <li class="baa-steps-item is-active">Portofolio</li>
                            <li class="baa-steps-item is-active">Upload</li>
                            <li class="baa-steps-item is-active">Pricing</li>
                        </ol>

                        <form class="form-baa" action="become-an-artist-step-5.php" data-validate>
                            <fieldset class="block-half">
                                <legend class="text-center h3 text-caps">Pricing</legend>

                                <fieldset id="priceEstimationContainer">
                                    <legend>Price estimation</legend>

                                    <template>
                                        <div class="price-estimation">
                                            <div class="bzg">
                                                <div class="bzg_c" data-col="m4">
                                                    <div class="block-half">
                                                        <label class="form-label"><small>Currency</small></label>
                                                        <select class="form-input">
                                                            <option value="Scorpion">Scorpion</option>
                                                            <option value="Dinar">Dinar</option>
                                                            <option value="IDR">IDR</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="bzg_c" data-col="m4">
                                                    <div class="block-half">
                                                        <label class="form-label"><small>Amount</small></label>
                                                        <input class="form-input" type="text" value="0" required>
                                                    </div>
                                                </div>
                                                <div class="bzg_c" data-col="m4">
                                                    <div class="block-half">
                                                        <label class="form-label"><small>Duration</small></label>
                                                        <select class="form-input">
                                                            <option value="Hourly">Hourly</option>
                                                            <option value="Daily">Daily</option>
                                                            <option value="Weekly">Weekly</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="price-estimation-remove-btn btn-reset" type="button">&times;</button>
                                        </div>
                                    </template>

                                    <div id="priceEstimationContent">
                                        <div class="price-estimation">
                                            <div class="bzg">
                                                <div class="bzg_c" data-col="m4">
                                                    <div class="block-half">
                                                        <label class="form-label"><small>Currency</small></label>
                                                        <select class="form-input">
                                                            <option value="Scorpion">Scorpion</option>
                                                            <option value="Dinar">Dinar</option>
                                                            <option value="IDR">IDR</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="bzg_c" data-col="m4">
                                                    <div class="block-half">
                                                        <label class="form-label"><small>Amount</small></label>
                                                        <input class="form-input" type="text" value="0" required>
                                                    </div>
                                                </div>
                                                <div class="bzg_c" data-col="m4">
                                                    <div class="block-half">
                                                        <label class="form-label"><small>Duration</small></label>
                                                        <select class="form-input">
                                                            <option value="Hourly">Hourly</option>
                                                            <option value="Daily">Daily</option>
                                                            <option value="Weekly">Weekly</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="price-estimation-remove-btn btn-reset" type="button">&times;</button>
                                        </div>
                                    </div>

                                    <div class="text-right block-half">
                                        <button class="price-estimation-add-btn btn btn--sm btn--gray" type="button">Add</button>
                                    </div>

                                    <hr>
                                </fieldset>

                                <label class="custom-checkbox" for="contactPrice">
                                    <input id="contactPrice" type="checkbox">
                                    <span>Contact me for price</span>
                                </label>

                                <fieldset>
                                    <legend>Price inclusion</legend>

                                    <div>
                                        <label class="custom-checkbox" for="inclusion1">
                                            <input id="inclusion1" type="checkbox" name="inclusion">
                                            <span>Accomodation</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="custom-checkbox" for="inclusion2">
                                            <input id="inclusion2" type="checkbox" name="inclusion">
                                            <span>Make up</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="custom-checkbox" for="inclusion3">
                                            <input id="inclusion3" type="checkbox" name="inclusion">
                                            <span>Costume</span>
                                        </label>
                                    </div>
                                    <div class="block-half">
                                        <input class="form-input" type="text" placeholder="other">
                                    </div>
                                </fieldset>

                                <div class="block-half">
                                    <label class="form-label" for="inputPriceNote">Price notes <small><sup>*</sup>if needed</small></label>
                                    <textarea class="form-input" id="inputPriceNote" rows="4"></textarea>
                                </div>

                                <fieldset>
                                    <legend><b>Availability area</b></legend>

                                    <fieldset class="block-half">
                                        <legend>Countries</legend>

                                        <div>
                                            <label class="custom-checkbox" for="country1">
                                                <input id="country1" type="checkbox" name="country" data-city-availability="#cityGermany">
                                                <span>Germany</span>
                                            </label>
                                            <div class="city-availability-hidden" id="cityGermany">
                                                <small>Cities</small>
                                                <input class="block-half" type="text" value="Germany City 1, Germany City 2, Germany City 3" data-selectize>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="custom-checkbox" for="country2">
                                                <input id="country2" type="checkbox" name="country" data-city-availability="#cityFrance">
                                                <span>France</span>
                                            </label>
                                            <div class="city-availability-hidden" id="cityFrance">
                                                <small>Cities</small>
                                                <input class="block-half" type="text" value="France City 1, France City 2, France City 3" data-selectize>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="custom-checkbox" for="country3">
                                                <input id="country3" type="checkbox" name="country" data-city-availability="#cityItaly">
                                                <span>Italy</span>
                                            </label>
                                            <div class="city-availability-hidden" id="cityItaly">
                                                <small>Cities</small>
                                                <input class="block-half" type="text" value="Italy City 1, Italy City 2, Italy City 3" data-selectize>
                                            </div>
                                        </div>
                                    </fieldset>
                                </fieldset>
                            </fieldset>

                            <div class="form-baa-actions">
                                <a class="btn btn--tosca btn--outline" href="#">Back</a>
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

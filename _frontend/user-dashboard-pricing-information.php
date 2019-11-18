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
                                    <li><a href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a class="is-active" href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Pricing information</h2>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l9">
                                        <form class="form-baa" action="#" data-validate>
                                            <fieldset class="block-half">
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
                                                            <div>
                                                                <small>Cities</small>
                                                                <select class="invisible" data-selectize multiple>
                                                                    <option value="City 1">City 1</option>
                                                                    <option value="City 2" selected>City 2</option>
                                                                    <option value="City 3">City 3</option>
                                                                    <option value="City 4" selected>City 4</option>
                                                                    <option value="City 5">City 5</option>
                                                                    <option value="City 6">City 6</option>
                                                                    <option value="City 7">City 7</option>
                                                                </select>
                                                                <!-- <input class="block-half" type="text" value="Germany City 1, Germany City 2, Germany City 3" data-selectize> -->
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

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
                                <button class="btn btn--tosca">
                                    <span class="fa fa-fw fa-filter"></span>
                                    Filter
                                </button>
                            </div>
                            <div class="category-filter" ng-controller="CategoryFilterController">
                                <form class="category-filter-form" action="talent-list.php">
                                    <div class="block-half">
                                        <label class="sr-only" for="categoryFilterSearch">Search by name</label>
                                        <input class="form-input" id="categoryFilterSearch" type="text" placeholder="Search by name">
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="categoryFilterCategory">Category</label>
                                        <select class="form-input" id="categoryFilterCategory">
                                            <option value="Category 1">Category 1</option>
                                            <option value="Category 2">Category 2</option>
                                            <option value="Category 3">Category 3</option>
                                        </select>
                                    </div>

                                    <fieldset>
                                        <legend>Expertise</legend>

                                        <div>
                                            <label class="custom-checkbox" for="expertise1">
                                                <input id="expertise1" type="checkbox" name="expertise">
                                                <span>Singer</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="custom-checkbox" for="expertise2">
                                                <input id="expertise2" type="checkbox" name="expertise">
                                                <span>Pianist</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="custom-checkbox" for="expertise3">
                                                <input id="expertise3" type="checkbox" name="expertise">
                                                <span>Guitarist</span>
                                            </label>
                                        </div>
                                    </fieldset>

                                    <fieldset>
                                        <legend>Location</legend>

                                        <div class="block-half normalize-selectize">
                                            <select id="selectCountry" data-countries="dev/countries.json" ng-model="filterCountry" ng-options="country.value as country.name for country in countries" ng-change="getCitiesByCountry()" data-enhance-select></select>
                                        </div>

                                        <div class="block-half normalize-selectize">
                                            <select id="selectCity" ng-model="filterCities" ng-options="city.value as city.name for city in cities" ng-disabled="isGettingCities" data-enhance-select></select>
                                        </div>
                                    </fieldset>

                                    <div>
                                        <fieldset>
                                            <legend>Price</legend>

                                            <div class="v-center v-center--compact block">
                                                <input class="form-input" id="minimumPrice" type="text" placeholder="Min">
                                                <span>-</span>
                                                <input class="form-input" id="MaximumPrice" type="text" placeholder="Max">
                                            </div>

                                        </fieldset>
                                    </div>

                                    <button class="btn btn--block btn--tosca">Apply</button>
                                </form>
                            </div>
                            <div class="category-content">
                                <h2 class="h3 text-light">Search results for "Adit"</h2>

                                <div class="talent-list talent-list--category">
                                    <?php for ($i=1; $i <= 9; $i++) { ?>
                                    <div class="talent-list-item">
                                        <a class="talent-anchor" href="talent-detail.php">
                                            <figure class="talent">
                                                <img class="talent-img" src="assets/img/thumb-talent-<?= $i % 4 + 1 ?>.jpg" alt="">
                                                <figcaption class="talent-desc">
                                                    <span class="talent-label">Actor</span>
                                                    <h3 class="talent-name">Adit Erlangga, 24</h3>
                                                    <span class="talent-location">
                                                        <span class="fa fa-map-marker"></span>
                                                        <span>Jakarta</span>
                                                    </span>
                                                </figcaption>
                                            </figure>
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>

                                <ul class="pagination">
                                    <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
                                    <li><a class="active" href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#"><span class="fa fa-angle-right"></span></a></li>
                                </ul>
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

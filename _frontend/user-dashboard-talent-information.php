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
                                    <li><a class="is-active" href="user-dashboard-talent-information.php">Talent information</a></li>
                                    <li><a href="user-dashboard-portofolios.php">Portofolios</a></li>
                                    <li><a href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Talent information</h2>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l9">
                                        <form style="opacity: 0;" action="#" data-validate ng-controller="TalentCategoryExpertiseController" data-categories="dev/talent-categories.json">
                                            <fieldset class="block-half">
                                                <legend class="text-center h3 text-caps">Describe your talent</legend>

                                                <div class="block">
                                                    <label class="form-label" for="selectCategory">Talent category</label>
                                                    <select class="form-input" id="selectCategory" ng-model="talentCategory" ng-options="category.value as category.description for category in talentCategories" ng-change="getExpertise()" ng-disabled="isLoading"></select>
                                                </div>

                                                <fieldset class="block" ng-show="!isLoading">
                                                    <legend>Expertise</legend>

                                                    <div ng-repeat="expertise in talentExpertises">
                                                        <label class="custom-checkbox" for="expertise{{ $index }}">
                                                            <input id="expertise{{ $index }}" type="checkbox" name="expertise[]" ng-value="{{ expertise.value }}">
                                                            <span>{{ expertise.description }}</span>
                                                        </label>
                                                    </div>

                                                    <label class="sr-only" for="inputOtherExpertise">Other</label>
                                                    <input class="form-input" id="inputOtherExpertise" type="text" placeholder="Other">
                                                </fieldset>

                                                <label class="form-label" for="inputDescribe">Tell me about your talent in 200 characters, who are you, what you are expert at, or where you usually perform your talent</label>
                                                <textarea class="form-input" id="inputDescribe" rows="5" data-max-char="200" required></textarea>
                                                <div class="text-right">
                                                    <small>{{ charCount }}/200</small>
                                                </div>
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

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
                            <li class="baa-steps-item">Portofolio</li>
                            <li class="baa-steps-item">Upload</li>
                            <li class="baa-steps-item">Pricing</li>
                        </ol>

                        <form class="form-baa" style="opacity: 0;" action="become-an-artist-step-3.php" data-validate ng-controller="TalentCategoryExpertiseController" data-categories="dev/talent-categories.json">
                            <fieldset class="block-half">
                                <legend class="text-center h3 text-caps">Describe your talent</legend>

                                <div class="block">
                                    <label class="form-label" for="selectCategory">Talent category <span class="text-tosca">*</span></label>
                                    <select class="form-input" id="selectCategory" ng-model="talentCategory" ng-options="category.value as category.description for category in talentCategories track by category.value" ng-change="getExpertise()" ng-disabled="isLoading"></select>
                                </div>

                                <fieldset class="block" ng-show="!isLoading">
                                    <legend>Expertise <span class="text-tosca">*</span></legend>

                                    <div ng-repeat="expertise in talentExpertises">
                                        <label class="custom-checkbox" for="expertise{{ $index }}">
                                            <input id="expertise{{ $index }}" type="checkbox" name="expertise[]" ng-value="{{ expertise.value }}" ng-checked="expertise.selected">
                                            <span>{{ expertise.description }}</span>
                                        </label>
                                    </div>

                                    <label class="sr-only" for="inputOtherExpertise">Other</label>
                                    <input class="form-input" id="inputOtherExpertise" type="text" placeholder="Other">
                                </fieldset>

                                <label class="form-label" for="inputDescribe">Tell me about your talent in 200 characters, who are you, what you are expert at, or where you usually perform your talent <span class="text-tosca">*</span></label>
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
            </main>
        </div>
        <div class="sticky-footer-container-item">
            <?php include '_include/footer.php'; ?>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

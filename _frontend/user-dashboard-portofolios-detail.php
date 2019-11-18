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
                            <div class="category-filter">
                                <ul class="user-dashboard-nav list-nostyle">
                                    <li><a href="user-dashboard.php">Account information</a></li>
                                    <li><a href="user-dashboard-personal-information.php">Personal information</a></li>
                                    <li><a href="user-dashboard-talent-information.php">Talent information</a></li>
                                    <li><a class="is-active" href="user-dashboard-portofolios.php">Portofolios</a></li>
                                    <li><a href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Portofolios: Event name</h2>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l9">
                                        <form action="become-an-artist-step-4.php" data-validate>
                                            <div>
                                                <fieldset>
                                                    <legend>Date of performance</legend>

                                                    <div class="bzg">
                                                        <div class="bzg_c" data-col="s4">
                                                            <div class="block-half">
                                                                <label class="sr-only" for="inputPerformanceDay">Day of performance</label>
                                                                <input class="form-input" id="inputPerformanceDay" type="text" name="performanceDay[]" placeholder="DD" ng-model="portofolios[$index].date" required>
                                                            </div>
                                                        </div>
                                                        <div class="bzg_c" data-col="s4">
                                                            <div class="block-half">
                                                                <label class="sr-only" for="inputPerformanceMonth">Month of performance</label>
                                                                <input class="form-input" id="inputPerformanceMonth" type="text" name="performanceMonth[]" placeholder="MM" ng-model="portofolios[$index].date" required>
                                                            </div>
                                                        </div>
                                                        <div class="bzg_c" data-col="s4">
                                                            <div class="block-half">
                                                                <label class="sr-only" for="inputPerformanceYear">Year of performance</label>
                                                                <input class="form-input" id="inputPerformanceYear" type="text" name="performanceYear[]" placeholder="YYYY" ng-model="portofolios[$index].date" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputEventName">Name of event</label>
                                                    <input class="form-input" id="inputEventName" type="text" required>
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputExperience">Describe your experience</label>
                                                    <textarea class="form-input" id="inputExperience" rows="5" required></textarea>
                                                </div>

                                                <div class="block-half">
                                                    <label class="form-label" for="inputEventUrl">URL of event</label>
                                                    <input class="form-input" id="inputEventUrl" type="text" required>
                                                    <small>Can be video or article review</small>
                                                </div>
                                            </div>

                                            <button class="btn btn--tosca">Update</button>
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

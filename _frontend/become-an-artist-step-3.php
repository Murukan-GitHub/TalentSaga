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
                            <li class="baa-steps-item">Upload</li>
                            <li class="baa-steps-item">Pricing</li>
                        </ol>

                        <form class="form-baa" action="become-an-artist-step-4.php" data-validate ng-controller="PortofolioFormController">
                            <fieldset class="block-half">
                                <legend class="text-center h3 text-caps">Portofolio</legend>
                                <p class="text-center">Tell us about your experience performing your talents.</p>

                                <div ng-repeat="portofolio in portofolios" on-finish-render="ngRepeatFinished">
                                    <div class="block-half">
                                        <label class="form-label" for="inputEventDate{{$index}}">Date of performance <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputEventDate{{$index}}" type="text" name="eventDate[{{$index}}]" ng-model="portofolios[$index].date" data-datepicker required>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventName{{$index}}">Name of event <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputEventName{{$index}}" type="text" name="eventName[{{$index}}]" ng-model="portofolios[$index].name" required>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputExperience{{$index}}">Describe your experience <span class="text-tosca">*</span></label>
                                        <textarea class="form-input" id="inputExperience{{$index}}" rows="5" name="experience[{{$index}}]" ng-model="portofolios[$index].experience" required></textarea>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventUrl{{$index}}">URL of event <span class="text-tosca">*</span></label>
                                        <input class="form-input" id="inputEventUrl{{$index}}" type="text" name="eventUrl[{{$index}}]" ng-model="portofolios[$index].url" required>
                                        <small>Can be video or article review</small>
                                    </div>
                                    <a href="" ng-click="removePortofolio(portofolio.id)" ng-hide="portofolio.id == 1"><small>Remove</small></a>
                                    <hr>
                                </div>

                                <a href="" ng-click="addPortofolio()">
                                    <span class="fa fa-fw fa-plus-circle"></span> Add more experience
                                </a>
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

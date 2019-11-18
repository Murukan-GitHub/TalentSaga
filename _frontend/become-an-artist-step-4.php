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
                            <li class="baa-steps-item">Pricing</li>
                        </ol>

                        <form class="form-baa" action="become-an-artist-step-5.php" data-validate ng-controller="UploadPhotosController">
                            <fieldset class="block-half">
                                <legend class="text-center h3 text-caps">Photos</legend>

                                <div class="upload-photos" ng-class="{ 'is-active': showUploader }">
                                    <label class="upload-photo" for="inputPhoto{{$index}}" data-filename="" ng-repeat="photoToUpload in photosToUpload" on-finish-render="attachInputListener">
                                        <input class="upload-photo-input sr-only" id="inputPhoto{{$index}}" type="file" name="photos[]" accept="image/*" ng-required="$index < 5">
                                        <span class="fa fa-2x fa-fw fa-picture-o"></span>
                                        <span>Click to add your photo</span>
                                    </label>
                                    <button class="upload-photo-add" type="button" ng-click="addInputFile()">
                                        <span class="fa fa-2x fa-fw fa-plus-circle"></span>
                                        Add more photo
                                    </button>
                                </div>
                            </fieldset>

                            <fieldset class="block-half">
                                <legend class="text-center h3 text-caps">Videos</legend>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="l9">
                                        <div class="block-half" ng-repeat="videoUrl in videoUrls">
                                            <input class="form-input" type="text" placeholder="Video URL e.g. YouTube or Vimeo">
                                        </div>
                                    </div>
                                    <div class="bzg_c" data-col="l3">
                                        <button class="btn" type="button" ng-click="addNewURLVideo()">+ Add url</button>
                                    </div>
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

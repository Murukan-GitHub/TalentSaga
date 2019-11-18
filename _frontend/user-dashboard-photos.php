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
                                    <li><a class="is-active" href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Photos</h2>

                                <div class="bzg">
                                    <div class="bzg_c" data-col="m6">
                                        <div class="bzg">
                                            <div class="bzg_c" data-col="s6">
                                                <div class="v-center block">
                                                    <small>Filter</small>
                                                    <select class="form-input form-input--small" style="width: 120px;">
                                                        <option value="Newest">Newest</option>
                                                        <option value="Oldest">Oldest</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="bzg_c" data-col="s6">
                                                <div class="v-center block">
                                                    <small>Category</small>
                                                    <select class="form-input form-input--small" style="width: 120px;">
                                                        <option value="Newest">Newest</option>
                                                        <option value="Oldest">Oldest</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bzg_c text-right block" data-col="m6">
                                        <button class="btn btn--sm btn--tosca js-upload-photos-modal-trigger">Add new photo</button>
                                    </div>
                                </div>

                                <div class="images-grid">
                                    <div>
                                        <div class="images-grid-item">
                                            <a class="images-grid-anchor is-video" href="https://www.youtube.com/watch?v=weeI1G46q0o" data-fancybox="gallery">
                                                <img class="images-grid-item-img" src="https://img.youtube.com/vi/weeI1G46q0o/mqdefault.jpg" alt="">
                                                <div class="images-grid-item-info text-ellipsis">Photo description Lorem ipsum dolor sit amet consectetur.</div>
                                            </a>

                                            <div class="images-grid-menus">
                                                <a class="images-grid-menu" href="user-dashboard-photos-edit.php">
                                                    <span class="fa fa-fw fa-pencil"></span>
                                                </a>
                                                <form method="POST" action="#">
                                                    <input type="hidden">
                                                    <button class="images-grid-menu" data-ts-confirm="are you sure?" type="button">
                                                        <span class="fa fa-fw fa-trash"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php for ($i = 1; $i <= 11; $i++) { ?>
                                    <div>
                                        <div class="images-grid-item">
                                            <a class="images-grid-anchor" href="assets/img/gallery-img.jpg" data-fancybox="gallery">
                                                <img class="images-grid-item-img" src="assets/img/team-1.jpg" alt="">
                                                <div class="images-grid-item-info text-ellipsis">Photo description Lorem ipsum dolor sit amet consectetur.</div>
                                            </a>

                                            <div class="images-grid-menus">
                                                <a class="images-grid-menu" href="user-dashboard-photos-edit.php">
                                                    <span class="fa fa-fw fa-pencil"></span>
                                                </a>
                                                <form method="POST" action="#">
                                                    <input type="hidden">
                                                    <button class="images-grid-menu" data-ts-confirm="are you sure?" type="button">
                                                        <span class="fa fa-fw fa-trash"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                                <ul class="pagination">
                                    <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
                                    <li><a class="active" href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
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

    <div class="upload-photos-modal" id="uploadPhotosModal">
        <div class="upload-photos-modal-dialog">
            <button class="upload-photos-modal-dialog-close">&times;</button>
            <form class="upload-photos-dropzone-form">
                <div class="upload-photos-dropzone" id="uploadPhotosDropZone">
                    <input class="sr-only" id="uploadPhotosInput" type="file" accept="image/png, image/jpg, image/jpeg" multiple>
                    <label class="upload-photos-dropzone-label" for="uploadPhotosInput">Click or drag pictures here to upload</label>

                    <div class="upload-photos-dropzone-previews" id="uploadPhotosDropZonePreview"></div>
                </div>

                <div class="text-center hidden" id="uploadPhotosCta">
                    <br>
                    <button class="btn btn--tosca">Upload images</button>
                </div>
            </form>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

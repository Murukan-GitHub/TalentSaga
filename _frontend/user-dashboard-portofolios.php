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
                                    <li><a class="is-active" href="user-dashboard-portofolios.php">Portofolios</a></li>
                                    <li><a href="user-dashboard-photos.php">Photos</a></li>
                                    <li><a href="user-dashboard-pricing-information.php">Pricing information</a></li>
                                </ul>
                            </div>
                            <div class="category-content">
                                <h2>Portofolios</h2>

                                <div class="text-right">
                                    <button class="btn btn--sm btn--tosca block-half" data-modal="#addPortofolio">Add portofolio</button>
                                    <template id="addPortofolio">
                                        <form data-validate>
                                            <div>
                                                <div class="block-half">
                                                    <label class="form-label" for="inputPerformanceDate">Date of performance</label>
                                                    <input class="form-input" id="inputPerformanceDate" type="text" data-datepicker required>
                                                </div>

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
                                    </template>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table--zebra table--dashboard">
                                        <thead>
                                            <tr>
                                                <th>Event name</th>
                                                <th>Date</th>
                                                <th>URL</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($i=0; $i < 10; $i++) { ?>
                                            <tr>
                                                <td>Event name</td>
                                                <td>10 Januari 2017</td>
                                                <td><a href="#">http://www.youtube.com?watch=j243SF3f</a></td>
                                                <td>
                                                    <a href="user-dashboard-portofolios-detail.php">Detail</a>
                                                    <a href="#">Delete</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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

<?php include '_include/scripts.php'; ?>

<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner-padded">
                    <div class="container">
                        <div class="bzg">
                            <div class="bzg_c" data-col="s7, m6">
                                <h2 class="text-caps">Job request</h2>
                            </div>
                            <div class="bzg_c" data-col="s5, m6">
                                <form class="activity-filter-form" action="">
                                    <label for="selectFilter">Sort by</label>
                                    <select class="form-input" id="selectFilter">
                                        <option value="Newest">Newest</option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <ul class="activity-list list-nostyle">
                            <?php for ($i=0; $i < 4; $i++) { ?>
                            <li class="activity-list-item">
                                <div class="activity">
                                    <form action="#" class="activity-form">
                                        <?php if ($i == 0) { ?>
                                        <fieldset disabled>
                                        <?php } else { ?>
                                        <fieldset>
                                        <?php } ?>
                                            <button>
                                                <span class="fa fa-fw fa-square-o"></span>
                                                <?php if ($i == 0) { ?>
                                                Job done
                                                <?php } else { ?>
                                                Mark as done
                                                <?php }?>
                                            </button>
                                        </fieldset>
                                    </form>

                                    <h3>Customer info</h3>
                                    <div class="block-half">
                                        <span class="fa fa-lg fa-fw fa-user-circle"></span> Adina Wirasti
                                    </div>
                                    <div class="block-half">
                                        <span class="fa fa-lg fa-fw fa-phone"></span> 081237513
                                    </div>
                                    <div class="block-half">
                                        <span class="fa fa-lg fa-fw fa-envelope"></span>
                                        anggita.kel@gmail.com
                                    </div>
                                    <hr>
                                    <h3>Event detail</h3>

                                    <p>Acara peringatan ulang tahun Provinsi Riau. Terdiri dari berbagai macam hiburan</p>

                                    <div class="block-half text-tosca">
                                        <span class="fa fa-lg fa-fw fa-calendar"></span>
                                        Saturday, 13 December 2016
                                    </div>
                                    <div class="block-half text-tosca">
                                        <span class="fa fa-lg fa-fw fa-clock-o"></span>
                                        17.00 - 19.00 (2 hours)
                                    </div>
                                    <div class="block-half text-tosca">
                                        <span class="fa fa-lg fa-fw fa-map-marker"></span>
                                        Kantor Bupati Riau
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>

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
            </main>
        </div>
        <div class="sticky-footer-container-item">
            <?php include '_include/footer.php'; ?>
        </div>
    </div>

<?php include '_include/scripts.php'; ?>

<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner-padded">
                    <div class="container">
                        <h2 class="fancy-heading">Success stories</h2>

                        <ul class="success-stories-list list-nostyle">
                            <?php for ($i=0; $i < 8; $i++) { ?>
                            <li class="success-stories-list-item">
                                <article class="success-story-news">
                                    <img src="assets/img/success-story-img-thumb.jpg" alt="">

                                    <div class="success-story-news-desc">
                                        <h3 class="success-story-news-title">Svetlana, a salsa dancer from moldova</h3>

                                        <p class="success-story-news-summary">Several times people told me, if they wanted to learn salsa Lorem ipsum dolor sit amet.</p>

                                        <a href="success-story-detail.php">Read more</a>
                                    </div>
                                </article>
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

<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner">
                    <div class="container">
                        <figure class="category-header block">
                            <img class="category-header-img" src="assets/img/category-header-image.jpg" alt="">
                            <figure class="category-header-info">
                                <h2 class="category-header-heading">Portofolio</h2>
                            </figure>
                        </figure>

                        <ul class="portofolio-list list-nostyle">
                            <?php for ($i=0; $i < 3; $i++) { ?>
                            <li>
                                <article class="portofolio">
                                    <time class="portofolio-time">11 Agustus 2014</time>
                                    <h3 class="portofolio-title">Indonesia Fashion Week</h3>
                                    <p class="portofolio-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil veniam accusamus, suscipit dolorum dicta corrupti. Minus voluptas, unde commodi rem cumque officia, esse tempora perspiciatis.</p>
                                    <a href="#">
                                        <span class="fa fa-fw fa-link"></span>
                                    </a>
                                    <a href="#">
                                        <span class="fa fa-fw fa-youtube-play"></span>
                                    </a>
                                </article>
                            </li>
                            <?php } ?>
                        </ul>

                        <ul class="pagination">
                            <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
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

<?php include '_include/head.php'; ?>

    <div class="sticky-footer-container">
        <div class="sticky-footer-container-item" style="height: 0;">
            <?php include '_include/header.php'; ?>
        </div>
        <div class="sticky-footer-container-item --pushed">
            <main class="site-main">
                <div class="site-main-inner">
                    <section class="section section--white">
                        <div class="container">
                            <h2 class="fancy-heading">Blog</h2>
                        </div>
                    </section>

                    <div class="container">
                        <ul class="blog-list list-nostyle">
                            <?php for ($i = 0; $i < 8; $i++) { ?>
                            <li class="blog-list-item">
                                <a class="blog-entry" href="blog-detail.php">
                                    <article>
                                        <figure>
                                            <img src="//placehold.it/400x200" alt="">
                                        </figure>
                                        <time>29 August 2017</time>
                                        <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et, quo.</h3>
                                    </article>
                                </a>
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

<section class="home-section">
    <div class="container">
        <h2 class="home-section-heading">Favorite talent</h2>

        <div class="talent-list">
            <?php for ($i=1; $i <= 8; $i++) { ?>
            <div class="talent-list-item">
                <a class="talent-anchor" href="#">
                    <figure class="talent">
                        <img class="talent-img" src="assets/img/thumb-talent-<?= $i % 4 + 1 ?>.jpg" alt="">
                        <figcaption class="talent-desc">
                            <span class="talent-label">Actor</span>
                            <h3 class="talent-name">Adit Erlangga, 24</h3>
                            <span class="talent-location">
                                <span class="fa fa-map-marker"></span>
                                <span>Jakarta</span>
                            </span>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<header class="site-header site-header--home">
    <div class="container">
        <div class="site-header-sections">
            <div class="site-header-logo-area">
                <a href="index.php"><h1 class="site-header-logo">Talent Saga</h1></a>

                <div class="popup">
                    <button class="site-nav-btn popup-btn">
                        <span class="fa fa-fw fa-th-large"></span>
                        <span class="fa fa-angle-down"></span>
                    </button>

                    <div class="popup-content">
                        <nav class="site-nav">
                            <h2 class="site-nav-heading"><span class="sr-only">Site nav</span> categories</h2>

                            <ul class="site-nav-list list-nostyle">
                                <li class="site-nav-list-item">
                                    <a class="site-nav-anchor" href="talent-list.php">
                                        <img class="site-nav-icon" src="assets/img/nav-icon-music.png" alt="">
                                        <span>Music</span>
                                    </a>
                                </li>
                                <li class="site-nav-list-item">
                                    <a class="site-nav-anchor" href="talent-list.php">
                                        <img class="site-nav-icon" src="assets/img/nav-icon-modelling.png" alt="">
                                        <span>Modelling</span>
                                    </a>
                                </li>
                                <li class="site-nav-list-item">
                                    <a class="site-nav-anchor" href="talent-list.php">
                                        <img class="site-nav-icon" src="assets/img/nav-icon-actor.png" alt="">
                                        <span>Actor/Actrees</span>
                                    </a>
                                </li>
                                <li class="site-nav-list-item">
                                    <a class="site-nav-anchor" href="talent-list.php">
                                        <img class="site-nav-icon" src="assets/img/nav-icon-mc.png" alt="">
                                        <span>Master of Ceremony</span>
                                    </a>
                                </li>
                                <li class="site-nav-list-item">
                                    <a class="site-nav-anchor" href="talent-list.php">
                                        <img class="site-nav-icon" src="assets/img/nav-icon-magician.png" alt="">
                                        <span>Magician</span>
                                    </a>
                                </li>
                                <li class="site-nav-list-item">
                                    <a class="site-nav-anchor" href="talent-list.php">
                                        <img class="site-nav-icon" src="assets/img/nav-icon-comedian.png" alt="">
                                        <span>Comedian</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <form class="site-header-search" action="search-result.php" role="search">
                <input class="site-header-search-input" type="text" placeholder="Search profession, name, location, etc...">
                <button class="site-header-search-btn">
                    <span class="fa fa-fw fa-search"></span>
                    <span class="sr-only">Search</span>
                </button>
            </form>
            <div class="site-header-user-actions">
                <button class="site-header-search-toggle">
                    <span class="fa fa-fw fa-search"></span>
                </button>
                <a class="btn btn--outline btn--red cta-become-an-artist" href="become-an-artist-step-1.php">Become an artist</a>
                <div class="popup popup--right">
                    <a class="btn btn--outline btn--black popup-btn site-header-login-btn" href="#">
                        <span class="hidden-medium"><i class="fa fa-fw fa-sign-in"></i></span>
                        <span class="hidden-small">Registrieren / Einloggen</span>
                    </a>
                    <div class="popup-content">
                        <form class="form-popup-login" action="index-logged-in.php">
                            <div class="block-half">
                                <label class="sr-only" for="inputUsername">Username</label>
                                <input class="form-input" type="text" placeholder="Username" required>
                            </div>
                            <div class="block-half">
                                <label class="sr-only" for="inputUsername">Password</label>
                                <input class="form-input" type="password" placeholder="Password" required>
                            </div>
                            <div class="block-half v-center v-center--spread">
                                <label for="rememberMe">
                                    <input id="rememberMe" type="checkbox">
                                    <small>Remember me</small>
                                </label>

                                <a href="#"><small>Forgot password?</small></a>
                            </div>
                            <button class="btn btn--block btn--tosca">Login</button>
                        </form>

                        <hr class="block-half">

                        <a class="btn btn--block btn--outline btn--fb block-half" href="#">
                            <span class="fa fa-fw fa-facebook-official"></span>
                            Login with Facebook
                        </a>
                        <a class="btn btn--block btn--outline btn--gplus block-half" href="#">
                            <span class="fa fa-fw fa-google-plus-official"></span>
                            Login with Google
                        </a>

                        <hr class="block-half">

                        <div class="text-center"><small>Don't have accont?</small></div>
                        <a class="btn btn--block btn--tosca btn--outline" href="#">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

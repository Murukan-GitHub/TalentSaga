@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <section class="section section--white">
            <div class="container">
                <h2 class="fancy-heading">About us</h2>

                <div class="text-center">
                    <p>Our mission is to help the talents and clients to interact with and find each other</p>
                    <p>We provide a platform for the talent to enhance their visibilities and therefore their careers, and at the same time for the talent seekers to find the most matching talent that suit their needs.</p>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="fancy-heading">Amazing people</h2>

                <?php $names = [
                    ['Andre Sasongko', 'Founder &amp; CEO'],
                    ['Muller Gracio', 'Founder &amp; COO'],
                    ['Ari Pratomo', 'Programmer'],
                    ['Robby Sanjaya', 'Designer']
                ] ?>

                <ul class="team-list list-nostyle">
                    <?php foreach ($names as $key => $value) { ?>
                    <li class="team-list-item">
                        <figure class="team">
                            <img src="{{ asset('frontend/assets/img/team-'.($key ? $key : '').'.jpg') }}" alt="">
                            <figcaption>
                                <h3><?= $value[0] ?></h3>
                                <small><?= $value[1] ?></small>
                            </figcaption>
                        </figure>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </section>
    </div>
@endsection

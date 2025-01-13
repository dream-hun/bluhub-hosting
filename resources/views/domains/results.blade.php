<x-app-layout>

    <!-- HERO BANNER ONE -->
    <section class="rts-hero-three rts-hero__one rts-hosting-banner domain-checker-padding banner-default-height">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="rts-hero__content domain">
                        <h1 data-sal="slide-down" data-sal-delay="100" data-sal-duration="800">Domain Search Results</h1>
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="domain-result mt-4">
                            <h3>{{ $searchedDomain }}</h3>
                            @if($isAvailable)
                                <div class="alert alert-success">
                                    <h4>Domain is available!</h4>
                                    <p>Register now at ${{ $prices['register'] }}/year</p>
                                    <a href="#" class="rts-btn">Register Now</a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h4>Domain is already taken</h4>
                                    <p>Try another domain name or consider these alternatives:</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('domains.index') }}" class="rts-btn">Search Another Domain</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- HERO BANNER ONE END -->

    <!-- DOMAIN PRICING -->
    <section class="rts-domain-pricing-area pt--120 pb--120">
        <div class="container">


            <div class="section-inner">
                <div class="row g-5">
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="200"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-01.svg" alt=""></div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="300"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-02.svg" alt=""></div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="200"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-03.svg" alt=""></div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="300"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-04.svg" alt="">
                            </div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="200"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-05.svg" alt="">
                            </div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="300"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-06.svg" alt="">
                            </div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="200"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-07.svg" alt="">
                            </div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="300"
                        data-sal-duration="800">
                        <div class="pricing-wrapper">
                            <div class="logo"><img src="assets/images/pricing/domain-08.svg" alt="">
                            </div>
                            <div class="content">
                                <p class="desc">Think about why buy domain name in the first place</p>
                                <div class="price-area">
                                    <span class="pre">$9.99</span>
                                    <span class="now">$6.99</span>
                                </div>
                                <div class="button-area">
                                    <a href="#" class="pricing-btn rts-btn">Register</a>
                                    <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- DOMAIN PRICING END -->

    <!-- DOMAIN PRICING -->
    <section class="rts-domain-pricing-area area-2 pt--120 pb--120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="section-title-area w-550">
                    <h2 class="section-title">Top Domains Price List</h2>
                    <p class="desc">Keep in mind that TLD prices can change over time, and different
                        registrars may offer different deals and packages
                    </p>
                </div>

            </div>
            <div class="section-inner">
                <div class="pricing-table-area">
                    <div class="rts-pricing-plan__tab pricing__tab">
                        <div class="tab__button">
                            <div class="tab__button__item">
                                <button class="active tab__btn" data-tab="all">All</button>
                                <button class="tab__btn" data-tab="sale">Sale</button>
                                <button class="tab__btn" data-tab="newest">Newest</button>
                                <button class="tab__btn" data-tab="popular">Popular</button>
                                <button class="tab__btn" data-tab="geographic">Geographic</button>
                            </div>
                        </div>
                    </div>
                    <!-- PRICING PLAN -->
                    <div class="tab__content open" id="all">
                        <table class="table table-hover table-responsive">
                            <thead class="heading__bg">
                                <tr>
                                    <th class="cell">TLD</th>
                                    <th class="cell">Register</th>
                                    <th class="cell">Renew</th>
                                    <th class="cell">Transfer</th>
                                </tr>
                            </thead>
                            <tbody class="table__content">
                                <tr>
                                    <td class="package">.com</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.net</td>
                                    <td class="process">$8.99</td>
                                    <td class="ram">$19.99</td>
                                    <td class="storage">$9.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.online</td>
                                    <td class="process">$5.99</td>
                                    <td class="ram">$12.99</td>
                                    <td class="storage">$6.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.shop</td>
                                    <td class="process">$9.99</td>
                                    <td class="ram">$20.99</td>
                                    <td class="storage">$10.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.xyz</td>
                                    <td class="process">$4.99</td>
                                    <td class="ram">$10.99</td>
                                    <td class="storage">$5.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.club</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- PRICING PLAN -->
                    <div class="tab__content" id="sale">
                        <table class="table table-hover table-responsive">
                            <thead class="heading__bg">
                                <tr>
                                    <th class="cell">TLD</th>
                                    <th class="cell">Register</th>
                                    <th class="cell">Renew</th>
                                    <th class="cell">Transfer</th>
                                </tr>
                            </thead>
                            <tbody class="table__content">
                                <tr>
                                    <td class="package">.com</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.net</td>
                                    <td class="process">$8.99</td>
                                    <td class="ram">$19.99</td>
                                    <td class="storage">$9.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.online</td>
                                    <td class="process">$5.99</td>
                                    <td class="ram">$12.99</td>
                                    <td class="storage">$6.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.shop</td>
                                    <td class="process">$9.99</td>
                                    <td class="ram">$20.99</td>
                                    <td class="storage">$10.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.xyz</td>
                                    <td class="process">$4.99</td>
                                    <td class="ram">$10.99</td>
                                    <td class="storage">$5.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.club</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- PRICING PLAN -->
                    <div class="tab__content" id="newest">
                        <table class="table table-hover table-responsive">
                            <thead class="heading__bg">
                                <tr>
                                    <th class="cell">TLD</th>
                                    <th class="cell">Register</th>
                                    <th class="cell">Renew</th>
                                    <th class="cell">Transfer</th>
                                </tr>
                            </thead>
                            <tbody class="table__content">
                                <tr>
                                    <td class="package">.com</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.net</td>
                                    <td class="process">$8.99</td>
                                    <td class="ram">$19.99</td>
                                    <td class="storage">$9.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.online</td>
                                    <td class="process">$5.99</td>
                                    <td class="ram">$12.99</td>
                                    <td class="storage">$6.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.shop</td>
                                    <td class="process">$9.99</td>
                                    <td class="ram">$20.99</td>
                                    <td class="storage">$10.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.xyz</td>
                                    <td class="process">$4.99</td>
                                    <td class="ram">$10.99</td>
                                    <td class="storage">$5.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.club</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- PRICING PLAN -->
                    <div class="tab__content" id="popular">
                        <table class="table table-hover table-responsive">
                            <thead class="heading__bg">
                                <tr>
                                    <th class="cell">TLD</th>
                                    <th class="cell">Register</th>
                                    <th class="cell">Renew</th>
                                    <th class="cell">Transfer</th>
                                </tr>
                            </thead>
                            <tbody class="table__content">
                                <tr>
                                    <td class="package">.com</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.net</td>
                                    <td class="process">$8.99</td>
                                    <td class="ram">$19.99</td>
                                    <td class="storage">$9.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.online</td>
                                    <td class="process">$5.99</td>
                                    <td class="ram">$12.99</td>
                                    <td class="storage">$6.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.shop</td>
                                    <td class="process">$9.99</td>
                                    <td class="ram">$20.99</td>
                                    <td class="storage">$10.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.xyz</td>
                                    <td class="process">$4.99</td>
                                    <td class="ram">$10.99</td>
                                    <td class="storage">$5.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.club</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- PRICING PLAN -->
                    <div class="tab__content" id="geographic">
                        <table class="table table-hover table-responsive">
                            <thead class="heading__bg">
                                <tr>
                                    <th class="cell">TLD</th>
                                    <th class="cell">Register</th>
                                    <th class="cell">Renew</th>
                                    <th class="cell">Transfer</th>
                                </tr>
                            </thead>
                            <tbody class="table__content">
                                <tr>
                                    <td class="package">.com</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.net</td>
                                    <td class="process">$8.99</td>
                                    <td class="ram">$19.99</td>
                                    <td class="storage">$9.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.online</td>
                                    <td class="process">$5.99</td>
                                    <td class="ram">$12.99</td>
                                    <td class="storage">$6.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.shop</td>
                                    <td class="process">$9.99</td>
                                    <td class="ram">$20.99</td>
                                    <td class="storage">$10.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.xyz</td>
                                    <td class="process">$4.99</td>
                                    <td class="ram">$10.99</td>
                                    <td class="storage">$5.99</td>
                                </tr>
                                <tr>
                                    <td class="package">.club</td>
                                    <td class="process">$6.99</td>
                                    <td class="ram">$17.99</td>
                                    <td class="storage">$7.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- DOMAIN PRICING END -->
</x-app-layout>

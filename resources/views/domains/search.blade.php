<x-app-layout>

    <!-- HERO BANNER ONE -->
    <section class="rts-hero-three rts-hero__one rts-hosting-banner domain-checker-padding banner-default-height">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="rts-hero__content domain">
                        <h1 data-sal="slide-down" data-sal-delay="100" data-sal-duration="800">Find Best Unique Domains
                            Checker!
                        </h1>
                        <p class="description" data-sal="slide-down" data-sal-delay="200" data-sal-duration="800">Web
                            Hosting, Domain Name and Hosting Center Solutions</p>
                        <form action="" data-sal-delay="300" data-sal-duration="800">
                            <div class="rts-hero__form-area">
                                <input type="hidden" name="domain" value="register">
                                <input type="hidden" name="a" value="add">
                                <input type="text" placeholder="find your domain name" name="query" required>
                                <div class="select-button-area">
                                    <select name="select" id="select" class="price__select">
                                        @foreach ($tlds as $tld)
                                            <option value="{{ $tld->tld }}">{{ $tld->tld }}</option>
                                        @endforeach


                                    </select>
                                    <button type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <div class="banner-content-tag" data-sal-delay="400" data-sal-duration="800">
                            <p class="desc">Popular Domain:</p>
                            <ul class="tag-list">
                                @foreach ($popularDomains as $popular)
                                    <li><span>{{ $popular->tld }}</span><span>{{ $popular->formattedRegistrationPrice() }}</span>
                                    </li>
                                @endforeach


                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-shape-area">
            <img class="three" src="assets/images/banner/banner-bg-element.svg" alt="">
        </div>
    </section>
    <!-- HERO BANNER ONE END -->
    <!-- DOMAIN PRICING -->
    <section class="rts-domain-pricing-area pt--120 pb--120">
        <div class="container">

            <div class="row justify-content-center">
                <div class="section-title-area w-570">
                    <h2 class="section-title" data-sal="slide-down" data-sal-delay="100" data-sal-duration="800">
                        {{config('app.name')}}
                        Straight forward Domain Pricing</h2>
                    <p class="desc" data-sal="slide-down" data-sal-delay="200" data-sal-duration="800">Straightforward
                        Domain Pricing</p>
                </div>
            </div>
            <div class="section-inner">
                <div class="row g-5">
                    @foreach($allTlds as $tld)
                        <div class="col-lg-4 col-xl-3 col-md-4 col-sm-6" data-sal="slide-down" data-sal-delay="200"
                             data-sal-duration="800">
                            <div class="pricing-wrapper">
                                <div class="logo">{{ $tld->tld }}</div>
                                <div class="content">
                                    <p class="desc">Think about why buy domain name in the first place</p>
                                    <div class="price-area">

                                        <span class="now">{{$tld->formattedRegistrationPrice()}}</span>
                                    </div>
                                    <div class="button-area">
                                        <a href="#" class="pricing-btn rts-btn">Register</a>
                                        <a href="#" class="pricing-btn rts-btn border">Transfer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
    </section>
    <!-- DOMAIN PRICING END -->

</x-app-layout>

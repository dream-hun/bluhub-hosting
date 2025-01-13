<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Your reliable secure and hosting service provider</title>
    <!-- Preconnect to Google Fonts and Google Fonts Static -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Importing Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,600;0,700;1,400;1,800&display=swap"
        rel="stylesheet">
    <!-- all styles -->
    <link rel="preload stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" as="style">
    <!-- fontawesome css -->
    <link rel="preload stylesheet" href="{{ asset('assets/css/plugins/fontawesome.min.css') }}" as="style">
    <!-- Custom css -->
    <link rel="preload stylesheet" href="{{ asset('assets/css/style.css') }}" as="style">
</head>

<body>
    <x-header-component />
    <!-- HERO BANNER ONE -->
    <section class="rts-hero rts-hero__one banner-style-home-one">
        <div class="container">
            <div class="rts-hero__blur-area"></div>
            <div class="row align-items-end position-relative">
                <div class="col-lg-6">
                    <div class="rts-hero__content w-550">
                        <h6 data-sal="slide-down" data-sal-delay="300" data-sal-duration="800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M23.9799 11.9805C23.9799 10.3545 23.2659 8.8205 22.0549 7.8565C22.1949 6.2345 21.6149 4.6455 20.4649 3.4945C19.3149 2.3455 17.7299 1.7635 16.1879 1.9395C14.1739 -0.616499 9.82288 -0.664499 7.85588 1.9045C4.62288 1.5205 1.51388 4.5645 1.93988 7.7725C-0.616121 9.7865 -0.665121 14.1375 1.90488 16.1055C1.76488 17.7275 2.34488 19.3165 3.49488 20.4675C4.64488 21.6165 6.23188 22.1985 7.77188 22.0225C9.78588 24.5785 14.1369 24.6265 16.1039 22.0575C17.7239 22.1965 19.3139 21.6185 20.4649 20.4675C21.6139 19.3175 22.1939 17.7275 22.0199 16.1905C23.2659 15.1425 23.9799 13.6085 23.9799 11.9825V11.9805ZM7.97988 8.9805C7.98588 7.6725 9.97388 7.6725 9.97988 8.9805C9.97388 10.2885 7.98588 10.2885 7.97988 8.9805ZM10.8119 15.5355C10.5039 15.9985 9.87888 16.1165 9.42488 15.8125C8.96488 15.5065 8.84088 14.8855 9.14788 14.4255L13.1479 8.4255C13.4539 7.9665 14.0739 7.8405 14.5349 8.1485C14.9949 8.4545 15.1189 9.0755 14.8119 9.5355L10.8119 15.5355ZM14.9799 15.9805C13.6719 15.9745 13.6719 13.9865 14.9799 13.9805C16.2879 13.9865 16.2879 15.9745 14.9799 15.9805Z"
                                    fill="#FFC107" />
                            </svg>
                            30% Discount first month purchase
                        </h6>
                        <h1 class="heading" data-sal="slide-down" data-sal-delay="300" data-sal-duration="800">Premium
                            Hosting
                            Technologies
                        </h1>
                        <p class="description" data-sal="slide-down" data-sal-delay="400" data-sal-duration="800">
                            Developing smart solutions in-house and adopting the latest speed and security technologies
                            is our passion.</p>
                        <div class="rts-hero__content--group" data-sal="slide-down" data-sal-delay="500"
                            data-sal-duration="800">
                            <a href="pricing.html" class="primary__btn white__bg">Get Started <i
                                    class="fa-regular fa-long-arrow-right"></i></a>
                            <a href="pricing-two.html" class="btn__zero plan__btn">Plans & Pricing <i
                                    class="fa-regular fa-long-arrow-right"></i></a>
                        </div>
                        <p data-sal="slide-down" data-sal-delay="600" data-sal-duration="800"><img
                                src="assets/images/icon/dollar.svg" alt="">Starting from <span>$2.95</span> per
                            month
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="rts-hero__images position-relative">
                        <div class="rts-hero-main">
                            <div class="image-main ">
                                <img class="main top-bottom2" src="assets/images/banner/hosting-01.svg" alt="">
                            </div>
                            <img class="hero-shape one" src="assets/images/banner/hosting.svg" alt="">
                        </div>
                        <div class="rts-hero__images--shape">
                            <div class="one top-bottom">
                                <img src="assets/images/banner/left.svg" alt="">
                            </div>
                            <div class="two bottom-top">
                                <img src="assets/images/banner/left.svg" alt="">
                            </div>
                            <div class="three top-bottom">
                                <img src="assets/images/banner/top.svg" alt="">
                            </div>
                            <div class="four bottom-top">
                                <img src="assets/images/banner/right.svg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- HERO BANNER ONE END -->

    <x-footer-component />
</body>

</html>

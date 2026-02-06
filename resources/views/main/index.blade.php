@extends('main.layouts.app')

@section('content')
    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <img class="hero-banner__image" src="./assets/images/slider-1.jpg" alt="" draggable="false">
        <div class="hero-banner__content">
            <h1 class="hero-banner__title">Empowering <span class="hero-banner__highlight-1">Communities,</span>
                <span class="hero-banner__highlight-2">One Purchase at a Time.</span>
            </h1>
            <p class="hero-banner__subtitle">Vibrant marketplace with diverse products and shoppers. Include natural
                elements for sustainability.</p>
            <div class="hero-banner__actions">
                <a class="hero-banner__btn btn-basic dark-btn text-highlight-group" href="###">
                    <span class="text">Shop Now</span>
                </a>
                <a class="hero-banner__btn btn-basic" href="###">
                    <span class="text">Donate Now</span>
                </a>
            </div>
        </div>
    </section>
    <!-- End of Hero Banner Section -->

    <!-- Featured Collection Section -->
    <section class="featured-collection">

        <h2 class="featured-collection__heading">Featured Categories</h2>

        <div class="featured-collection__product-grid">
            @php
                $newArrivals = [
                    [
                        'front' => './assets/images/products/1.webp',
                        'back' => './assets/images/products/1[0].webp',
                        'name' => 'Summer Two Piece Set',
                        'price' => '69,99',
                        'bestseller' => true,
                        'soldOut' => false,
                        'discountPercent' => 50,
                    ],
                    [
                        'front' => './assets/images/products/2.webp',
                        'back' => './assets/images/products/2[0].webp',
                        'name' => 'Nike Sportswear Tee Shirts',
                        'price' => '9,514',
                        'bestseller' => false,
                        'soldOut' => false,
                        'discountPercent' => 10,
                    ],
                    [
                        'front' => './assets/images/products/3.webp',
                        'back' => './assets/images/products/3[0].webp',
                        'name' => "Women's Straight Leg Pants",
                        'price' => '19,999',
                        'bestseller' => true,
                        'soldOut' => false,
                        'discountPercent' => 0,
                    ],
                    [
                        'front' => './assets/images/products/4.webp',
                        'back' => './assets/images/products/4[0].webp',
                        'name' => 'V-neck button down vest',
                        'price' => '8,999',
                        'bestseller' => true,
                        'soldOut' => true,
                        'discountPercent' => 0,
                    ],
                    [
                        'front' => './assets/images/products/5.webp',
                        'back' => './assets/images/products/5[0].webp',
                        'name' => 'Half sleeve crop top',
                        'price' => '12,750',
                        'bestseller' => false,
                        'soldOut' => false,
                        'discountPercent' => 0,
                    ],
                    [
                        'front' => './assets/images/products/6.webp',
                        'back' => './assets/images/products/6[0].webp',
                        'name' => "Women's straight leg pants",
                        'price' => '24,587',
                        'bestseller' => false,
                        'soldOut' => true,
                        'discountPercent' => 20,
                    ],
                ];
            @endphp
            @foreach ($newArrivals as $item)
                <x-product-item :item="$item" />
            @endforeach
        </div>

        <div class="flex justify-center items-center">
            <a href="###" class="default-btn btn-basic">
                <span class="text">View All </span>
            </a>
        </div>
    </section>
    <!-- End of Featured Collection Section -->

    <!-- Cybercore Overview Section -->
    <section class="beliefs-manifesto">
        <div class="beliefs-top-wrapper">
            <h2 class="beliefs-heading">Turning Transactions into Transformations</h2>
            <p class="beliefs-para">Every purchase or donation creates meaningful impact by supporting vendors and
                empowering communities worldwide.</p>
        </div>

        <div class="beliefs-grid">
            <article class="belief-card">
                <i class="belief-icon iconify" data-icon="mdi:store-check"></i>
                <div class="belief-content">
                    <h3 class="belief-title">Vendors List Ethical Products</h3>
                    <p class="belief-description">
                        Trusted vendors and partners list ethically sourced products and donated goods that align
                        with our sustainability and social impact mission.
                    </p>
                </div>
            </article>

            <article class="belief-card">
                <i class="belief-icon iconify" data-icon="mdi:cart-heart"></i>
                <div class="belief-content">
                    <h3 class="belief-title">Buy or Donate with Purpose</h3>
                    <p class="belief-description">
                        Shoppers can purchase products for themselves or donate essential items, knowing every
                        transaction supports positive economic change.
                    </p>
                </div>
            </article>

            <article class="belief-card last-child">
                <i class="belief-icon iconify" data-icon="mdi:account-group"></i>
                <div class="belief-content">
                    <h3 class="belief-title">Communities Thrive</h3>
                    <p class="belief-description">
                        Your support helps create jobs, fund skills training, reduce waste, and deliver essential
                        goods to communities that need them most.
                    </p>
                </div>
            </article>
        </div>

        <div class="beliefs-manifesto___cta-wrapper">
            <a href="###" class="beliefs-manifesto___cta-btn btn-basic">
                <span class="text">Learn More</span>
            </a>
        </div>
    </section>
    <!-- End of Cybercore Overview Section -->

    <!-- Cybercore Overview Section -->
    <section class="cybercore-overview">
        <div class="overview-container">
            <figure class="overview-image-wrapper">
                <img src="https://images.pexels.com/photos/34623866/pexels-photo-34623866.jpeg"
                    alt="Community marketplace with people shopping and colorful stalls outdoors" draggable="false"
                    class="overview-image">
            </figure>

            <aside class="overview-details">
                <h2 class="overview-heading">Creating Change
                    <span class="overview-heading__highlight">Through Commerce</span>
                </h2>
                <div class="overview-para-wrapper">
                    <p class="overview-text">
                        Globalgood Ecommerce is a multi-vendor marketplace built to empower communities and promote
                        ethical, sustainable shopping. By connecting trusted vendors, donors, and conscious
                        shoppers, we turn everyday purchases into opportunities for social impact, job creation, and
                        environmental responsibility.
                    </p>
                    <p class="overview-text">
                        Every product sold supports fair trade, reduces waste, and helps fund programs that
                        strengthen local economies and create a better future for all.
                    </p>
                </div>

                <a href="###" class="overview-action btn-basic">
                    <span class="text">Learn More About Us</span>
                </a>
            </aside>
        </div>
    </section>
    <!-- End of Cybercore Overview Section -->

    <!-- Why Cyber Section -->
    <section class="why-cyber-section">
        <div class="why-cyber-container">
            <h2 class="why-cyber-title">
                Creating Opportunity, <span class="why-cyber__highlight">Transforming Lives</span>
                <span class="why-cyber-title-accent">
                    Jobs. Skills. Sustainable Futures.
                </span>
            </h2>


            <p class="why-cyber-overview">
                Every purchase, donation, or volunteer effort helps us empower communities, create jobs,
                and provide essential goods. Your support directly contributes to building a fair,
                sustainable, and debt-free global economy.
            </p>

            <div class="trust-metrics-grid">

                <!-- Products Distributed -->
                <article class="trust-metric-card industries-metric">
                    <h3 class="trust-metric-heading">Products Distributed</h3>
                    <div class="trust-metric-body">
                        <p class="trust-metric-number">20,000+</p>
                        <p class="trust-metric-caption">
                            Essential goods delivered to communities worldwide
                        </p>
                    </div>
                </article>

                <!-- Communities Supported -->
                <article class="trust-metric-card brands-metric">
                    <h3 class="trust-metric-heading">Communities Supported</h3>
                    <div class="trust-metric-body">
                        <p class="trust-metric-number">50+</p>
                        <p class="trust-metric-caption">
                            Communities empowered through programs and donations
                        </p>
                    </div>
                </article>

                <!-- Jobs Created -->
                <article class="trust-metric-card team-metric">
                    <h3 class="trust-metric-heading">Jobs Created</h3>
                    <div class="trust-metric-body">
                        <p class="trust-metric-number">500+</p>
                        <div class="team-avatar-stack">
                            <img class="team-avatar-image"
                                src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg"
                                alt="Community member sewing" draggable="false">
                            <img class="team-avatar-image"
                                src="https://images.pexels.com/photos/160414/female-portrait-studio-attractive-160414.jpeg"
                                alt="Local job training session" draggable="false">
                            <img class="team-avatar-image"
                                src="https://images.pexels.com/photos/7322197/pexels-photo-7322197.jpeg"
                                alt="Community member sewing" draggable="false">
                            <img class="team-avatar-image"
                                src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg"
                                alt="Local job training session" draggable="false">
                            <img class="team-avatar-image"
                                src="https://images.pexels.com/photos/5286744/pexels-photo-5286744.jpeg"
                                alt="Local job training session" draggable="false">
                        </div>
                    </div>
                </article>

                <!-- Volunteers Engaged -->
                <article class="trust-metric-card customers-metric">
                    <h3 class="trust-metric-heading">Volunteers Engaged</h3>
                    <div class="trust-metric-body">
                        <p class="trust-metric-number">200+</p>
                        <p class="trust-metric-caption">
                            Passionate individuals contributing to positive change
                        </p>
                    </div>
                </article>

            </div>
        </div>
    </section>
    <!-- End of Cybercore Overview Section -->

    <!-- Partners Section -->
    <section class="partners-section">
        <div class="partners-header">
            <h2 class="partners-title">Our Trusted Vendors & Partners</h2>
            <p class="partners-description">
                We collaborate with ethical brands and global partners committed to sustainability,
                fairness, and community empowerment.
            </p>
        </div>

        <div class="partners-marquee-wrapper">
            <!-- Marquee Left -->
            <div class="partners-marquee marquee-left">
                <!-- AliExpress -->
                <article class="partner-card">
                    <img src="./assets/images/aliexpress.svg" alt="AliExpress logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-ethical">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:handshake-outline"></i>
                            </span>
                            <span class="partner-tag-text">Ethical</span>
                        </div>
                    </div>
                </article>

                <!-- Amazon -->
                <article class="partner-card">
                    <img src="./assets/images/amazon.svg" alt="Amazon logo" draggable="false">
                    <div class="partner-tags mt-[3px]">
                        <div class="partner-tag tag-green">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:recycle-variant"></i>
                            </span>
                            <span class="partner-tag-text">Green</span>
                        </div>
                        <div class="partner-tag tag-local">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="pepicons-pencil:map"></i>
                            </span>
                            <span class="partner-tag-text">Local</span>
                        </div>
                    </div>
                </article>

                <!-- DHL -->
                <article class="partner-card">
                    <img src="./assets/images/dhl.svg" alt="DHL logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-logistics">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:truck-outline"></i>
                            </span>
                            <span class="partner-tag-text">Green Logistics</span>
                        </div>
                    </div>
                </article>

                <!-- Etsy -->
                <article class="partner-card">
                    <img src="./assets/images/etsy.svg" alt="Etsy logo" draggable="false">
                    <div class="partner-tags mt-[3px]">
                        <div class="partner-tag tag-fair">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:scale-balance"></i>
                            </span>
                            <span class="partner-tag-text">Fair Trade</span>
                        </div>
                        <div class="partner-tag tag-sustainable">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:leaf-circle-outline"></i>
                            </span>
                            <span class="partner-tag-text">Sustainable</span>
                        </div>
                    </div>
                </article>

                <!-- Patagonia -->
                <article class="partner-card">
                    <img src="./assets/images/patagonia.svg" alt="Patagonia logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-sustainable">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:pine-tree"></i>
                            </span>
                            <span class="partner-tag-text">Eco Leader</span>
                        </div>
                    </div>
                </article>

                <!-- duplicated list (needed for infinite loop) -->

                <!-- AliExpress -->
                <article class="partner-card">
                    <img src="./assets/images/aliexpress.svg" alt="AliExpress logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-ethical">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:handshake-outline"></i>
                            </span>
                            <span class="partner-tag-text">Ethical</span>
                        </div>
                    </div>
                </article>

                <!-- Amazon -->
                <article class="partner-card">
                    <img src="./assets/images/amazon.svg" alt="Amazon logo" draggable="false">
                    <div class="partner-tags mt-[3px]">
                        <div class="partner-tag tag-green">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:recycle-variant"></i>
                            </span>
                            <span class="partner-tag-text">Green</span>
                        </div>
                        <div class="partner-tag tag-local">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="pepicons-pencil:map"></i>
                            </span>
                            <span class="partner-tag-text">Local</span>
                        </div>
                    </div>
                </article>

                <!-- DHL -->
                <article class="partner-card">
                    <img src="./assets/images/dhl.svg" alt="DHL logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-logistics">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:truck-outline"></i>
                            </span>
                            <span class="partner-tag-text">Green Logistics</span>
                        </div>
                    </div>
                </article>

                <!-- Etsy -->
                <article class="partner-card">
                    <img src="./assets/images/etsy.svg" alt="Etsy logo" draggable="false">
                    <div class="partner-tags mt-[3px]">
                        <div class="partner-tag tag-fair">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:scale-balance"></i>
                            </span>
                            <span class="partner-tag-text">Fair Trade</span>
                        </div>
                        <div class="partner-tag tag-sustainable">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:leaf-circle-outline"></i>
                            </span>
                            <span class="partner-tag-text">Sustainable</span>
                        </div>
                    </div>
                </article>

                <!-- Patagonia -->
                <article class="partner-card">
                    <img src="./assets/images/patagonia.svg" alt="Patagonia logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-sustainable">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:pine-tree"></i>
                            </span>
                            <span class="partner-tag-text">Eco Leader</span>
                        </div>
                    </div>
                </article>

                <!-- duplicated list (needed for infinite loop) -->

                <!-- AliExpress -->
                <article class="partner-card">
                    <img src="./assets/images/aliexpress.svg" alt="AliExpress logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-ethical">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:handshake-outline"></i>
                            </span>
                            <span class="partner-tag-text">Ethical</span>
                        </div>
                    </div>
                </article>

                <!-- Amazon -->
                <article class="partner-card">
                    <img src="./assets/images/amazon.svg" alt="Amazon logo" draggable="false">
                    <div class="partner-tags mt-[3px]">
                        <div class="partner-tag tag-green">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:recycle-variant"></i>
                            </span>
                            <span class="partner-tag-text">Green</span>
                        </div>
                        <div class="partner-tag tag-local">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="pepicons-pencil:map"></i>
                            </span>
                            <span class="partner-tag-text">Local</span>
                        </div>
                    </div>
                </article>

                <!-- DHL -->
                <article class="partner-card">
                    <img src="./assets/images/dhl.svg" alt="DHL logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-logistics">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:truck-outline"></i>
                            </span>
                            <span class="partner-tag-text">Green Logistics</span>
                        </div>
                    </div>
                </article>

                <!-- Etsy -->
                <article class="partner-card">
                    <img src="./assets/images/etsy.svg" alt="Etsy logo" draggable="false">
                    <div class="partner-tags mt-[3px]">
                        <div class="partner-tag tag-fair">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:scale-balance"></i>
                            </span>
                            <span class="partner-tag-text">Fair Trade</span>
                        </div>
                        <div class="partner-tag tag-sustainable">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:leaf-circle-outline"></i>
                            </span>
                            <span class="partner-tag-text">Sustainable</span>
                        </div>
                    </div>
                </article>

                <!-- Patagonia -->
                <article class="partner-card">
                    <img src="./assets/images/patagonia.svg" alt="Patagonia logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-sustainable">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:pine-tree"></i>
                            </span>
                            <span class="partner-tag-text">Eco Leader</span>
                        </div>
                    </div>
                </article>

                <!-- duplicated list (needed for infinite loop) -->
            </div>

            <!-- Marquee Right -->
            <div class="partners-marquee marquee-right">
                <!-- PNGegg -->
                <article class="partner-card">
                    <img src="./assets/images/pngegg.png" alt="PNGegg logo" draggable="false">
                    <div class="partner-tags mt-[10px]">
                        <div class="partner-tag tag-creative">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:palette-outline"></i>
                            </span>
                            <span class="partner-tag-text">Creative Assets</span>
                        </div>
                    </div>
                </article>

                <!-- Shopify -->
                <article class="partner-card">
                    <img src="./assets/images/shopify.svg" alt="Shopify logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-platform">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:storefront-outline"></i>
                            </span>
                            <span class="partner-tag-text">Commerce Platform</span>
                        </div>
                    </div>
                </article>

                <!-- TOMS -->
                <article class="partner-card">
                    <img src="./assets/images/toms.svg" alt="TOMS logo" draggable="false">
                    <div class="partner-tags mt-[5px]">
                        <div class="partner-tag tag-social">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:account-group-outline"></i>
                            </span>
                            <span class="partner-tag-text">Social Impact</span>
                        </div>
                    </div>
                </article>

                <!-- UNDP -->
                <article class="partner-card">
                    <img src="./assets/images/undp.svg" alt="UNDP logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-global mt-[12px]">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:earth"></i>
                            </span>
                            <span class="partner-tag-text">Global Partner</span>
                        </div>
                    </div>
                </article>

                <!-- Walmart -->
                <article class="partner-card">
                    <img src="./assets/images/walmart.svg" alt="Walmart logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-retail">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:shopping-outline"></i>
                            </span>
                            <span class="partner-tag-text">Retail Partner</span>
                        </div>
                    </div>
                </article>

                <!-- duplicated list (needed for infinite loop) -->

                <!-- PNGegg -->
                <article class="partner-card">
                    <img src="./assets/images/pngegg.png" alt="PNGegg logo" draggable="false">
                    <div class="partner-tags mt-[10px]">
                        <div class="partner-tag tag-creative">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:palette-outline"></i>
                            </span>
                            <span class="partner-tag-text">Creative Assets</span>
                        </div>
                    </div>
                </article>

                <!-- Shopify -->
                <article class="partner-card">
                    <img src="./assets/images/shopify.svg" alt="Shopify logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-platform">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:storefront-outline"></i>
                            </span>
                            <span class="partner-tag-text">Commerce Platform</span>
                        </div>
                    </div>
                </article>

                <!-- TOMS -->
                <article class="partner-card">
                    <img src="./assets/images/toms.svg" alt="TOMS logo" draggable="false">
                    <div class="partner-tags mt-[5px]">
                        <div class="partner-tag tag-social">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:account-group-outline"></i>
                            </span>
                            <span class="partner-tag-text">Social Impact</span>
                        </div>
                    </div>
                </article>

                <!-- UNDP -->
                <article class="partner-card">
                    <img src="./assets/images/undp.svg" alt="UNDP logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-global mt-[12px]">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:earth"></i>
                            </span>
                            <span class="partner-tag-text">Global Partner</span>
                        </div>
                    </div>
                </article>

                <!-- Walmart -->
                <article class="partner-card">
                    <img src="./assets/images/walmart.svg" alt="Walmart logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-retail">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:shopping-outline"></i>
                            </span>
                            <span class="partner-tag-text">Retail Partner</span>
                        </div>
                    </div>
                </article>

                <!-- duplicated list (needed for infinite loop) -->

                <!-- PNGegg -->
                <article class="partner-card">
                    <img src="./assets/images/pngegg.png" alt="PNGegg logo" draggable="false">
                    <div class="partner-tags mt-[10px]">
                        <div class="partner-tag tag-creative">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:palette-outline"></i>
                            </span>
                            <span class="partner-tag-text">Creative Assets</span>
                        </div>
                    </div>
                </article>

                <!-- Shopify -->
                <article class="partner-card">
                    <img src="./assets/images/shopify.svg" alt="Shopify logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-platform">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:storefront-outline"></i>
                            </span>
                            <span class="partner-tag-text">Commerce Platform</span>
                        </div>
                    </div>
                </article>

                <!-- TOMS -->
                <article class="partner-card">
                    <img src="./assets/images/toms.svg" alt="TOMS logo" draggable="false">
                    <div class="partner-tags mt-[5px]">
                        <div class="partner-tag tag-social">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:account-group-outline"></i>
                            </span>
                            <span class="partner-tag-text">Social Impact</span>
                        </div>
                    </div>
                </article>

                <!-- UNDP -->
                <article class="partner-card">
                    <img src="./assets/images/undp.svg" alt="UNDP logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-global mt-[12px]">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:earth"></i>
                            </span>
                            <span class="partner-tag-text">Global Partner</span>
                        </div>
                    </div>
                </article>

                <!-- Walmart -->
                <article class="partner-card">
                    <img src="./assets/images/walmart.svg" alt="Walmart logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-retail">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:shopping-outline"></i>
                            </span>
                            <span class="partner-tag-text">Retail Partner</span>
                        </div>
                    </div>
                </article>

                <!-- duplicated list (needed for infinite loop) -->

                <!-- PNGegg -->
                <article class="partner-card">
                    <img src="./assets/images/pngegg.png" alt="PNGegg logo" draggable="false">
                    <div class="partner-tags mt-[10px]">
                        <div class="partner-tag tag-creative">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:palette-outline"></i>
                            </span>
                            <span class="partner-tag-text">Creative Assets</span>
                        </div>
                    </div>
                </article>

                <!-- Shopify -->
                <article class="partner-card">
                    <img src="./assets/images/shopify.svg" alt="Shopify logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-platform">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:storefront-outline"></i>
                            </span>
                            <span class="partner-tag-text">Commerce Platform</span>
                        </div>
                    </div>
                </article>

                <!-- TOMS -->
                <article class="partner-card">
                    <img src="./assets/images/toms.svg" alt="TOMS logo" draggable="false">
                    <div class="partner-tags mt-[5px]">
                        <div class="partner-tag tag-social">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:account-group-outline"></i>
                            </span>
                            <span class="partner-tag-text">Social Impact</span>
                        </div>
                    </div>
                </article>

                <!-- UNDP -->
                <article class="partner-card">
                    <img src="./assets/images/undp.svg" alt="UNDP logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-global mt-[12px]">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:earth"></i>
                            </span>
                            <span class="partner-tag-text">Global Partner</span>
                        </div>
                    </div>
                </article>

                <!-- Walmart -->
                <article class="partner-card">
                    <img src="./assets/images/walmart.svg" alt="Walmart logo" draggable="false">
                    <div class="partner-tags">
                        <div class="partner-tag tag-retail">
                            <span class="partner-icon">
                                <i class="iconify" data-icon="mdi:shopping-outline"></i>
                            </span>
                            <span class="partner-tag-text">Retail Partner</span>
                        </div>
                    </div>
                </article>

                <!-- duplicated list (needed for infinite loop) -->
            </div>

        </div>
    </section>
    <!-- End of Partners Section -->

    <section class="protection-section">
        <aside class="protection-section__content">
            <h6 class="protection-section__heading">Empowering People, Building Futures</h6>

            <p class="protection-section__para">We create local jobs and provide skills training that help
                individuals build sustainable careers.
                From retail and logistics to digital and customer service roles, our programs empower community
                members with real opportunities for growth.</p>

            <ul class="protection-section__list">
                <li class="protection-section__list-item">
                    Local job opportunities
                </li>
                <li class="protection-section__list-item">
                    Skills training & career development
                </li>
                <li class="protection-section__list-item">
                    Volunteer & community engagement
                </li>
            </ul>

            <div class="protection-section__actions">
                <a href="###" class="btn-basic dark-btn protection-section__btn--primary">
                    <span class="text">Apply for Jobs</span>
                </a>

                <a href="###" class="btn-basic">
                    <span class="text">Join as a Volunteer</span>
                </a>
            </div>
        </aside>

        <figure class="protection-section__image-wrapper">
            <img src="https://images.pexels.com/photos/15189552/pexels-photo-15189552.jpeg"
                alt="Community members participating in skills training workshop" class="protection-section__image"
                draggable="false">
        </figure>
    </section>

    <section class="go-green">
        <div class="protection-section bg-green-100 rounded-md">
            <figure class="protection-section__image-wrapper">
                <img src="https://images.pexels.com/photos/6690884/pexels-photo-6690884.jpeg"
                    alt="Community members participating in skills training workshop" class="protection-section__image"
                    draggable="false">
            </figure>

            <aside class="protection-section__content">
                <h6 class="protection-section__heading">Shopping for a Greener Tomorrow</h6>

                <div class="mt-4 mb-7">
                    <p class="protection-section__para">At Globalgood Ecommerce, we prioritize sustainability and
                        ethical
                        practices in everything we do. From sourcing eco-friendly products to redistributing donated
                        goods,
                        every transaction helps reduce waste, support fair trade, and promote a responsible global
                        economy.
                    </p>
                    <p class="protection-section__para">Your purchases contribute to a greener planet while
                        empowering
                        communities in need.
                    </p>
                </div>

                <div class="protection-section__actions">
                    <a href="###" class="btn-basic">
                        <span class="text">Shop Sustainably</span>
                    </a>
                </div>
            </aside>
        </div>
    </section>

    <section class="impact-section">
        <!-- Left Sticky Content -->
        <div class="impact-content">
            <h2 class="impact-heading">Building Communities, <span class="impact__highlight">One Purchase at a
                    Time</span></h2>
            <p class="impact-tagline">Shop. Give. Grow.</p>
            <p class="impact-description">
                Globalgood Ecommerce connects conscious shoppers with ethical vendors, redistributing essential
                goods to those in need. Hear from people whose lives have been transformed through our marketplace
                and community initiatives.
            </p>
            <div class="impact-cta">
                <a href="###" class="btn-shop btn-basic dark-btn">
                    <span class="text">Shop Now</span>
                </a>
                <a href="###" class="btn-join btn-basic">
                    <span class="text">Join Our Mission</span>
                </a>
            </div>
        </div>

        <!-- Scrolling Testimonials -->
        <aside class="impact-testimonials">

            <div class="testimonial-mobile">
                <div class="swiper testimonial-mobile-swiper testimonial-mobile-wrapper">
                    <div class="swiper-wrapper">
                        <!-- Testimonial 1 -->
                        <div class="swiper-slide">
                            <article class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                </div>
                                <p class="testimonial-quote">
                                    “Thanks to Globalgood, I found a new career in a local store and now support my
                                    family with
                                    pride.”
                                </p>
                                <div class="testimonial-author">
                                    <img src="https://ui-avatars.com/api/?name=Aisha+K&background=000000&color=ffffff&size=64"
                                        alt="Photo of Aisha K." class="author-image" draggable="false">
                                    <div class="author-info">
                                        <h6 class="author-name">Aisha K.</h6>
                                        <p class="author-role">Store Associate</p>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Testimonial 2 -->
                        <div class="swiper-slide">
                            <article class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                </div>
                                <p class="testimonial-quote">
                                    “Buying through Globalgood made me feel like my purchase had purpose—helping
                                    artisans thrive and
                                    communities flourish.”
                                </p>
                                <div class="testimonial-author">
                                    <img src="https://ui-avatars.com/api/?name=David+M&background=000000&color=ffffff&size=64"
                                        alt="Photo of David M." class="author-image" draggable="false">
                                    <div class="author-info">
                                        <h6 class="author-name">David M.</h6>
                                        <p class="author-role">Conscious Shopper</p>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Testimonial 3 -->
                        <div class="swiper-slide">
                            <article class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                </div>
                                <p class="testimonial-quote">
                                    “Our village received essential goods we needed, and I even got trained in
                                    digital skills.
                                    Life-changing!”
                                </p>
                                <div class="testimonial-author">
                                    <img src="https://ui-avatars.com/api/?name=Sita+R&background=000000&color=ffffff&size=64"
                                        alt="Photo of Sita R." class="author-image" draggable="false">
                                    <div class="author-info">
                                        <h6 class="author-name">Sita R.</h6>
                                        <p class="author-role">Program Participant</p>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Testimonial 4 -->
                        <div class="swiper-slide">
                            <article class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                </div>
                                <p class="testimonial-quote">
                                    “Volunteering with Globalgood gave me purpose and real-world experience.
                                    It opened doors I never thought possible.”
                                </p>
                                <div class="testimonial-author">
                                    <img src="https://ui-avatars.com/api/?name=Rahul+S&background=000000&color=ffffff&size=64"
                                        alt="Photo of Rahul S." class="author-image" draggable="false">
                                    <div class="author-info">
                                        <h6 class="author-name">Rahul S.</h6>
                                        <p class="author-role">Volunteer Coordinator</p>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Testimonial 5 -->
                        <div class="swiper-slide">
                            <article class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                </div>
                                <p class="testimonial-quote">
                                    “As a small vendor, Globalgood helped me reach customers while staying true
                                    to ethical and sustainable practices.”
                                </p>
                                <div class="testimonial-author">
                                    <img src="https://ui-avatars.com/api/?name=Maria+L&background=000000&color=ffffff&size=64"
                                        alt="Photo of Maria L." class="author-image" draggable="false">
                                    <div class="author-info">
                                        <h6 class="author-name">Maria L.</h6>
                                        <p class="author-role">Independent Artisan</p>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Testimonial 6 -->
                        <div class="swiper-slide">
                            <article class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                    <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                                </div>
                                <p class="testimonial-quote">
                                    “Globalgood isn’t just a marketplace—it’s a movement.
                                    Every purchase truly supports people and communities.”
                                </p>
                                <div class="testimonial-author">
                                    <img src="https://ui-avatars.com/api/?name=James+T&background=000000&color=ffffff&size=64"
                                        alt="Photo of James T." class="author-image" draggable="false">
                                    <div class="author-info">
                                        <h6 class="author-name">James T.</h6>
                                        <p class="author-role">Community Partner</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <div class="testimonial-nav">
                    <button type="button" class="swiper-button-prev testimonial-nav-prev"><i class="iconify"
                            data-icon="mingcute:arrow-left-line"></i></button>
                    <button type="button" class="swiper-button-next testimonial-nav-next"><i class="iconify"
                            data-icon="mingcute:arrow-left-line"></i></button>
                </div>
            </div>

            <div class="testimonial-desktop">
                <!-- Testimonial 1 -->
                <article class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                    </div>
                    <p class="testimonial-quote">
                        “Thanks to Globalgood, I found a new career in a local store and now support my family with
                        pride.”
                    </p>
                    <div class="testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=Aisha+K&background=000000&color=ffffff&size=64"
                            alt="Photo of Aisha K." class="author-image" draggable="false">
                        <div class="author-info">
                            <h6 class="author-name">Aisha K.</h6>
                            <p class="author-role">Store Associate</p>
                        </div>
                    </div>
                </article>

                <!-- Testimonial 2 -->
                <article class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                    </div>
                    <p class="testimonial-quote">
                        “Buying through Globalgood made me feel like my purchase had purpose—helping
                        artisans thrive and
                        communities flourish.”
                    </p>
                    <div class="testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=David+M&background=000000&color=ffffff&size=64"
                            alt="Photo of David M." class="author-image" draggable="false">
                        <div class="author-info">
                            <h6 class="author-name">David M.</h6>
                            <p class="author-role">Conscious Shopper</p>
                        </div>
                    </div>
                </article>

                <!-- Testimonial 3 -->
                <article class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                    </div>
                    <p class="testimonial-quote">
                        “Our village received essential goods we needed, and I even got trained in
                        digital skills.
                        Life-changing!”
                    </p>
                    <div class="testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=Sita+R&background=000000&color=ffffff&size=64"
                            alt="Photo of Sita R." class="author-image" draggable="false">
                        <div class="author-info">
                            <h6 class="author-name">Sita R.</h6>
                            <p class="author-role">Program Participant</p>
                        </div>
                    </div>
                </article>

                <!-- Testimonial 4 -->
                <article class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                    </div>
                    <p class="testimonial-quote">
                        “Volunteering with Globalgood gave me purpose and real-world experience.
                        It opened doors I never thought possible.”
                    </p>
                    <div class="testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=Rahul+S&background=000000&color=ffffff&size=64"
                            alt="Photo of Rahul S." class="author-image" draggable="false">
                        <div class="author-info">
                            <h6 class="author-name">Rahul S.</h6>
                            <p class="author-role">Volunteer Coordinator</p>
                        </div>
                    </div>
                </article>

                <!-- Testimonial 5 -->
                <article class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                    </div>
                    <p class="testimonial-quote">
                        “As a small vendor, Globalgood helped me reach customers while staying true
                        to ethical and sustainable practices.”
                    </p>
                    <div class="testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=Maria+L&background=000000&color=ffffff&size=64"
                            alt="Photo of Maria L." class="author-image" draggable="false">
                        <div class="author-info">
                            <h6 class="author-name">Maria L.</h6>
                            <p class="author-role">Independent Artisan</p>
                        </div>
                    </div>
                </article>

                <!-- Testimonial 6 -->
                <article class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                        <i class="iconify" data-icon="material-symbols:star-rounded"></i>
                    </div>
                    <p class="testimonial-quote">
                        “Globalgood isn’t just a marketplace—it’s a movement.
                        Every purchase truly supports people and communities.”
                    </p>
                    <div class="testimonial-author">
                        <img src="https://ui-avatars.com/api/?name=James+T&background=000000&color=ffffff&size=64"
                            alt="Photo of James T." class="author-image" draggable="false">
                        <div class="author-info">
                            <h6 class="author-name">James T.</h6>
                            <p class="author-role">Community Partner</p>
                        </div>
                    </div>
                </article>
            </div>
        </aside>
    </section>

    <section class="movement-signup">
        <div class="movement-signup__content">
            <h3 class="movement-signup__headline">
                Be Part of the <span class="movement-signup__highlight">Globalgood Movement</span>
            </h3>

            <div class="movement-signup__form-area">
                <div class="movement-signup__form">
                    <div class="movement-signup__field floating-item">
                        <input type="email" id="newsletter-email" class="movement-signup__input floating-input"
                            placeholder=" ">

                        <label for="newsletter-email" class="movement-signup__label floating-label">
                            Your Email Address
                        </label>
                    </div>

                    <button type="button" class="movement-signup__submit btn-basic dark-btn">
                        <span class="movement-signup__submit-text text">Subscribe</span>
                    </button>
                </div>

                <p class="movement-signup__disclaimer">
                    Get updates on ethical vendors, community impact, and platform news.
                    No spam. Unsubscribe anytime.
                </p>
            </div>
        </div>

        <figure class="movement-signup__visual">
            <img class="movement-signup__image" src="https://images.pexels.com/photos/12663094/pexels-photo-12663094.jpeg"
                alt="Community-driven marketplace impact" draggable="false">
        </figure>
    </section>
@endsection

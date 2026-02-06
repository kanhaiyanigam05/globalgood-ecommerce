@extends('main.layouts.app')

@section('content')
    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <img class="hero-banner__image" src="https://images.pexels.com/photos/3738387/pexels-photo-3738387.jpeg" alt=""
            draggable="false">
        <div class="hero-banner__content">
            <h1 class="hero-banner__title">Shop With
                <span class="hero-banner__highlight-2">Purpose.</span>
            </h1>
            <p class="hero-banner__subtitle">Every purchase you make supports local communities, creates jobs, and
                funds sustainable programs worldwide. Explore ethically sourced, donated, and eco-friendly products
                while making a real difference.</p>
            <div class="hero-banner__actions">
                <a class="hero-banner__btn btn-basic dark-btn text-highlight-group" href="###">
                    <span class="text">Browse Products</span>
                </a>
                <a class="hero-banner__btn btn-basic" href="###">
                    <span class="text">Donate Goods</span>
                </a>
            </div>

            <div class="core-values">
                <div class="core-value ethical-glow">
                    <span class="icon-frame ethical-symbol bg-green-600">
                        <i class="iconify" data-icon="mdi:hand-heart-outline"></i>
                    </span>
                    <p class="value-label">Ethical</p>
                </div>
                <div class="core-value green-pledge">
                    <span class="icon-frame sustainable-symbol bg-amber-600">
                        <i class="iconify" data-icon="mdi:leaf-circle-outline"></i>
                    </span>
                    <p class="value-label">Sustainable</p>
                </div>
                <div class="core-value community-link">
                    <span class="icon-frame global-symbol bg-purple-600">
                        <i class="iconify" data-icon="mdi:earth"></i>
                    </span>
                    <p class="value-label">Community-Powered</p>
                </div>
            </div>

        </div>
    </section>
    <!-- End of Hero Banner Section -->

    <!-- Category Section -->
    <section class="category-section">
        <h2 class="category-section__title">Shop by Category</h2>
        <p class="category-section__description">
            Explore products that make a difference—support local communities, sustainable businesses, and ethical
            practices with every purchase.
        </p>

        <div class="category-grid">
            <a href="###" class="category-card category-card--clothing">
                <div class="category-card__image-wrapper">
                    <img src="https://images.pexels.com/photos/7679868/pexels-photo-7679868.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
                <div class="category-card__content">
                    <h6 class="category-card__title">Clothing & Accessories</h6>
                    <p class="category-card__description">Fair-trade and ethically made apparel, shoes, and
                        accessories for all ages.</p>
                </div>
            </a>

            <a href="###" class="category-card category-card--home">
                <div class="category-card__image-wrapper">
                    <img src="https://images.pexels.com/photos/6187606/pexels-photo-6187606.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
                <div class="category-card__content">
                    <h6 class="category-card__title">Home & Living</h6>
                    <p class="category-card__description">Eco-friendly and ethically sourced home goods to make your
                        space sustainable.</p>
                </div>
            </a>

            <a href="###" class="category-card category-card--electronics">
                <div class="category-card__image-wrapper">
                    <img src="https://images.pexels.com/photos/3981749/pexels-photo-3981749.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
                <div class="category-card__content">
                    <h6 class="category-card__title">Electronics & Gadgets</h6>
                    <p class="category-card__description">New and refurbished tech products that empower both buyers
                        and communities.</p>
                </div>
            </a>

            <a href="###" class="category-card category-card--essentials">
                <div class="category-card__image-wrapper">
                    <img src="https://images.pexels.com/photos/3735170/pexels-photo-3735170.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
                <div class="category-card__content">
                    <h6 class="category-card__title">Essentials & Relief Goods</h6>
                    <p class="category-card__description">Everyday necessities and donated items supporting families
                        and communities in need.</p>
                </div>
            </a>

            <a href="###" class="category-card category-card--eco">
                <div class="category-card__image-wrapper">
                    <img src="https://images.pexels.com/photos/7262729/pexels-photo-7262729.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
                <div class="category-card__content">
                    <h6 class="category-card__title">Eco-Friendly Products</h6>
                    <p class="category-card__description">Sustainable products that reduce waste and help protect
                        the planet.</p>
                </div>
            </a>

            <a href="###" class="category-card category-card--donated">
                <div class="category-card__image-wrapper">
                    <img src="https://images.pexels.com/photos/30236995/pexels-photo-30236995.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
                <div class="category-card__content">
                    <h6 class="category-card__title">Donated Goods</h6>
                    <p class="category-card__description">High-quality secondhand items given a second
                        life—supporting community</p>
                </div>
            </a>

            <a href="###" class="category-card category-card--vendors">
                <div class="category-card__image-wrapper">
                    <img src="https://images.pexels.com/photos/7667437/pexels-photo-7667437.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
                <div class="category-card__content">
                    <h6 class="category-card__title">Vendor Stores</h6>
                    <p class="category-card__description">Discover products directly from our vetted ethical vendors
                        worldwide.</p>
                </div>
            </a>

            <div class="category-card category-card--all">
                <div class="category-card__inner">
                    <div class="category-card__link-wrapper">
                        <a href="###" class="category-card__link">View All Categories</a>
                    </div>
                    <img src="https://images.pexels.com/photos/5531747/pexels-photo-5531747.jpeg" alt=""
                        class="category-card__image" draggable="false">
                </div>
            </div>
        </div>
    </section>
    <!-- End of Category Section -->

    <!-- Catalog Section -->
    <section class="catalog-section">

        <div class="catalog-toolbar">
            <button class="catalog-filter-toggle" data-model-id="filter-Offcanvas" onclick="openOffcanvas(this);">
                <i class="iconify" data-icon="lets-icons:filter"></i>
                <span>Refine</span>
            </button>

            <!-- <p class="catalog-product-count"><span>182</span> Products</p> -->

            <select class="dropdown-select product-select">
                <option value="">Sort by</option>
                <option value="most-impactful">Most Impactful</option>
                <option value="price-low-high">Price: Low to High</option>
                <option value="price-high-low">Price: High to Low</option>
                <option value="latest">Newest Arrivals</option>
            </select>

        </div>

        <div class="catalog-toolbar___main">
            <!-- FILTER SIDEBAR -->
            <aside id="filter-Offcanvas" class="catalog-filter-panel offcanvas-wrapper">
                <div class="catalog-filter-container offcanvas-inner hide-left show-left">
                    <!-- Header -->
                    <div class="filter-panel-header">
                        <h6 class="filter-panel-title">Filter</h6>
                        <button class="filter-panel-close close-btn" type="button">
                            <i class="iconify" data-icon="eva:close-fill"></i>
                        </button>
                    </div>

                    <!-- Availability -->
                    <div class="custom-accordion-wrapper filter-group filter-group-availability" data-initially-open
                        data-keep-open>
                        <button class="filter-group-toggle accordion-btn" type="button">
                            <span>Availability</span>
                            <i class="iconify accordion-icon" data-icon="line-md:chevron-down"></i>
                        </button>

                        <div class="filter-group-content accordion-body">
                            <ul class="filter-options-list inner-body">
                                <li class="filter-option">
                                    <input type="checkbox" id="in-stock">
                                    <label for="in-stock">
                                        <span class="checkbox-ui"></span>
                                        <span class="filter-label">In Stock</span>
                                    </label>
                                </li>
                                <li class="filter-option">
                                    <input type="checkbox" id="out-of-stock">
                                    <label for="out-of-stock">
                                        <span class="checkbox-ui"></span>
                                        <span class="filter-label">Out of Stock</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="custom-accordion-wrapper filter-group filter-group-price" data-initially-open
                        data-keep-open>
                        <button class="filter-group-toggle accordion-btn" type="button">
                            <span>Price</span>
                            <i class="iconify accordion-icon" data-icon="line-md:chevron-down"></i>
                        </button>

                        <div class="filter-group-content accordion-body">
                            <div class="price-filter inner-body">
                                <div class="range-slider">
                                    <div class="price-labels">
                                        <p class="lowerband">$ <span>0.00</span></p>
                                        <p class="higherband">$ <span>82,790.00</span></p>
                                    </div>
                                    <tc-range-slider class="slider" min="10000" max="82790" step="500"
                                        slider-bg-fill="black" value1="32000" value2="65000" color="black">
                                    </tc-range-slider>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Type -->
                    <div class="custom-accordion-wrapper filter-group filter-group-category" data-initially-open
                        data-keep-open>
                        <button class="filter-group-toggle accordion-btn" type="button">
                            <span>Product Type</span>
                            <i class="iconify accordion-icon" data-icon="line-md:chevron-down"></i>
                        </button>

                        <div class="filter-group-content accordion-body">
                            <ul class="filter-options-list inner-body">
                                <li class="filter-option"><input type="checkbox" id="DonatedClothing"><label
                                        for="DonatedClothing"><span class="checkbox-ui"></span>Donated
                                        Clothing</label></li>
                                <li class="filter-option"><input type="checkbox" id="RefurbishedGoods"><label
                                        for="RefurbishedGoods"><span class="checkbox-ui"></span>Refurbished
                                        Goods</label></li>
                                <li class="filter-option"><input type="checkbox" id="PreLovedItems"><label
                                        for="PreLovedItems"><span class="checkbox-ui"></span>Pre-Loved Items</label>
                                </li>
                                <li class="filter-option"><input type="checkbox" id="MensWear"><label
                                        for="MensWear"><span class="checkbox-ui"></span>Men’s Wear</label></li>
                                <li class="filter-option"><input type="checkbox" id="WomensWear"><label
                                        for="WomensWear"><span class="checkbox-ui"></span>Women’s Wear</label></li>
                                <li class="filter-option"><input type="checkbox" id="KidsWear"><label
                                        for="KidsWear"><span class="checkbox-ui"></span>Kids’ Wear</label></li>
                                <li class="filter-option"><input type="checkbox" id="EthnicWear"><label
                                        for="EthnicWear"><span class="checkbox-ui"></span>Ethnic Wear</label></li>
                                <li class="filter-option"><input type="checkbox" id="SustainableFashion"><label
                                        for="SustainableFashion"><span class="checkbox-ui"></span>Sustainable
                                        Fashion</label></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Color -->
                    <div class="custom-accordion-wrapper filter-group filter-group-color" data-initially-open
                        data-keep-open>
                        <button class="filter-group-toggle accordion-btn" type="button">
                            <span>Color</span>
                            <i class="iconify accordion-icon" data-icon="line-md:chevron-down"></i>
                        </button>

                        <div class="filter-group-content accordion-body">
                            <ul class="color-filter-list inner-body">

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Ivory">
                                    <label for="Ivory">
                                        <span class="color-swatch" style="background:#fffff0;"></span>
                                        Ivory
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Charcoal">
                                    <label for="Charcoal">
                                        <span class="color-swatch" style="background:#36454f;"></span>
                                        Charcoal
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Olive-Green">
                                    <label for="Olive-Green">
                                        <span class="color-swatch" style="background:#6b8e23;"></span>
                                        Olive Green
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Terracotta">
                                    <label for="Terracotta">
                                        <span class="color-swatch" style="background:#e2725b;"></span>
                                        Terracotta
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Sage">
                                    <label for="Sage">
                                        <span class="color-swatch" style="background:#9caf88;"></span>
                                        Sage
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Dusty-Rose">
                                    <label for="Dusty-Rose">
                                        <span class="color-swatch" style="background:#c08081;"></span>
                                        Dusty Rose
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Teal">
                                    <label for="Teal">
                                        <span class="color-swatch" style="background:#008080;"></span>
                                        Teal
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Mustard">
                                    <label for="Mustard">
                                        <span class="color-swatch" style="background:#ffdb58;"></span>
                                        Mustard
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Indigo">
                                    <label for="Indigo">
                                        <span class="color-swatch" style="background:#4b0082;"></span>
                                        Indigo
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Cocoa">
                                    <label for="Cocoa">
                                        <span class="color-swatch" style="background:#7b3f00;"></span>
                                        Cocoa
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Slate-Blue">
                                    <label for="Slate-Blue">
                                        <span class="color-swatch" style="background:#6a5acd;"></span>
                                        Slate Blue
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>

                                <li class="color-filter-option">
                                    <input type="checkbox" id="Forest-Green">
                                    <label for="Forest-Green">
                                        <span class="color-swatch" style="background:#228b22;"></span>
                                        Forest Green
                                        <span class="checkbox-ui"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Size -->
                    <div class="custom-accordion-wrapper filter-group filter-group-size" data-initially-open
                        data-keep-open>
                        <button class="filter-group-toggle accordion-btn" type="button">
                            <span>Size</span>
                            <i class="iconify accordion-icon" data-icon="line-md:chevron-down"></i>
                        </button>

                        <div class="filter-group-content accordion-body">
                            <ul class="size-filter-list inner-body">
                                <li class="size-filter-option"><input type="checkbox" id="XS"><label
                                        for="XS"><span class="checkbox-ui"></span>XS</label></li>
                                <li class="size-filter-option"><input type="checkbox" id="S"><label
                                        for="S"><span class="checkbox-ui"></span>S</label></li>
                                <li class="size-filter-option"><input type="checkbox" id="M"><label
                                        for="M"><span class="checkbox-ui"></span>M</label></li>
                                <li class="size-filter-option"><input type="checkbox" id="L"><label
                                        for="L"><span class="checkbox-ui"></span>L</label></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="custom-accordion-wrapper filter-group filter-group-sort" data-initially-open
                        data-keep-open>
                        <button class="filter-group-toggle accordion-btn" type="button">
                            <span>Sort By</span>
                            <i class="iconify accordion-icon" data-icon="line-md:chevron-down"></i>
                        </button>

                        <div class="filter-group-content accordion-body">
                            <ul class="sort-options-list inner-body">
                                <li class="sort-option">
                                    <input type="radio" name="sort" id="sort-bestseller">
                                    <label for="sort-bestseller"><span class="radio-ui"></span>Bestseller</label>
                                </li>
                                <li class="sort-option">
                                    <input type="radio" name="sort" id="sort-latest">
                                    <label for="sort-latest"><span class="radio-ui"></span>Latest</label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Apply Filters -->
                    <div class="filter-group-footer">
                        <button type="button" class="btn-basic catalog-filter__clear close-btn"><span
                                class="text">Clear All</span></button>
                        <button type="button" class="btn-basic dark-btn catalog-filter__apply close-btn"><span
                                class="text">Apply Filters</span></button>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <div class="catalog-main">
                <!-- Toolbar -->

                <!-- Product Grid -->
                <div class="catalog-product-grid">
                    <!-- Product cards here -->
                    @php
                        $newArrivals = [
                            [
                                'front' => 'https://images.pexels.com/photos/6311603/pexels-photo-6311603.jpeg',
                                'back' => 'https://images.pexels.com/photos/6311605/pexels-photo-6311605.jpeg',
                                'name' => 'Handwoven Ethical Summer Dress',
                                'price' => '14,499',
                                'bestseller' => true,
                                'soldOut' => false,
                                'discountPercent' => 15,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/5704841/pexels-photo-5704841.jpeg',
                                'back' => 'https://images.pexels.com/photos/5704843/pexels-photo-5704843.jpeg',
                                'name' => 'Locally Crafted Linen Shirt',
                                'price' => '11,250',
                                'bestseller' => false,
                                'soldOut' => false,
                                'discountPercent' => 0,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/7691164/pexels-photo-7691164.jpeg',
                                'back' => 'https://images.pexels.com/photos/7691166/pexels-photo-7691166.jpeg',
                                'name' => 'Sustainable Tailored Trousers',
                                'price' => '21,999',
                                'bestseller' => true,
                                'soldOut' => false,
                                'discountPercent' => 20,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/7691104/pexels-photo-7691104.jpeg',
                                'back' => 'https://images.pexels.com/photos/7691106/pexels-photo-7691106.jpeg',
                                'name' => 'Community-Made Casual Jacket',
                                'price' => '29,750',
                                'bestseller' => false,
                                'soldOut' => true,
                                'discountPercent' => 0,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/5704832/pexels-photo-5704832.jpeg',
                                'back' => 'https://images.pexels.com/photos/5704834/pexels-photo-5704834.jpeg',
                                'name' => 'Organic Cotton Relaxed Tee',
                                'price' => '7,999',
                                'bestseller' => true,
                                'soldOut' => false,
                                'discountPercent' => 10,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/6311611/pexels-photo-6311611.jpeg',
                                'back' => 'https://images.pexels.com/photos/6311613/pexels-photo-6311613.jpeg',
                                'name' => 'Ethical Wide-Leg Lounge Pants',
                                'price' => '18,650',
                                'bestseller' => false,
                                'soldOut' => false,
                                'discountPercent' => 5,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/7691201/pexels-photo-7691201.jpeg',
                                'back' => 'https://images.pexels.com/photos/7691203/pexels-photo-7691203.jpeg',
                                'name' => 'Fair-Trade Sleeveless Top',
                                'price' => '9,899',
                                'bestseller' => false,
                                'soldOut' => false,
                                'discountPercent' => 0,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/5704850/pexels-photo-5704850.jpeg',
                                'back' => 'https://images.pexels.com/photos/5704852/pexels-photo-5704852.jpeg',
                                'name' => 'Upcycled Denim Everyday Wear',
                                'price' => '22,499',
                                'bestseller' => true,
                                'soldOut' => false,
                                'discountPercent' => 25,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/6311622/pexels-photo-6311622.jpeg',
                                'back' => 'https://images.pexels.com/photos/6311624/pexels-photo-6311624.jpeg',
                                'name' => 'Minimalist Ethical Office Shirt',
                                'price' => '13,999',
                                'bestseller' => false,
                                'soldOut' => true,
                                'discountPercent' => 0,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/7691180/pexels-photo-7691180.jpeg',
                                'back' => 'https://images.pexels.com/photos/7691182/pexels-photo-7691182.jpeg',
                                'name' => 'Artisan-Made Comfort Wear Pants',
                                'price' => '26,300',
                                'bestseller' => true,
                                'soldOut' => false,
                                'discountPercent' => 30,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/6311596/pexels-photo-6311596.jpeg',
                                'back' => 'https://images.pexels.com/photos/6311598/pexels-photo-6311598.jpeg',
                                'name' => 'Handcrafted Community Wear Tunic',
                                'price' => '16,850',
                                'bestseller' => false,
                                'soldOut' => false,
                                'discountPercent' => 10,
                            ],
                            [
                                'front' => 'https://images.pexels.com/photos/7691225/pexels-photo-7691225.jpeg',
                                'back' => 'https://images.pexels.com/photos/7691227/pexels-photo-7691227.jpeg',
                                'name' => 'Ethical Everyday Comfort Dress',
                                'price' => '20,499',
                                'bestseller' => true,
                                'soldOut' => false,
                                'discountPercent' => 0,
                            ],
                        ];
                    @endphp
                    @foreach ($newArrivals as $item)
                        <x-product-item :item="$item" />
                    @endforeach
                </div>

            </div>
        </div>
    </section>
    <!-- End of Catalog Section -->

    <!-- Impact Products Section -->
    <section class="impact-products">
        <div class="impact-products__intro">
            <h2 class="impact-products__title">
                Featured Products <span class="impact-products__highlight">Making an Impact</span>
            </h2>
            <p class="impact-products__description">
                Discover ethical, sustainable, and community-powered products. Every item you buy helps create jobs,
                reduce waste, and support local communities.
            </p>
        </div>

        <div class="impact-products__grid">
            <!-- Cards go here -->
            @php
                $impactProducts = [
                    [
                        'front' => 'https://images.pexels.com/photos/6311603/pexels-photo-6311603.jpeg',
                        'back' => 'https://images.pexels.com/photos/6311605/pexels-photo-6311605.jpeg',
                        'name' => 'Handwoven Ethical Summer Dress',
                        'description' =>
                            'Lightweight handwoven dress crafted by artisans using breathable, ethically sourced fabrics.',
                        'price' => '14,499',
                        'soldOut' => false,
                        'discountPercent' => 15,
                        'condition_badge' => 'new',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/5704841/pexels-photo-5704841.jpeg',
                        'back' => 'https://images.pexels.com/photos/5704843/pexels-photo-5704843.jpeg',
                        'name' => 'Locally Crafted Linen Shirt',
                        'description' => 'Naturally airy linen shirt handmade by local makers for everyday comfort.',
                        'price' => '11,250',
                        'soldOut' => false,
                        'discountPercent' => 0,
                        'condition_badge' => 'donated',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/7691164/pexels-photo-7691164.jpeg',
                        'back' => 'https://images.pexels.com/photos/7691166/pexels-photo-7691166.jpeg',
                        'name' => 'Sustainable Tailored Trousers',
                        'description' =>
                            'Modern tailored trousers designed with sustainable materials and long-lasting construction.',
                        'price' => '21,999',
                        'soldOut' => false,
                        'discountPercent' => 20,
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/7691104/pexels-photo-7691104.jpeg',
                        'back' => 'https://images.pexels.com/photos/7691106/pexels-photo-7691106.jpeg',
                        'name' => 'Community-Made Casual Jacket',
                        'description' =>
                            'Comfortable casual jacket refurbished and finished by skilled community artisans.',
                        'price' => '29,750',
                        'soldOut' => true,
                        'discountPercent' => 0,
                        'condition_badge' => 'refurbished',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/5704832/pexels-photo-5704832.jpeg',
                        'back' => 'https://images.pexels.com/photos/5704834/pexels-photo-5704834.jpeg',
                        'name' => 'Organic Cotton Relaxed Tee',
                        'description' =>
                            'Soft organic cotton t-shirt made without harmful chemicals for everyday wear.',
                        'price' => '7,999',
                        'soldOut' => false,
                        'discountPercent' => 10,
                        'condition_badge' => 'new',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/6311611/pexels-photo-6311611.jpeg',
                        'back' => 'https://images.pexels.com/photos/6311613/pexels-photo-6311613.jpeg',
                        'name' => 'Ethical Wide-Leg Lounge Pants',
                        'description' =>
                            'Relaxed-fit lounge pants ethically produced for all-day comfort at home or outside.',
                        'price' => '18,650',
                        'soldOut' => false,
                        'discountPercent' => 5,
                        'condition_badge' => 'refurbished',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/7691201/pexels-photo-7691201.jpeg',
                        'back' => 'https://images.pexels.com/photos/7691203/pexels-photo-7691203.jpeg',
                        'name' => 'Fair-Trade Sleeveless Top',
                        'description' =>
                            'Breathable sleeveless top made under fair-trade standards by ethical cooperatives.',
                        'price' => '9,899',
                        'soldOut' => false,
                        'discountPercent' => 0,
                        'condition_badge' => 'donated',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/5704850/pexels-photo-5704850.jpeg',
                        'back' => 'https://images.pexels.com/photos/5704852/pexels-photo-5704852.jpeg',
                        'name' => 'Upcycled Denim Everyday Wear',
                        'description' => 'Stylish everyday denim crafted from upcycled materials to reduce waste.',
                        'price' => '22,499',
                        'soldOut' => false,
                        'discountPercent' => 25,
                        'condition_badge' => 'refurbished',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/6311622/pexels-photo-6311622.jpeg',
                        'back' => 'https://images.pexels.com/photos/6311624/pexels-photo-6311624.jpeg',
                        'name' => 'Minimalist Ethical Office Shirt',
                        'description' =>
                            'Clean, minimalist office shirt responsibly made for professional everyday wear.',
                        'price' => '13,999',
                        'soldOut' => true,
                        'discountPercent' => 0,
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/7691180/pexels-photo-7691180.jpeg',
                        'back' => 'https://images.pexels.com/photos/7691182/pexels-photo-7691182.jpeg',
                        'name' => 'Artisan-Made Comfort Wear Pants',
                        'description' => 'Comfort-focused pants handcrafted by artisans using durable ethical fabrics.',
                        'price' => '26,300',
                        'soldOut' => false,
                        'discountPercent' => 30,
                        'condition_badge' => 'donated',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/6311596/pexels-photo-6311596.jpeg',
                        'back' => 'https://images.pexels.com/photos/6311598/pexels-photo-6311598.jpeg',
                        'name' => 'Handcrafted Community Wear Tunic',
                        'description' => 'Traditional-inspired tunic handmade to support local community artisans.',
                        'price' => '16,850',
                        'soldOut' => false,
                        'discountPercent' => 10,
                        'condition_badge' => 'new',
                    ],
                    [
                        'front' => 'https://images.pexels.com/photos/7691225/pexels-photo-7691225.jpeg',
                        'back' => 'https://images.pexels.com/photos/7691227/pexels-photo-7691227.jpeg',
                        'name' => 'Ethical Everyday Comfort Dress',
                        'description' => 'Easy-to-wear everyday dress made ethically with comfort-first design.',
                        'price' => '20,499',
                        'soldOut' => false,
                        'discountPercent' => 0,
                        'condition_badge' => 'new',
                    ],
                ];
            @endphp
            @foreach ($impactProducts as $item)
                <x-product-item :item="$item" />
            @endforeach
        </div>
    </section>
    <!-- End of Impact Products Section -->

    <!-- Partners Section -->
    <section class="partners-section">
        <div class="partners-header">
            <h2 class="partners-title">Featured Vendors Making a Difference</h2>
            <p class="partners-description">
                Meet community-led vendors whose products support local jobs, sustainability, and economic
                empowerment.
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

    <!-- Impact Products Section -->
    <section class="protection-section">
        <aside class="protection-section__content">
            <h6 class="protection-section__heading">Your Purchase Creates Real Impact</h6>

            <p class="protection-section__para">Every item you buy helps create local jobs, fund skills training,
                reduce waste, and support communities in need. Shopping with Globalgood Ecommerce means contributing
                to a fairer, more sustainable world.</p>

            <ul class="protection-section__list">
                <li class="protection-section__list-item">
                    Supports local employment & small vendors
                </li>
                <li class="protection-section__list-item">
                    Funds skills training and career growth
                </li>
                <li class="protection-section__list-item">
                    Reduces waste through reuse and ethical sourcing
                </li>
            </ul>

            <div class="protection-section__actions">
                <a href="###" class="btn-basic dark-btn protection-section__btn--primary">
                    <span class="text">View Impact</span>
                </a>

                <a href="###" class="btn-basic">
                    <span class="text">Impact Stories</span>
                </a>
            </div>
        </aside>

        <figure class="protection-section__image-wrapper">
            <img src="https://images.pexels.com/photos/33059930/pexels-photo-33059930.jpeg"
                alt="Community members participating in skills training workshop" class="protection-section__image"
                draggable="false">
        </figure>
    </section>
    <!-- End of Impact Products Section -->

    <!-- Donated & Refurbished Goods Section -->
    <section class="protection-section refurbished-goods">
        <figure class="protection-section__image-wrapper">
            <img src="./assets/images/0f2e27da-a2d7-4f29-a3a0-9df39845f1ed.png"
                alt="Community members participating in skills training workshop" class="protection-section__image"
                draggable="false">
        </figure>
        <aside class="protection-section__content">
            <h2 class="protection-section__heading">Donated & Refurbished Goods</h2>
            <h6 class="protection-section__sub-heading">Give Quality Items a Second Life</h6>


            <p class="protection-section__para">Discover high-quality donated and refurbished products that help
                reduce waste while supporting communities in need. Every item is carefully inspected and repurposed
                to ensure usability, affordability, and positive impact.</p>

            <p class="protection-section__para">By choosing donated goods, you’re not only saving money—you’re
                helping keep valuable resources out of landfills and extending their life for a greater cause.</p>

            <ul class="protection-section__list">
                <li class="protection-section__list-item">
                    Reduces environmental waste
                </li>
                <li class="protection-section__list-item">
                    Supports underserved communities
                </li>
                <li class="protection-section__list-item">
                    Affordable and impactful shopping
                </li>
            </ul>

            <div class="protection-section__actions">
                <a href="###" class="btn-basic dark-btn protection-section__btn--primary">
                    <span class="text">Explore Donated Goods</span>
                </a>
            </div>
        </aside>
    </section>
    <!-- End of Donated & Refurbished Goods Section -->

    <!-- Shop Confidence Section -->
    <section class="shop-confidence">
        <div class="shop-intro">
            <h2 class="shop-intro__title">Why You Can Shop With Confidence</h2>
            <p class="shop-intro__description">
                Globalgood Ecommerce is built on transparency, sustainability, and community impact. Every purchase,
                donation, or action on our platform supports ethical commerce and measurable change.
            </p>
        </div>

        <div class="shop-features">
            <article class="feature feature--nonprofit">
                <div class="feature__icon-wrapper">
                    <i class="iconify feature__icon" data-icon="mdi:charity"></i>
                </div>
                <h6 class="feature__title">Nonprofit-Backed Marketplace</h6>
                <p class="feature__description">
                    Supported by Globalgood Corporation (501(c)(3)), ensuring proceeds fund real-world community
                    programs.
                </p>
            </article>

            <article class="feature feature--sustainable">
                <div class="feature__icon-wrapper">
                    <i class="iconify feature__icon" data-icon="mdi:leaf"></i>
                </div>
                <h6 class="feature__title">Ethical & Sustainable Sourcing</h6>
                <p class="feature__description">
                    All vendors follow fair-trade practices and prioritize eco-friendly products.
                </p>
            </article>

            <article class="feature feature--secure">
                <div class="feature__icon-wrapper">
                    <i class="iconify feature__icon" data-icon="mdi:lock-check"></i>
                </div>
                <h6 class="feature__title">Secure Payments</h6>
                <p class="feature__description">
                    Shop safely with encrypted transactions and trusted payment gateways.
                </p>
            </article>

            <article class="feature feature--community">
                <div class="feature__icon-wrapper">
                    <i class="iconify feature__icon" data-icon="mdi:account-group"></i>
                </div>
                <h6 class="feature__title">Community-Driven Impact</h6>
                <p class="feature__description">
                    Every purchase contributes to job creation, skills training, and local economic empowerment.
                </p>
            </article>
        </div>

        <a class="shop-mission-link btn-basic dark-btn" href="###">
            <span class="text">Learn More About Our Mission</span>
        </a>
    </section>
    <!-- End of Shop Confidence Section -->

    <section class="action-section">
        <figure class="action-image">
            <img src="https://images.pexels.com/photos/6646864/pexels-photo-6646864.jpeg" alt=""
                draggable="false">
        </figure>

        <div class="action-content">
            <h2 class="action-title">Your Action Creates Change</h2>
            <p class="action-description">
                Every vendor, donation, or volunteer effort contributes to empowering communities worldwide
            </p>

            <div class="action-buttons">
                <a href="###" class="btn-basic action-btn vendor"><span class="text">Become a Vendor</span></a>
                <a href="###" class="btn-basic action-btn donate dark-btn"><span class="text">Donate
                        Goods</span></a>
                <a href="###" class="btn-basic action-btn volunteer"><span class="text">Volunteer With
                        Us</span></a>
            </div>
        </div>
    </section>
@endsection

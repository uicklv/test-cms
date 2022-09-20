<div class="shop-page">

    <?php include _SYSDIR_  . 'modules/shop/views/parts/_cart_button.php'; ?>

    <section class="shop-home-section">
        <div class="cont">
            <?php if ($this->highlights) { ?>
            <div class="swiper mySwiper shop-home-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($this->highlights as $item) { ?>
                    <div class="swiper-slide shop-home-item">
                        <div class="info-block">
                            <h1>
                                <?= $item->title ?>
                            </h1>
                            <p>
                                <?= reFilter($item->preview_content) ?>
                            </p>
                            <div class="btn-wrap">
                                <?php if (Cart::getProduct($item->id)) { ?>
                                    <button class="btn-shop btn-shop--fill" id="btn-buy-<?= $item->id ?>"><span id="btn-buy-text-<?= $item->id ?>">Product in cart</span></button>
                                <?php } else { ?>
                                    <button class="btn-shop btn-shop--fill" id="btn-buy-<?= $item->id ?>" onclick="load('shop/buy_product', 'product_id=<?= $item->id ?>'); return false;"><span id="btn-buy-text-<?= $item->id ?>">Buy</span></button>
                                <?php } ?>
                                <a href="{URL:shop/product/<?= $item->slug ?>}" class="btn-shop btn-shop--line">View More</a>
                            </div>
                        </div>

                        <div class="photo-block">
                            <img src="<?= _SITEDIR_ ?>data/shop/<?= $item->image ?>" alt="">
                        </div>

                    </div>
                    <?php } ?>
                </div>
                <div class="navigation-wrap">
                    <div class="swiper-button-prev">Prev</div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next">Next</div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>

    <section class="shop-search-products">
        <div class="cont">
            <div class="shop-search-products__wrapper">
                <h2>
                    Search Products
                </h2>
                <div class="list-search-block">
                    <?php if ($this->types) { ?>
                    <ul>
                        <?php foreach ($this->types as $type) { ?>
                            <li>
                                <a href="{LINK:shop-search}?type=<?= $type->id ?>">
                                    <?= $type->name ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                    <form action="{LINK:shop-search}" method="GET" class="shop-search-container">
                        <input type="search" name="keywords" placeholder="Search">
                        <button class="icon-shop-search-line" type="submit"></button>
                        <a class="link-show" href="{LINK:shop-search}">Advanced search</a>
                    </form>

                </div>

            </div>
        </div>
    </section>

    <?php /*
    <section class="products-section">
        <div class="cont">
            <div class="products-grid columns-four products-grid--home">
                <div class="title-block">
                    <h2>
                        Popular
                    </h2>

                    <a href="#" class="link-show">Show All</a>
                </div>
                <a href="#" class="products-card">
                    <div class="photo-block">
                        <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-1.jpg" alt="">
                    </div>
                    <div class="info-block">
                        <h4>
                            Gazer 43" Full HD Smart TV
                        </h4>
                        <div class="about-info-block">
                            <p>
                                Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                            </p>
                        </div>
                    </div>
                    <div class="price-buy-block">
                        <span>560 $</span>
                        <button class="btn-shop btn-shop--fill"><span class="icon-shop-shopping-cart-line"></span><span class="buy">Buy</span></button>
                    </div>
                    <button class="icon-shop-heart-line btn-shop btn-shop--line favorite active"></button>
                </a>

                <a href="#" class="products-card">
                    <div class="photo-block">
                        <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-2.jpg" alt="">
                    </div>
                    <div class="info-block">
                        <h4>
                            Gazer 43" Full HD Smart TV
                        </h4>
                        <div class="about-info-block">
                            <p>
                                Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                            </p>
                        </div>
                    </div>
                    <div class="price-buy-block">
                        <span>560 $</span>
                        <button class="btn-shop btn-shop--fill"><span class="icon-shop-shopping-cart-line"></span><span class="buy">Buy</span></button>
                    </div>
                    <button class="icon-shop-heart-line btn-shop btn-shop--line favorite"></button>
                </a>

                <a href="#" class="products-card">
                    <div class="photo-block">
                        <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-3.jpg" alt="">
                    </div>
                    <div class="info-block">
                        <h4>
                            Gazer 43" Full HD Smart TV
                        </h4>
                        <div class="about-info-block">
                            <p>
                                Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                            </p>
                        </div>
                    </div>
                    <div class="price-buy-block">
                        <span>560 $</span>
                        <button class="btn-shop btn-shop--fill"><span class="icon-shop-shopping-cart-line"></span><span class="buy">Buy</span></button>
                    </div>
                    <button class="icon-shop-heart-line btn-shop btn-shop--line favorite"></button>
                </a>

                <a href="#" class="products-card">
                    <div class="photo-block">
                        <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-4.jpg" alt="">
                    </div>
                    <div class="info-block">
                        <h4>
                            Gazer 43" Full HD Smart TV
                        </h4>
                        <div class="about-info-block">
                            <p>
                                Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                            </p>
                        </div>
                    </div>
                    <div class="price-buy-block">
                        <span>560 $</span>
                        <button class="btn-shop btn-shop--fill"><span class="icon-shop-shopping-cart-line"></span><span class="buy">Buy</span></button>
                    </div>
                    <button class="icon-shop-heart-line btn-shop btn-shop--line favorite"></button>
                </a>
            </div>

        </div>
    </section>
*/ ?>

    <?php if ($this->popular) { ?>
    <section class="products-section">
        <div class="cont">
            <div class="products-grid columns-four products-grid--home">
                <div class="title-block">
                    <h2>
                        Popular
                    </h2>

                    <a href="{LINK:shop-search}" class="link-show">Show All</a>
                </div>
                <?php foreach ($this->popular as $item) {
                    include _SYSDIR_ . 'modules/shop/views/parts/_product_card.php';
                } ?>
            </div>
            <div class="search-all">
                <a href="{LINK:shop-search}" class="btn-shop btn-shop--fill">Search Products</a>
            </div>

        </div>
    </section>
    <?php } ?>

    <?php include _SYSDIR_  . 'modules/shop/views/parts/_cart.php'; ?>

</div>
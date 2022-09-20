<div class="shop-page">

    <?php include _SYSDIR_  . 'modules/shop/views/parts/_cart_button.php'; ?>

    <div class="page-with-sidebar">
        <div class="cont page-with-sidebar__container">
            <aside id="sidebar" class="sidebar products-sidebar">
                <a href="{LINK:shop-search}" class="products-btn-back">
                    <span class="icon-shop-arrow-right btn-shop btn-shop--line"></span>
                    <p class="link-show">Back</p>
                </a>
                <?php /*
                <?php include _SYSDIR_  . 'modules/shop/views/parts/_filters.php'; ?>
 */ ?>
            </aside>

            <div class="products-wrapper">

                <div class="top-block top-block--view">
                    <a href="{LINK:shop-search}" class="products-btn-back">
                        <span class="icon-shop-arrow-right btn-shop btn-shop--line"></span>
                        <p class="link-show">Back</p>
                    </a>
                    <div class="top-block__filters">
                        <p>Filters</p>
                        <button id="filters-btn-shop" class="icon-shop-filter-line btn-shop btn-shop--line filters-btn-shop"></button>
                    </div>
                </div>

                <section class="products-view">

                    <div class="products-view__top">
                        <?php if ($this->images || $this->videos) { ?>
                        <div class="products-thumbs-slider">
                            <div class="swiper mySwiper2 thumbs-slider-top">
                                <div class="swiper-wrapper">
                                    <?php if ($this->images) { ?>
                                        <?php foreach ($this->images as $item) { ?>
                                            <div class="swiper-slide thumbs-slider-top__photo">
                                                <img src="<?= _SITEDIR_ ?>data/shop/<?= $item->media ?>" alt="">
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($this->videos) { ?>
                                        <?php foreach ($this->videos as $item) { ?>
                                            <div class="swiper-slide thumbs-slider-top__video">
                                                <iframe width="560" height="315" src="<?= $item->media ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <!--                            <div class="swiper-button-next"></div>-->
                                <!--                            <div class="swiper-button-prev"></div>-->
                            </div>
                            <div thumbsSlider="" class="swiper mySwiper thumbs-slider-bottom">
                                <div class="swiper-wrapper">
                                    <?php if ($this->images) { ?>
                                        <?php foreach ($this->images as $item) { ?>
                                            <div class="swiper-slide">
                                                <div class="thumbs-slider-bottom__photo">
                                                    <img src="<?= _SITEDIR_ ?>data/shop/<?= $item->media ?>" alt="">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($this->videos) { ?>
                                        <?php foreach ($this->videos as $item) { ?>
                                            <div class="swiper-slide">
                                                <div class="thumbs-slider-bottom__video">
                                                    <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-3.jpg" alt="">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="products-card products-view__info">
                            <h2>
                                <?= $this->product->title ?>
                            </h2>
                            <?php if ($this->avg_rating) { ?>
                            <div class="rating-block">
                                <?php for ($i = 0, $j = $this->avg_rating; $i < 5; $i++) {
                                    if ($i < $j) {
                                        $class = 'icon-shop-star-fill star active';
                                    } else {
                                        $class = 'icon-shop-star-line star';
                                    }
                                    ?>
                                    <span class="<?= $class ?>"></span>
                                <?php } ?>

                                <p><span><?= count($this->reviews) ?></span> <a class="link-show" href="#"
                                    onclick="scrollToEl('.products-reviews|900'); return false;">Reviews</a></p>
                            </div>
                            <?php } ?>

                            <div class="products-info-view">

                                <?= reFilter($this->product->preview_content)?>

                                <table>
                                    <tbody>
                                    <tr>
                                        <td>Type:</td>
                                        <td><?= propertiesToString($this->product->types) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Brand:</td>
                                        <td><?= propertiesToString($this->product->brands) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="price-buy-block">
                                <span><?= $this->product->price ?> $</span>
                                <?php if (Cart::getProduct($this->product->id)) { ?>
                                    <button class="btn-shop btn-shop--fill" id="btn-buy-<?= $this->product->id ?>"><span id="btn-buy-text-<?= $this->product->id ?>">Product in cart</span></button>
                                <?php } else { ?>
                                    <button class="btn-shop btn-shop--fill" id="btn-buy-<?= $this->product->id ?>" onclick="load('shop/buy_product', 'product_id=<?= $this->product->id ?>'); return false;"><span id="btn-buy-text-<?= $this->product->id ?>">Buy</span></button>
                                <?php } ?>
                            </div>

                        </div>
                    </div>

                </section>

                <section class="products-info-view products-info-view--section">
                    <h3>
                        Product description
                    </h3>

                    <?= reFilter($this->product->content)?>

                    <h3>
                        Product characteristics
                    </h3>
                    <table>
                        <tbody>
                        <tr>
                            <td>Type:</td>
                            <td><?= propertiesToString($this->product->types) ?></td>
                        </tr>
                        <tr>
                            <td>Brand:</td>
                            <td><?= propertiesToString($this->product->brands) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </section>

                <?php include _SYSDIR_ . 'modules/shop/views/parts/_product_reviews.php';  ?>

                <?php if ($this->similar) { ?>
                <section class="products-grid products-grid--view">
                    <div class="title-block">
                        <h2>
                            Search Products
                        </h2>

                        <a href="{LINK:shop-search}" class="link-show">Show All</a>
                    </div>

                    <?php foreach ($this->similar as $item) {
                        include _SYSDIR_ . 'modules/shop/views/parts/_product_card.php';
                    } ?>

                </section>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include _SYSDIR_  . 'modules/shop/views/parts/_cart.php'; ?>

</div>
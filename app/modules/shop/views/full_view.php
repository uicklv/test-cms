<div class="shop-page">
    <div class="page-with-sidebar">
        <div class="cont page-with-sidebar__container">
            <aside id="sidebar" class="sidebar products-sidebar">
                <div class="sidebar__inner products-sidebar__container">
                    <div class="top-block">
                        <button id="filters-btn-shop" class="icon-shop-filter-line btn-shop btn-shop--line filters-btn-shop"></button>
                        <p>Filters</p>
                    </div>
                    <div class="products-sidebar__elem">
                        <h5>
                            Key words
                        </h5>
                        <input type="text" placeholder="Search...">
                    </div>

                    <div class="products-sidebar__elem products-sidebar__accordion">
                        <h5 class="icon-shop-arrow-down accordion-trigger active">
                            Price
                        </h5>
                        <div class="accordion-content active">
                            <input id="inputPieces" class="double-range" type="range" multiple value="" min="0" max="5000" unit="$" />
                        </div>
                    </div>

                    <div class="products-sidebar__elem products-sidebar__accordion">
                        <h5 class="icon-shop-arrow-down accordion-trigger active">
                            Type
                        </h5>
                        <ul class="accordion-content active">
                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>Space (<span>10</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>Transport (<span>12</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>Building (<span>16</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>Homes (<span>16</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>SUV (<span>12</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>SUV (<span>12</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>Lorem (<span>16</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="type" value="">
                                    <p>Lorem (<span>16</span>)</p>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="products-sidebar__elem products-sidebar__accordion">
                        <h5 class="icon-shop-arrow-down accordion-trigger active">
                            Theme
                        </h5>
                        <ul class="accordion-content active">
                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="theme" value="">
                                    <p>Transport (<span>12</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="theme" value="">
                                    <p>Building (<span>16</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="theme" value="">
                                    <p>Building (<span>16</span>)</p>
                                </label>
                            </li>

                            <li>
                                <label class="checkbox">
                                    <input type="checkbox" name="theme" value="">
                                    <p>Transport (<span>12</span>)</p>
                                </label>
                            </li>

                        </ul>
                    </div>
                    <div class="show-search-block">
                        <button class="btn-shop btn-shop--fill">Show</button>
                        <button class="icon-shop-delete-line btn-shop btn-shop--line"></button>
                    </div>
                </div>
            </aside>

            <div class="products-wrapper">
                <div class="top-block top-block--view">
                    <a href="#" class="products-btn-back">
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
                        <div class="products-thumbs-slider">
                            <div class="swiper mySwiper2 thumbs-slider-top">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-1.jpg" alt="">
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-2.jpg" alt="">
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-3.jpg" alt="">
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-4.jpg" alt="">
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-5.jpg" alt="">
                                    </div>
                                </div>
                                <!--                            <div class="swiper-button-next"></div>-->
                                <!--                            <div class="swiper-button-prev"></div>-->
                            </div>
                            <div thumbsSlider="" class="swiper mySwiper thumbs-slider-bottom">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="thumbs-slider-bottom__photo">
                                            <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="thumbs-slider-bottom__photo">
                                            <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-2.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="thumbs-slider-bottom__photo">
                                            <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-3.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="thumbs-slider-bottom__photo">
                                            <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-4.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="thumbs-slider-bottom__photo">
                                            <img src="<?= _SITEDIR_ ?>public/images/shop/slider-view-5.jpg" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="products-card products-view__info">
                            <h2>
                                Converse Chuck 70 - modern classics
                            </h2>
                            <div class="rating-block">
                                <span class="icon-shop-star-fill star active"></span>
                                <span class="icon-shop-star-fill star active"></span>
                                <span class="icon-shop-star-fill star active"></span>
                                <span class="icon-shop-star-fill star active"></span>
                                <span class="icon-shop-star-line star"></span>

                                <p><span>30+</span> <a class="link-show" href="#">Reviewes</a></p>
                            </div>

                            <div class="products-info-view">
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla justo, rhoncus magna aliquam viverra interdum felis, et. At facilisis ut risus faucibus ipsum tortor. Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                                </p>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>Type:</td>
                                        <td>Lorem ipsum dolor sit amet.</td>
                                    </tr>
                                    <tr>
                                        <td>Color:</td>
                                        <td>Dolor sit amet.</td>
                                    </tr>
                                    <tr>
                                        <td>Type:</td>
                                        <td>Sit amet.</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="price-buy-block">
                                <span>560 $</span>
                                <button class="btn-shop btn-shop--fill">Buy now</button>
                            </div>

                        </div>
                    </div>

                </section>

                <section class="products-info-view products-info-view--section">
                    <h3>
                        Product description
                    </h3>

                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla justo, rhoncus magna aliquam viverra interdum felis, et. At facilisis ut risus faucibus ipsum tortor. Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                    </p>

                    <h3>
                        Product characteristics
                    </h3>
                    <table>
                        <tbody>
                        <tr>
                            <td>Type:</td>
                            <td>Lorem ipsum dolor sit amet.</td>
                        </tr>
                        <tr>
                            <td>Color:</td>
                            <td>Dolor sit amet.</td>
                        </tr>
                        <tr>
                            <td>Type:</td>
                            <td>Sit amet.</td>
                        </tr>
                        </tbody>
                    </table>
                </section>

                <section class="products-reviews">
                    <div class="products-reviews__title">
                        <h3>
                            Reviews
                        </h3>
                        <button class="btn-shop btn-shop--line">30</button>
                    </div>
                    <div class="products-reviews__container">
                        <div class="products-reviews__top">
                            <div class="products-reviews__author">
                                <div class="photo-block">
                                    <img src="<?= _SITEDIR_ ?>public/images/shop/author-1.jpg" alt="">
                                </div>
                                <h4>
                                    Alex Dias
                                </h4>
                            </div>
                            <div class="products-reviews__rating">
                                <time>21 July 2022</time>
                                <div class="rating-block">
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-line star"></span>
                                </div>
                            </div>
                        </div>

                        <div class="products-reviews__info">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla justo, rhoncus magna aliquam viverra interdum felis, et. At facilisis ut risus faucibus ipsum tortor. Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                            </p>
                        </div>
                    </div>

                    <div class="products-reviews__container">
                        <div class="products-reviews__top">
                            <div class="products-reviews__author">
                                <div class="photo-block">
                                    <img src="<?= _SITEDIR_ ?>public/images/shop/author-2.jpg" alt="">
                                </div>
                                <h4>
                                    Alex Dias
                                </h4>
                            </div>
                            <div class="products-reviews__rating">
                                <time>21 July 2022</time>
                                <div class="rating-block">
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-line star"></span>
                                </div>
                            </div>
                        </div>

                        <div class="products-reviews__info">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla justo, rhoncus magna aliquam viverra interdum felis, et. At facilisis ut risus faucibus ipsum tortor. Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                            </p>
                        </div>
                    </div>

                    <div class="products-reviews__container">
                        <div class="products-reviews__top">
                            <div class="products-reviews__author">
                                <div class="photo-block">
                                    <img src="<?= _SITEDIR_ ?>public/images/shop/author-3.jpg" alt="">
                                </div>
                                <h4>
                                    Alex Dias
                                </h4>
                            </div>
                            <div class="products-reviews__rating">
                                <time>21 July 2022</time>
                                <div class="rating-block">
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-fill star active"></span>
                                    <span class="icon-shop-star-line star"></span>
                                </div>
                            </div>
                        </div>

                        <div class="products-reviews__info">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla justo, rhoncus magna aliquam viverra interdum felis, et. At facilisis ut risus faucibus ipsum tortor. Et ut dictumst viverra vivamus fringilla massa. Nam eu amet scelerisque dui sed leo ut ullamcorper.
                            </p>
                        </div>
                    </div>


                </section>

                <section class="products-grid products-grid--view">
                    <div class="title-block">
                        <h2>
                            Search Products
                        </h2>

                        <a href="#" class="link-show">Show All</a>
                    </div>
                    <a href="#" class="products-card">
                        <div class="photo-block">
                            <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-5.jpg" alt="">
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
                            <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-6.jpg" alt="">
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
                            <img src="<?= _SITEDIR_ ?>public/images/shop/product-card-7.jpg" alt="">
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
                            <img src="<?= _SITEDIR_ ?>public/images/shop/home-img-1.jpg" alt="">
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
                </section>

            </div>
        </div>
    </div>

</div>
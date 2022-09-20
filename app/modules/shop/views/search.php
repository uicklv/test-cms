<div class="shop-page">

    <?php include _SYSDIR_  . 'modules/shop/views/parts/_cart_button.php'; ?>

    <div class="page-with-sidebar">
        <div class="cont page-with-sidebar__container">
            <aside id="sidebar" class="sidebar products-sidebar">
                <?php include _SYSDIR_  . 'modules/shop/views/parts/_filters.php'; ?>
            </aside>

            <div class="products-wrapper" id="products-content">
                <div class="top-block" id="main-block">
                    <h3>Product name</h3>

                    <div class="filters-menu">

                        <div class="filters-menu__btn">
                            <button id="filters-btn-shop" class="icon-shop-filter-line btn-shop btn-shop--line"></button>
                            <p class="icon-shop-arrow-down filters-menu__active">Popular</p>
                        </div>

                        <ul>
                            <li class="active" data-value="popular">Popular</li>
                            <li data-value="recent">Most recent</li>
                            <li data-value="cheap">From cheap</li>
                            <li data-value="expensive">From expensive</li>
                        </ul>

                    </div>

                </div>

                <section class="products-grid" id="search_results_box">
                    <?php include _SYSDIR_ . 'modules/shop/views/parts/_search_process.php'; ?>
                </section>

                <div class="navigation-block" id="pagination-block">
                    <?= Pagination::ajax('shop/search_process', ['type' => get('type'),
                        'keywords' => $this->keywords ? arrayToString($this->keywords) : '']) ?>

<!--                    <a href="#" class="icon-shop-arrow-right btn-shop btn-shop--line prev"></a>-->
<!---->
<!--                    <div class="num-pages">-->
<!--                        <a href="#" class="btn-shop btn-shop--line active">1</a>-->
<!--                        <a href="#" class="btn-shop btn-shop--line">2</a>-->
<!--                        <a href="#" class="btn-shop btn-shop--line">3</a>-->
<!--                        <a href="#" class="btn-shop btn-shop--line">4</a>-->
<!--                        <a href="#" class="btn-shop btn-shop--line">5</a>-->
<!--                        <a href="#" class="btn-shop btn-shop--line">6</a>-->
<!--                        <a href="#" class="btn-shop btn-shop--line">7</a>-->
<!--                    </div>-->
<!---->
<!--                    <a href="#" class="icon-shop-arrow-right btn-shop btn-shop--line next"></a>-->
                </div>

            </div>
        </div>
    </div>

    <?php include _SYSDIR_  . 'modules/shop/views/parts/_cart.php'; ?>

</div>
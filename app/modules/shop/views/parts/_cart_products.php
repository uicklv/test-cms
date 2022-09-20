<?php

$productsIds = array_keys(Cart::getProducts());

$products = [];
$sumPrice = 0;
if ($productsIds) {
    $products = Model::fetchAll(Model::select('shop_products', " `deleted` = 'no' AND `posted` 
    AND `id` IN (" . implode(',', $productsIds) . ")"));

    foreach ($products as $product) {
        $sumPrice += ($product->price * Cart::getProduct($product->id));
    }
}

?>
<div class="cart-wrapper">
    <button class="icon-shop-close-line cart-popup__close-btn"></button>
    <div class="top-block">
        <a href="#" class="products-btn-back cart-popup__close">
            <span class="icon-shop-arrow-right btn-shop btn-shop--line"></span>
            <p class="link-show">Back</p>
        </a>

        <h3>
            Your Card
        </h3>
        <p class="items-card-length"><?= Cart::getCount() ?> items</p>
    </div>
    <ul class="cart-list">
        <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
                <li class="cart-list__item" id="cart-product-<?= $product->id ?>">
                    <div class="cart-list__start">
                        <div class="photo-block">
                            <img src="<?= _SITEDIR_ ?>data/shop/<?= $product->image ?>" alt="">
                        </div>
                        <h4 class="cart-list__title">
                            <?= $product->title ?>
                        </h4>
                    </div>
                    <div class="counter-block">
                        <button class="icon-shop-minus btn-shop btn-shop--line"></button>
                        <input class="count-input" type="text" value="1">
                        <button class="icon-shop-plus btn-shop btn-shop--line"></button>
                    </div>
                    <span class="cart-list__price"><?= $product->price ?> $</span>

                    <button class="icon-shop-close-line cart-list__close" onclick="load('shop/remove_product',
                            'product_id=<?= $product->id ?>')"></button>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
    <div class="bottom-block">

        <div class="price-item">
            <h3>Total:</h3>
            <span><?= $sumPrice ?> $</span>
        </div>

        <button class="btn-shop btn-shop--fill">Checkout</button>
    </div>
</div>


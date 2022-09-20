<div class="products-card">
    <a href="{URL:shop/product/<?= $item->slug ?>}" class="products-card__item">
        <div class="photo-block">
            <img src="<?= _SITEDIR_ ?>data/shop/<?= $item->image ?>" alt="">
        </div>
        <div class="info-block">
            <h4>
                <?= $item->title ?>
            </h4>
            <div class="about-info-block">
                <p>
                    <?= reFilter($item->preview_content) ?>
                </p>
            </div>
        </div>
    </a>
    <div class="price-buy-block">
        <span><?= $item->price ?> $</span>

        <?php if (Cart::getProduct($item->id)) { ?>
            <button class="btn-shop btn-shop--fill" id="btn-buy-<?= $item->id ?>"><span class="icon-shop-check-fill"></span></button>
        <?php } else { ?>
        <button class="btn-shop btn-shop--fill" id="btn-buy-<?= $item->id ?>" onclick="load('shop/buy_product', 'product_id=<?= $item->id ?>'); return false;"><span class="icon-shop-shopping-cart-line"></span><span class="buy" id="btn-buy-text-<?= $item->id ?>">Buy</span></button>
        <?php } ?>
    </div>
    <button id="save-button-<?= $item->id ?>" class="icon-shop-heart-line btn-shop btn-shop--line favorite <?= $item->saved ? 'active' : '' ?>"
            onclick=" load('shop/add_shortlist', 'user=<?= User::get('id', 'candidate') ?>', 'product=<?= $item->id ?>');return false;"></button>
</div>

<?php if ($this->products) { ?>
    <?php foreach ($this->products as $item) {
        include _SYSDIR_ . 'modules/shop/views/parts/_product_card.php';
    } ?>
<?php } else { ?>
    <h4>Results not found</h4>
<?php } ?>

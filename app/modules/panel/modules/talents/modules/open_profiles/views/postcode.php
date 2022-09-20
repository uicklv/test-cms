<?php foreach ($this->postcodes as $item) { ?>
    <div class="pc-item" onclick="fillPostcode(this);"><?php echo $item->postcode; ?></div>
<?php } ?>
<?php foreach ($this->postcodes as $item) { ?>
    <div class="pc-item" onclick="fillPostcode(this);" style="cursor: pointer;"><?php echo $item->postcode; ?></div>
<?php } ?>
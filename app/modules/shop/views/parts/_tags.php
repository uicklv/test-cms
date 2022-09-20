<?php if ($this->keywords) { ?>
    <?php foreach ($this->keywords as $k => $keyword) { ?>
        <button class="btn-shop btn-shop--line" onclick="load('shop/search_process', 'form:#search_form', 'tag_type=unset', 'value=<?= $keyword ?>');return false;"
                id="tag_<?= $k ?>"><?= $keyword ?>
            <span class="icon-shop-close-line"></span></button>
        <input type="hidden" name="keywords[]" value="<?= $keyword ?>">
    <?php } ?>
<?php } ?>


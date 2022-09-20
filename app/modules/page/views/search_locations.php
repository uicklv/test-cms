<?php if (is_array($this->list) && count($this->list) > 0) { ?>
    <?php foreach ($this->list as $item) {
//        if (post('postcode')) {
//            $range = 10;
//            if ($item->distance > $range) continue;
//        }
        list($lat, $lng) = explode(",", $item->coordinates);
        ?>
        <li onclick="getOffice(<?= $item->id ?>);" class="locations-li">
            <div>
                <h3><?= $item->name . (isset($item->distance) ? ' <span style="font-size: 12px; color: gray;">' . round($item->distance, 2) . ' miles</span>' : ''); ?></h3>
                <div>
                    <?= $item->address ?>,<br> <?= $item->postcode ?> <br>
                    <?= $item->tel ?>
                </div>
                <div class="lg-time">
                    <div><span><?= $item->day_1 ?></span><?= $item->time_1 ?></div>
                    <div><span><?= $item->day_2 ?></span><?= $item->tim_2 ?></div>
                </div>
                <a class="lg-details" href="{URL:locations/<?= $item->slug ?>}">View details</a>
            </div>
        </li>
    <?php } ?>
<?php } ?>

<script>
    //add class active
    $('.locations-li').on('click', function () {
        $('.locations-li').removeClass('active');
        $(this).addClass('active');
    })
</script>



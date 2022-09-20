<?php foreach ($this->list as $item) { ?>
    <div>
        <div class="rs-item">
            <div class="rs-pic">
                <?php
                $techArray = explodeString('|', trim($item->tech_stack, '|'));
                foreach ($techArray as $tech)
                    echo '<img src="'._SITEDIR_.'data/tech_stack/' . $this->tech_list[ $tech ]->image . '" height="66" width="48" alt="' . $this->tech_list[ $tech ]->name . '" title="' . $this->tech_list[ $tech ]->name . '"/>';
                ?>
            </div>
            <div class="rs-cont">
                <h3 class="rs-title"><?= $item->title; ?></h3>
                <p>
                    <?= implode(", ", array_map(function ($location) {
                        return $location->location_name;
                    }, $item->locations)); ?>
                </p>
                <p>
                    <b><?= ucfirst($item->contract_type); ?></b>
                    <br><?= $item->salary_value; ?>
                </p>
                <p><?= reFilter($item->content_short); ?></p>
            </div>
            <a class="rs-more" href="{URL:job/<?= $item->slug; ?>}">Find out more</a>
        </div>
    </div>
<?php } ?>

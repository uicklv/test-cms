<?php
$range = post('radius', 'int');
if ($range <= 0 OR $range > 250)
    $range = 10;

if (is_array($this->list) && count($this->list) > 0) { ?>
    <?php foreach ($this->list as $item) {

        if (post('radius') && post('postcode')) {
            if ($item->distance > $range) continue;
        }
        ?>
        <li>
            <div class="box-sec">
                <h2 class="title-small"><?= $item->job_title ?></h2>
                <?= (isset($item->distance) ? '<p><span style="font-size: 12px; color: gray;">' . $item->distance . ' miles</span></p>' : '') ?>
                <ul>
                    <li>
                        <p class="bs-title">Salary / Rate Required:</p>
                        <p class="bs-title-cont"><?= SalaryJoin($item->annual_currency, $item->min_annual_salary,
                                $item->daily_currency, $item->min_daily_salary, $item->hourly_currency, $item->min_hourly_salary) ?></p>
                    </li>
                    <li>
                        <p class="bs-title">Skills:</p>
                        <p class="bs-title-cont"><?= str_replace(',', ', ', $item->keywords) ?></p>
                    </li>
                    <li><p class="bs-title">Current Location:</p>
                        <p class="bs-title-cont">
                            <?= implode(", ", array_map(function ($location) {
                                    return $location->location_name;
                                }, $item->locations )
                            ); ?>
                        </p>
                    </li>
                    <li>
                        <p class="bs-title">Contract Preference:</p>
                        <p class="bs-title-cont"><?= ucfirst($item->contract) ?></p>
                    </li>
                </ul>
                <div class="box-bottom-line">

                <div class="bs-flex">
                    <h2 class="bs-sub-head">Represented by<br><?= $item->consultant->firstname . ' ' .  $item->consultant->lastname?></h2>
                    <div class="bs-pic">
                        <div style="background-image: url(<?= _SITEDIR_ ?>data/users/<?= $item->consultant->image ?>)"></div>
                    </div>
                </div>
                <a href="{URL:talent/anonymous_profile/<?= $item->id ?>}" class="btn-center btn-yellow">view candidate</a>
                </div>
        </li>
    <?php } ?>
<?php } ?>

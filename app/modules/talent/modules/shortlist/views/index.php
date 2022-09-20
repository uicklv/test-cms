<section class="head-block mar head-block-small">
    <div class="fixed">
        <div class="head-cont">
            <div style="width: 100%">
                <h3 class="title-big center mar"><span><?= $this->list->name ?></span></h3>
                <div class="hc-info">
<!--                    <p><span>Name:</span> </p>-->
                    <p><span>Vacancy Title:</span> <?= $this->list->vacancy_title ?></p>
                    <p><span>Location:</span> <?= $this->list->location ?></p>
                </div>
                <div class="hc-info-text"><?= reFilter($this->list->description)?></div>
            </div>
        </div>
    </div>
</section>

<!-- RESULTS -->
<div class="develop-sec">
    <div class="fixed">
        <div class="develop-cont">
            <ul id="anonymous-profiles">
                <?php if (is_array($this->open_profiles) && count($this->open_profiles) > 0) { ?>
                    <?php foreach ($this->open_profiles as $item) { ?>
                        <li>
                            <div class="box-sec">
                                <h2 class="title-small"><?= $item->candidate_name ?></h2>
                                <ul>
                                    <li>
                                        <p class="bs-title">Salary / Rate Required:</p>
                                        <p class="bs-title-cont"><?= SalaryJoin($item->annual_currency, $item->min_annual_salary,
                                                $item->daily_currency, $item->min_daily_salary, $item->hourly_currency, $item->min_hourly_salary) ?></p></li>
                                    <li>
                                        <p class="bs-title">Skills:</p>
                                        <p class="bs-title-cont"><?= str_replace(',', ', ', $item->keywords) ?></p>
                                    </li>
                                    <li>
                                        <p class="bs-title">Current Location:</p>
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
                                    <a href="{URL:talent/open_profile/<?= $item->id ?>}" class="btn-center btn-yellow">view candidate</a>
                                </div>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<!-- INFO -->
<div class="candidate-sec">
    <div class="container">
        <div class="candidate-cont">
            <div class="row">
                <div class="col-md-6 col-12">
                    <h1 class="sub-head">Are you a hiring manager<br> looking for that perfect candidate?</h1>
                    <br>
                    <p>Then browse and search to view our active candidates.</p>
                </div>
                <div class="col-md-6 col-12">
                    <div class="cc-flex">
                        <h1 class="sub-head">Can't find that  perfect candidate?</h1>
                        <a href="#" data-toggle="modal" data-target="#candidate_alert" class="btn-yellow btn-inline">register candidate alert</a>
                    </div>
                    <p>Simply sign up for "candidate alerts" here, and as soon as we find your ideal candidate profile, you'll immediately be alerted!</p>
                </div>
            </div>
        </div>
    </div>
</div>

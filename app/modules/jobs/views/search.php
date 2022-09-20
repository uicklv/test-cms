<?php
foreach ($this->list as $item) {
    ?>
    <li class="rs-item">
        <div class="rs-pic">
            <?php
            $techArray = explodeString('|', trim($item->tech_stack, '|'));
            foreach ($techArray as $tech)
                echo '<img src="'._SITEDIR_.'data/tech_stack/' . $this->tech_list[ $tech ]->image . '" height="66" width="48" alt="' . $this->tech_list[ $tech ]->name . '" title="' . $this->tech_list[ $tech ]->name . '"/>';
            ?>
        </div>
        <div class="rs-cont">
            <h3 class="rs-title"><?= $item->title; ?></h3>
            <p><?= propertiesToString($item->locations) ?></p>
            <p>
                <b><?= ucfirst($item->contract_type); ?></b>
                <br><?= $item->salary_value; ?>
            </p>
            <p><?= reFilter($item->content_short); ?></p>
        </div>
        <a class="rs-more" href="{URL:job/<?= $item->slug; ?>}" onclick="load('job/<?= $item->slug; ?>');">Find out more</a>
    </li>
    <?php
}
?>
<li class="rs-item rs-imitation"></li>
<div class="sjs__alerts-block alerts-block" id="alert_block">
    <h3 class="ab__title">Get alerts for this search</h3>
    <div class="email-form">
        <input type="email" id="email" class="gray-input" placeholder="Email">
        <button class="btn-blue" onclick="load('jobs/get_alerts', 'email#email', 'form:#search_form'); return false;">send</button>
    </div>
</div>
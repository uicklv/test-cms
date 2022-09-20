<section class="head-block mar looking-inner">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">
                Expiring Jobs
            </div>
            <h1 class="gen-title">
                <contentElement name="search-jobs-title" type="input"><span>Elevate</span><br>YoUR ambition</contentElement>
            </h1>
        </div>
    </div>
    <span class="pattern_13"><img src="<?= _SITEDIR_ ?>public/images/pattern_13.png" height="235" width="548"/></span>
</section>

<style>
    .ui-selectmenu-menu ul.ui-menu {
        max-height: 300px;
    }
</style>
<?php /*
<section class="section_15">
    <form id="search_form" class="fixed" method="post">
        <div class="search-jobs">
            <input id="keywords" name="keywords" class="sj-text-field" type="text" placeholder="Keyword or job title" value="<?= post('keywords'); ?>">
            <button class="sj-sub" type="" onclick="load('jobs/search', 'form:#search_form'); return false;"><span class="icon-search"></span></button>
        </div>
        <div class="filter-jobs">
            <div class="fj-item">
                <label class="fj-label">Type of vacancy</label>
                <select class="select" id="type" name="type">
                    <option value="">Choose one</option>
                    <option value="permanent">Permanent</option>
                    <!--<option value="temporary">Temporary</option>-->
                    <option value="contract">Contract</option>
                </select>
            </div>
            <div class="fj-item">
                <label class="fj-label">Industry</label>
                <select class="select" id="sector" name="sector">
                    <option value="">Choose one</option>
                    <?php if ($this->sectors) { ?>
                        <?php foreach ($this->sectors as $item) { ?>
                            <option value="<?= $item->id; ?>" <?= checkOptionValue(post('sector'), $item->id); ?>><?= $item->name; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="fj-item">
                <label class="fj-label">Location</label>
                <select class="select" id="location" name="location">
                    <option value="">Choose one</option>
                    <?php if ($this->locations) { ?>
                        <?php foreach ($this->locations as $item) { ?>
                            <option value="<?= $item->location_id; ?>" <?= checkOptionValue(post('location'), $item->id); ?>><?= $item->name; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <button class="btn-yellow" type="submit" onclick="load('jobs/search', 'form:#search_form'); return false;">Search</button>
        </div>
    </form>
</section>
*/ ?>
<div class="fixed">
    <ul id="search_results_box" class="rs-list">
        <?php foreach ($this->list as $item) { ?>
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
                <a class="rs-more" href="{URL:job/<?= $item->slug; ?>}" onclick="load('job/<?= $item->slug; ?>');">Find out more</a>
            </li>
        <?php } ?>
        <li class="rs-item rs-imitation"></li>
    </ul>
</div>

<?php /*
// Buttons for shortlist jobs
<div id="add_<?= $item->id ?>">
<?php
if (!User::get('id')) { ?>
    <a class="plus" onclick="load('login')">add</a>
    <?php } else {
        if (!$item->saved){ ?>
            <a class="plus" onclick="load('jobs/add_shortlist', 'user=<?= User::get('id')?>', 'job=<?= $item->id ?>')">add</a>
            <?php
        } else { ?>
            <a class='trashcan'
               onclick="load('jobs/add_shortlist', 'user=<?= User::get('id')?>', 'job=<?= $item->id ?>')"><i class="fas fa-trash">remove</i></a>
        <?php
        }
    }
?>
</div>
 */?>

<script>
    $('.select').selectmenu();
</script>
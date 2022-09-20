<section class="head-block mar looking-inner">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">
                <contentElement name="search-jobs" type="input">Search Jobs</contentElement>
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

<div id="search_results_box">
    <section class="section_15">
    <div>
        <form id="search_form" class="fixed" method="post">
            <div class="search-jobs">
                <input id="keywords" name="keywords" class="sj-text-field" type="text" placeholder="Keyword or job title" value="<?= post('keywords'); ?>">
                <button class="sj-sub" onclick="load('jobs/search_2', 'form:#search_form'); return false;"><span class="icon-search"></span></button>
            </div>
            <div class="filter-jobs">
                <div class="fj-item">
                    <label class="fj-label">Type of vacancy</label>
                    <ul>
                        <li>
                            <input type="checkbox" onchange="load('jobs/search_2', 'form:#search_form'); return false;" name="type[]" id="permanent"  value="permanent"
                                <?= checkCheckboxValue(post('type'),'permanent'); ?>>
                            <label for="permanent">Permanent<span class="tcoutner">
                                  <?php
                                  foreach ($this->types as $k => $count) {
                                      if ($k == 'permanent') echo '( ' . $count . ' )';
                                  }
                                  ?>
                                </span></label>
                        </li>
                        <li>
                            <input type="checkbox"  onchange="load('jobs/search_2', 'form:#search_form'); return false;" name="type[]" id="contract"  value="contract"
                                <?= checkCheckboxValue(post('type'),'contract'); ?>>
                            <label for="contract">Interim & Contract<span class="tcoutner">
                                <?php
                                foreach ($this->types as $k => $count) {
                                    if ($k == 'contract') echo '( ' . $count . ' )';
                                }
                                ?>
                                </span> </label>
                        </li>
                    </ul>
                </div>
                <div class="fj-item">
                    <label class="fj-label">Industry</label>
                    <div class="collapse show" id="category-collapse">
                        <ul>
                            <?php if ($this->sectors) foreach ($this->sectors as $item) { ?>
                                <li>
                                    <input id="s<?= $item->id; ?>" type="checkbox" name="sector[]" value="<?= $item->id; ?>"
                                           onchange="load('jobs/search_2', 'form:#search_form'); return false;">
                                    <label for="s<?= $item->id; ?>"><?= $item->name; ?><span class="tcoutner">
                                               ( <?= $item->counter ?> )
                                            </span></label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="fj-item">
                    <label class="fj-label">Location</label>
                    <ul>
                        <?php if ($this->locations) foreach ($this->locations as $item) {?>
                                <li>
                                    <input id="l<?= $item->id; ?>" type="checkbox" name="location[]" value="<?= $item->id; ?>"
                                           onchange="load('jobs/search_2', 'form:#search_form'); return false;">
                                    <label for="l<?= $item->id; ?>"><?= $item->name; ?><span class="tcoutner">
                                               ( <?= $item->counter ?> )
                                        </span></label>
                                </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </form>
    </section>

    <div class="fixed">
        <ul class="rs-list">
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
                        <p><?= propertiesToString($this->job->locations) ?></p>
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
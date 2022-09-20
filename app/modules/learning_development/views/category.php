<section class="section-inner" style="margin: 100px 0 100px 0">
    <div class="fixed">
        <a class="start-search" href="{URL:learning-development-resources}">Back to Resource Categories</a>
        <h1 class="gen-title" data-aos="zoom-in" data-aos-duration="1500">

            <p><?= $this->category->name ?></p>
        </h1>
        <div class="filter-news" data-aos="fade-up" data-aos-duration="1500">
            <label class="filter-check" onclick="load('learning_development/search', 'category=<?= $this->category->id ?>');">
                <input type="checkbox">
                <span class="filter-check-title">All Resources</span>
            </label>
            <label class="filter-check" onclick="load('learning_development/search', 'type=incomplete', 'category=<?= $this->category->id ?>');">
                <input type="checkbox"<?php if (get('type') == 'incomplete') echo 'checked' ?>>
                <span class="filter-check-title">Incomplete</span>
            </label>
            <label class="filter-check" onclick="load('learning_development/search', 'type=completed', 'category=<?= $this->category->id ?>');">
                <input type="checkbox" <?php if (get('type') == 'completed') echo 'checked' ?>>
                <span class="filter-check-title">Completed</span>
            </label>
        </div>
        <ul class="attitude-list" id="search_results_box">
            <?php if (is_array($this->list) && count($this->list) > 0) foreach ($this->list as $item){ ?>
                <li>
                    <div class="al-pic" style="background-image: url('<?= _SITEDIR_ ?>data/learning_development/<?= $item->image ?>')">
                        <a class="dl-link" href="{URL:learning-development-resource/<?= $item->slug ?>}">View Resource</a>
                        <?php if ($item->completed) { ?>
                        <span class="al-check"><span class="icon-check"></span></span>
                        <?php } ?>
                    </div>
                    <h3 class="al-title"><?= $item->title ?></h3>
                </li>
            <?php } ?>
        </ul>
    </div>
</section>
<div class="separator"></div>
<script>
    $('.filter-check').click(function(){
        $('.filter-check > input[type=checkbox]').attr('checked', false);
        $('.filter-check').removeClass('check_active');
        // $(this).find('input[type=checkbox]').attr('checked', true);
        $(this).addClass("check_active");
    });
</script>
<section class="section-inner" style="margin: 100px 0 100px 0">
    <div class="fixed">
        <h1 class="gen-title" data-aos="zoom-in" data-aos-duration="1500">
            <p><contentElement name="gen-title" type="input">Learning & Development</contentElement></p>
        </h1>
        <ul class="directing-list">
            <?php if (is_array($this->list) && count($this->list) > 0) foreach ($this->list as $item) {
                ?>
                <li>
                    <div class="dl-item" style="background-image: url('<?= _SITEDIR_ ?>data/learning_development/<?= $item->image ?>') ">
                        <div>
                            <a class="dl-link" href="{URL:resources/category/<?= $item->id ?>}">View Resources</a>
                        </div>
                    </div>
                    <div class="dl-text"><?= $item->name ?></div>
                </li>
            <?php } ?>
        </ul>
    </div>
</section>
<div class="separator"></div>
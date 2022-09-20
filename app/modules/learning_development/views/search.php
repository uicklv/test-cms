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

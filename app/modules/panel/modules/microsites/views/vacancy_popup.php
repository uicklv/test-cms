<?php Popup::head('Microsite Vacancies'); ?>

<form id="vac_form" class="pop_form">
    <style>
        .popup {
            justify-content: flex-start;
        }
        .popup_body {
            width: 100%;
        }

        .chk_it {
            width: 100%;
            max-width: 1024px;
            height: 30px;
            line-height: 30px;
            padding: 0 5px;
        }
        .chk_it:nth-child(2n+1) {
            background-color: #f7f7f7;
        }

        .pop_form .pf_row label {
            margin-bottom: 0;
            padding-left: 6px;
        }
    </style>

    <div class="pf_row">
        <div class="chk_it-wrap">
        <?php if (is_array($this->jobs) && count($this->jobs) > 0) { ?>
            <?php foreach ($this->jobs as $item) { ?>
                <div class="chk_it flex flex-vc">
                    <input id="j<?= $item->id ?>" type="checkbox" name="jobs[]"
                           onchange="load('panel/microsites/update_microsite_vacancy', 'form:#vac_form','microsite_id=<?= $this->microsite->id?>'); return false;"
                           value="<?= $item->id ?>" <?php if ($this->microsite->id === $item->microsite_id) echo 'checked'; ?>
                    >
                    <label for="j<?= $item->id ?>" class="pointer"><?= $item->title ?></label>
                </div>
            <?php } ?>
        <?php } ?>
        </div>
    </div>
</form>

<?php Popup::foot(); ?>
<?php Popup::closeListener(); ?>
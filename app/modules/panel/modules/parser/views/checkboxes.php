<!--<ol>-->
<!--    --><?php //foreach ($this->titles as $index => $title) { ?>
<!--        --><?php //$index++; ?>
<!--        <li>-->
<!--            <label class="pointer" for="check_row_--><?//= $index ?><!--">-->
<!--                <input name="fields[]" value="--><?//= $index ?><!--" id="check_row_--><?//= $index ?><!--" type="checkbox">- --><?//= $title ?>
<!--            </label>-->
<!--        </li>-->
<!--    --><?php //} ?>
<!--</ol>-->


<div class="form-group col-md-6">
    <label>Fields</label>
    <input type="text" class="form-control" id="field_filter" value="" autocomplete="off" placeholder="Start typing to filter fields below">
    <div class="form-check scroll_max_200 border_1">
        <?php if (isset($this->titles) && is_array($this->titles) && count($this->titles) > 0) { ?>
            <?php foreach ($this->titles as $index => $title) { ?>
                    <?php $index++; ?>
                <div class="custom-control custom-checkbox checkbox-info">
                    <input class="custom-control-input" type="checkbox" name="fields[]" id="field_<?=$index?>" value="<?= $index ?>">
                    <label class="custom-control-label fields" for="field_<?=$index?>"><?= $title ?></label>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>



<script>
    $('#get_data_box').show();
    $('#filename').show();

    $('#field_filter').keyup(function () {
        let q = $(this).val().toLowerCase().trim();

        if (q.length > 0) {
            $('.fields').each(function (i, field) {
                if ($(field).text().trim().toLowerCase().indexOf(q) === -1) {
                    $(field).parent().addClass('hidden')
                } else {
                    $(field).parent().removeClass('hidden')
                }
            });
        } else {
            $('.fields').each(function (i, field) {
                $(field).parent().removeClass('hidden')
            });
        }
    });

</script>
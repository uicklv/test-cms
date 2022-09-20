<?php Popup::head('Add New Section'); ?>

    <form id="field_form" class="ppsec" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <h3>Select from the list</h3>
            <div class="form-group col-md-12">
                <div class="form-check scroll_max_200 border_1">
                    <?php if ($this->sections) { ?>
                        <?php foreach ($this->sections as $item) { ?>
                            <?php if ($item->duplicate == 0) { ?>
                                <div class="custom-control custom-checkbox checkbox-info">
                                    <input class="custom-control-input" type="checkbox" name="section_ids[]" id="section_<?=$item->id?>" value="<?= $item->id; ?>"
                                        <?= checkCheckboxValue(post('section_ids'), $item->id, stringToArray($this->form->sections_row)); ?>
                                    ><label class="custom-control-label" for="section_<?=$item->id?>"><?= $item->title ?></label>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group col-md-12">
                <a class="btn btn-success" href="" onclick="load('panel/forms/section_popup/<?= $this->form->id ?>', 'form:#field_form', 'action_type=list');">
                    Add
                </a>
            </div>
        </div>

        <div class="form-row">
            <h3>Or add new</h3>
            <div class="form-group col-md-12">
                <div class="form-group col-md-6">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id="title" value="<?= post('title', false, $this->field->title); ?>">
                </div>
            </div>

            <div class="form-group col-md-12">
                <a class="btn btn-success" href="" onclick="load('panel/forms/section_popup/<?= $this->form->id ?>', 'form:#field_form', 'action_type=new');">
                    Add
                </a>
                <a class="btn btn-danger" onclick="closePopup();">Cancel</a>
            </div>
        </div>
    </form>

<?php Popup::foot(); ?>
    <script>
        $("#site").addClass('popup-open');
    </script>
<?php //Popup::closeListener(); ?>
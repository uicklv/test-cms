<div class="form-group col-md-12 flex-end">
    <a href="" onclick="load('panel/forms/section_popup/<?= $this->form->id ?>')">Add Section</a>
</div>
<!-- Sort input for Fields -->
<input type="hidden" name="sections_row" id="temp_row" value="<?= post('sections_row', false, $this->form->sections_row); ?>">
<script>
    var arrayObj = {};
    var itemsArray = []; // itemsArray
</script>
<div class="form-group col-md-12" id="sections_block">
    <?php
    $sectionsIds = [];
    foreach ($this->sections as $skey => $row) {
        if (is_array($row->fields) && count($row->fields) > 0) {
            $sectionsIds[] = $row->id;
        }
        ?>
        <div class="flex-start mt16 section_id_wrap" style="font-weight: 500"  section-id="<?= $row->id ?>">
            <div class="sr-icon sr-icon-move mr16"><i class="fa fa-sort" aria-hidden="true"></i></div>
            <div class="ml6" style="align-items: center; margin-top: 8px;">
                <?php if ($row->duplicate == 0) { ?>
                    <a href="{URL:panel/forms/sections/edit/<?= $row->id ?>}" target="_blank" class="tagA"><?= $row->title . ' ' . $row->title_date ?></a>
                <?php } else { ?>
                    <?= $row->title . ' ' . $row->title_date?>
                <?php } ?>
                <div>
                    <input id="fields_row_<?= $row->id ?>" type="hidden" name="fields_row_<?= $row->id ?>" value="<?= post('fields_row_' . $row->id, false, $row->fields_row); ?>">
                    <script>
                        arrayObj['itemsArray<?= $row->id ?>'] = [];
                    </script>
                    <!-- Fields -->
                    <?php if (is_array($row->fields) && count($row->fields) > 0) { ?>
                        <ul id="sections_block_<?= $row->id ?>" class="pl12 ml12" style="font-weight: 400">
                            <?php foreach ($row->fields as $key => $field) { ?>
                                <!-- Field -->
                                <li class="mb6 flex section_block_wrap" item-id="<?= $field->id ?>" style="line-height: 32px">
                                    <div class="sr-icon sr-icon-move"><i class="fa fa-sort" aria-hidden="true"></i></div>

                                    <div class="ml16 section_block_text" style="display: flex;align-items: center;">
                                        <?php if ($field->duplicate == 0) { ?>
                                            <a href="{URL:panel/forms/fields/edit/<?= $field->id ?>}" class="tagA" target="_blank"><?= $field->title ?>&nbsp;<span style="font-size: 12px; color: #a39e9e;">(<?= $field->type ?>)</span></a>
                                        <?php } else { ?>
                                            <?= $field->title ?><span style="font-size: 12px; color: #a39e9e;">(<?= $field->type ?>)</span>
                                        <?php } ?>
                                    </div>
                                    <a class="sr-icon ml10 pointer" onclick="load('panel/forms/copy_field/<?= $field->id ?>', 'section_id=<?= $row->id ?>' , 'form_id=<?= $this->form->id ?>'); return false;"><i class="fas fa-copy"></i></a>
                                    <a class="sr-icon ml10 pointer" onclick="load('panel/forms/field_edit_popup/<?= $field->id ?>/<?= $row->id ?>/<?= $this->form->id ?>'); return false;"><i class="fas fa-edit"></i></a>
                                    <a class="sr-icon ml10 pointer" onclick="if (confirm('Are you sure you wish to delete this item? Please re-confirm this action.')) load('panel/forms/remove_field/<?= $field->id ?>', 'section_id=<?= $row->id ?>' ,
                                            'form_id=<?= $this->form->id ?>')"><i class="fas fa-trash-alt"></i></a>
                                    <a class="sr-icon ml10 pointer" onclick="load('panel/select_image', 'field=images[field_<?= $field->id ?>_section_<?= $row->id ?>_form_<?= $this->form->id ?>][]', 'multiple=1', 'preview=#images-block_<?= $skey . '_' . $key ?>')" style="cursor: pointer;"><i class="fas fa-image"></i></a>
                                    <label class="checkmark_container ml-4">
                                        <span style="font-size: 12px; color: #a39e9e;">Active</span>
                                        <input type="checkbox" value="no" <?php if ($field->gray == 'no') echo 'checked'; ?>
                                               onchange="load('panel/forms/change_status_field', 'field_id=<?= $field->id ?>', 'section_id=<?= $row->id ?>', 'form_id=<?= $this->form->id ?>')">
                                        <span class="checkmark"></span>
                                    </label>

                                    <script>
                                        // Add item to array
                                        arrayObj['itemsArray<?= $row->id ?>'].push('<?= $field->id ?>');
                                    </script>
                                    <!-- Images -->
                                    <div class="flex-start flex-wrap-gap" id="images-block_<?= $skey . '_' . $key ?>">
                                        <?php if ($field->images) { ?>
                                            <?php foreach ($field->images as $k => $image) { ?>
                                                <div id="image_block_<?= $skey . '_' . $key . '_' . $k ?>">
                                                    <img id="<?= $skey . '_' . $key . '_' . $k ?>" src="<?= _SITEDIR_ ?>data/form_builder/<?= $image ?>" alt="" height="50px" class="ml-2">
                                                    <input type="hidden" id="hidden_<?= $skey . '_' . $key . '_' . $k ?>" name="images[field_<?= $field->id ?>_section_<?= $row->id ?>_form_<?= $this->form->id ?>][]" value="<?= $image ?>">
                                                    <span class="img_del" onclick="removeFieldImage('<?= $skey . '_' . $key . '_' . $k ?>')"><span class="fa fa-times-circle-o"></span></span>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <!-- End Images -->
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>

            </div>
            <div class="flex-btw ml-5 section_id_btns">
                <a class="btn btn-success" onclick="load('panel/forms/field_popup/<?= $row->id ?>/<?= $this->form->id ?>')">Add Field</a>
                <a class="sr-icon ml10 pointer" onclick="if (confirm('Are you sure you wish to delete this item? Please re-confirm this action.')) load('panel/forms/remove_section/<?= $row->id ?>', 'form_id=<?= $this->form->id ?>')"><i class="fas fa-trash-alt"></i></a>
                <a class="sr-icon ml10 pointer" onclick="load('panel/forms/copy_section/<?= $row->id ?>', 'form_id=<?= $this->form->id ?>');"><i class="fas fa-copy"></i></a>
            </div>
            <script>
                // Add item to array
                itemsArray.push('<?= $row->id ?>');
            </script>
        </div>
    <?php }
    ?>
</div>
<script>
    <?php if ($this->sections) { ?>
    $(document).ready(function() {
        var ids = <?= json_encode($sectionsIds) ?>;
        ids.forEach((k) => {
            var el_ser = document.getElementById('sections_block_' + k);
            Sortable.create(el_ser, {
                group: false, // set both lists to same group
                sort: true,
                handle: '.sr-icon-move', // handle's class
                filter: '.filtered', // 'filtered' class is not draggable
                animation: 150,
                onSort: function (evt) {
                    let fromSection = $(evt.from).attr('id');
                    let toSection = $(evt.to).attr('id');
                    let fromSectionPos = evt.oldIndex;
                    let toSectionPos = evt.newIndex;
                    // from
                    console.log(fromSectionPos)
                    if (fromSection === 'sections_block_' + k)
                        arrayObj['itemsArray' + k].splice(fromSectionPos, 1);

                    console.log(arrayObj['itemsArray' + k])
                    // to
                    console.log(toSectionPos)
                    if (toSection === 'sections_block_' + k)
                        arrayObj['itemsArray' + k].splice(toSectionPos, 0, evt.clone.attributes['item-id'].nodeValue);

                    console.log(arrayObj['itemsArray' + k])

                    var newArr = [];
                    for (var i = 0; i < arrayObj['itemsArray' + k].length; i++) {
                        if (newArr.indexOf(arrayObj['itemsArray' + k][i]) == -1) {
                            newArr.push('' + arrayObj['itemsArray' + k][i] + '');
                        }
                    }

                    arrayObj['itemsArray' + k] = newArr;
                    var ids_row = "|" + arrayObj['itemsArray' + k].join('||') + "|";
                    $('#fields_row_' + k).val(ids_row);
                }
            });
        });


        var el_ser = document.getElementById('sections_block');
        var sortable_ser = Sortable.create(el_ser, {
            group: 'shared', // set both lists to same group
            sort: true,
            handle: '.sr-icon-move', // handle's class
            filter: '.filtered', // 'filtered' class is not draggable
            animation: 150,
            onSort: function (evt) {
                let fromSection = $(evt.from).attr('id');
                let toSection = $(evt.to).attr('id');
                let fromSectionPos = evt.oldIndex;
                let toSectionPos = evt.newIndex;
                // from
                if (fromSection === 'sections_block')
                    itemsArray.splice(fromSectionPos, 1);

                // to
                if (toSection === 'sections_block')
                    itemsArray.splice(toSectionPos, 0, evt.clone.attributes['section-id'].nodeValue);


                var newArr = [];
                for (var i = 0; i < itemsArray.length; i++) {
                    if (newArr.indexOf(itemsArray[i]) == -1) {
                        newArr.push('' + itemsArray[i] + '');
                    }
                }
                itemsArray = newArr;
                var ids_row = "|" + itemsArray.join('||') + "|";
                $('#temp_row').val(ids_row);

                console.log(ids_row);
            }
        });
    });
    <?php } ?>
</script>

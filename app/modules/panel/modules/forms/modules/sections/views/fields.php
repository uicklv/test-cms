<?php
foreach ($this->fields as $row) { ?>
    <div class="flex-start mb25" item-id="<?= $row->id ?>">
        <div class="sr-icon sr-icon-move"><i class="fa fa-sort" aria-hidden="true"></i></div>
        <div class="ml16" style="display: flex;align-items: center;">
            <a href="{URL:panel/forms/fields/edit/<?= $row->id ?>}" class="tagA"><?= $row->title . ' ' . $row->title_date ?> <span style="font-size: 12px; color: #a39e9e;">(<?= $row->type ?>)</span></a>
        </div>
        <div class="flex-btw">
            <a class="sr-icon ml10 pointer" onclick="load('panel/forms/sections/copy_field/<?= $row->id ?>', 'section_id=<?= $this->section->id ?>'); return false;"><i class="fas fa-copy"></i></a>
            <a href="/" class="sr-icon ml10" onclick="if (confirm('Are you sure you wish to delete this item? Please re-confirm this action.')) load('panel/forms/sections/remove_field/<?= $row->id ?>', 'section_id=<?= $this->section->id ?>')"><i class="fas fa-trash-alt"></i></a>
        </div>
        <script>
            // Add item to array
            itemsArray.push('<?= $row->id ?>');
        </script>
    </div>
<?php } ?>
<script>
    $(document).ready(function() {
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
                    itemsArray.splice(toSectionPos, 0, evt.clone.attributes['item-id'].nodeValue);


                var newArr = [];
                for (var i = 0; i < itemsArray.length; i++) {
                    if (newArr.indexOf(itemsArray[i]) == -1) {
                        newArr.push('' + itemsArray[i] + '');
                    }
                }
                itemsArray = newArr;
                var ids_row = "|" + itemsArray.join('||') + "|";
                $('#fields_row').val(ids_row);

                console.log(ids_row);
            }
        });
    });
</script>

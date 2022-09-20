<div class="fb-row files" id="uploaded_files_<?=$field->id?>_<?=$section->id?>">
    <label class="custom-file-upload">
        <input type="file" class="init-files" id="image<?=$field->id?>_<?=$section->id?>" multiple="multiple" name="files" accept=""
               onchange="initFile(this); load('panel/upload_image_crop/',
                   'field=images[<?=$field->id?>_<?=$section->id?>][]', 'select_image=true',
                   'preview=#uploaded_files_<?=$field->id?>_<?=$section->id?>', 'multiple=true', 'type_html=front');">

        <span class="fb-file"><i class="ico-attachment" aria-hidden="true"></i> or <span>Ctrl + V</span> to paste</span>
    </label>
    <?php if ($field->answer_images) { ?>
            <?php foreach ($field->answer_images as $k => $image) { ?>
            <div class="uploaded-file" id="image_block_<?=$field->id?>_<?=$section->id?>_<?= $k ?>">
                <a data-fancybox href="<?= _SITEDIR_ ?>data/form_builder/answers/<?= $image ?>">
                    <img id="<?=$field->id?>_<?=$section->id?>_<?= $k ?>" src="<?= _SITEDIR_ ?>data/form_builder/answers/<?= $image ?>" alt="">
                </a>
                <input type="hidden" id="hidden_<?=$field->id?>_<?=$section->id?>_<?= $k ?>" name="images[<?=$field->id?>_<?=$section->id?>][]" value="<?= $image ?>">
                <span class="btn-remove" onclick="removeFieldImage('<?= $field->id ?>_<?= $section->id ?>_<?= $k ?>')"></span>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<script>
    $(document).ready(function($){
        // Paste Ctrl + V
        document.getElementById('elem-file-<?= $field->id ?>_<?= $section->id ?>').addEventListener('paste', e => {
            if (e.clipboardData.files.length) {
                let inputFile = this.querySelector('.init-files')

                if (inputFile) {
                    inputFile.files = e.clipboardData.files;
                    load('panel/upload_image_crop/',
                        'field=images[<?=$field->id?>_<?=$section->id?>][]', 'select_image=true',
                        'preview=#uploaded_files_<?=$field->id?>_<?=$section->id?>', 'multiple=true', 'type_html=front');
                }
            }
        });
    });
</script>

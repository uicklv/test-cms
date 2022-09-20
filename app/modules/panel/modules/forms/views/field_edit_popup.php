<?php Popup::head('Edit Field'); ?>

    <form id="field_form" class="ppsec" method="post" enctype="multipart/form-data">

        <div class="form-row">
            <div class="form-group col-md-12">
                <div class="form-group col-md-6">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id="title" value="<?= post('title', false, $this->field->title); ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="type">Type</label>
                    <select class="form-control" name="type" id="type" required>
                        <option value="input" <?= checkOptionValue(post('posted'), 'input', $this->field->type) ?>>Input</option>
                        <option value="textarea" <?= checkOptionValue(post('posted'), 'textarea', $this->field->type) ?>>Textarea</option>
                        <option value="checkbox" <?= checkOptionValue(post('posted'), 'checkbox', $this->field->type) ?>>Checkbox</option>
                        <option value="radio" <?= checkOptionValue(post('posted'), 'radio', $this->field->type) ?>>Radio</option>
                        <option value="info" <?= checkOptionValue(post('posted'), 'info', $this->field->type) ?>>Info(Textarea)</option>
                    </select>
                </div>
            </div>
            <!-- INFO BLOCK -->
            <div class="form-group col-md-12" id="info_block" <?php if ($this->field->type !== 'info') { ?>style="display: none;" <?php } ?>>
                <h4>Content</h4>
                <div class="form-group col-md-12">
                    <textarea class="form-control" name="answer_options" id="answer_options" rows="20"><?= post('answer_options', false, $this->field->answer_options); ?></textarea>
                </div>
            </div>
            <!-- END INFO BLOCK -->

            <!-- OPTION BLOCK -->
            <div class="form-group col-md-12" id="answers_block" <?php if (!in_array($this->field->type, ['checkbox', 'radio'])) { ?> style="display: none;" <?php } ?>>
                <div class="form-group col-md-12 flex-end">
                    <a href="/" onclick="addNewItem();">Add Option</a>
                </div>
                <?php if ($this->field->answer_options) { ?>
                    <?php
                    $options = stringToArray($this->field->answer_options);
                    foreach ($options as $option) { ?>
                        <div class="form-group col-md-6">
                            <label>Option <i class="fa fa-trash-alt pointer" onclick="removeItem($(this));"></i></label>
                            <input class="form-control" type="text" name="options[]" value="<?= $option ?>">
                        </div>
                        <div class="form-group col-md-6">
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="form-group col-md-12">
                <a class="btn btn-success" href="" onclick="setTextareaValue(); load('panel/forms/field_edit_popup/<?= $this->field->id ?>/<?= $this->section->id ?>/<?= $this->form->id ?>', 'form:#field_form'); return false;">
                    Edit
                </a>
                <a class="btn btn-danger" onclick="closePopup();">Cancel</a>
            </div>
        </div>
    </form>
<?php Popup::foot(); ?>
<link rel="stylesheet" href="<?= _SITEDIR_; ?>public/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<script src="<?= _SITEDIR_; ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?= _SITEDIR_; ?>public/plugins/ckeditor/samples/js/sample.js"></script>
<script>
    $("#site").addClass('popup-open');

    $(document).ready(function() {
        if ($('#type').val() === 'radio' || $('#type').val() === 'checkbox') {
            $('#answers_block').css('display', 'block');
        } else {
            $('#answers_block').css('display', 'none');
        }

        if ($('#type').val() === 'info') {
            $('#info_block').css('display', 'block');
        } else {
            $('#info_block').css('display', 'none');
        }
    });

    var editorField;

    function setTextareaValue() {
        $('#answer_options').val(editorField.getData());
    }

    $(function () {

        editorField = CKEDITOR.replace('answer_options', {
            htmlEncodeOutput: false,
            wordcount: {
                showWordCount: true,
                showCharCount: true,
                countSpacesAsChars: true,
                countHTML: false,
            },
            removePlugins: 'zsuploader',

            filebrowserBrowseUrl: '<?= _SITEDIR_; ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=files',
            filebrowserImageBrowseUrl: '<?= _SITEDIR_; ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=images',
            filebrowserUploadUrl: '<?= _SITEDIR_; ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=files',
            filebrowserImageUploadUrl: '<?= _SITEDIR_; ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=images'
        });

    });

    $('#type').on('change', function () {
        if ($(this).val() === 'radio' || $(this).val() === 'checkbox') {
            $('#answers_block').css('display', 'block');
        } else {
            $('#answers_block').css('display', 'none');
        }

        if ($(this).val() === 'info') {
            $('#info_block').css('display', 'block');
        } else {
            $('#info_block').css('display', 'none');
        }
    });

    function addNewItem() {
        var html = '<div class="form-group col-md-6">' +
            '<label>Option <i class="fa fa-trash-alt pointer" onclick="removeItem($(this));"></i></label>' +
            '<input class="form-control" type="text" name="options[]" value="">' +
            '</div>' +
            '<div class="form-group col-md-6">' +
            '</div>';
        $('#answers_block').append(html);
    }

    function removeItem(item) {
        item.parent().parent().remove();
    }
</script>
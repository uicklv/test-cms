<form id="form_box" action="{URL:panel/forms/fields/edit/<?= $this->field->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-rss" href="{URL:panel/forms/fields}"></a>
                                    <h1 class="page_title"><?= $this->field->title ?></h1>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- General -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>
                    <div class="form-row">
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

                    <!-- IMAGE BLOCK -->
                    <div class="form-row" id="image_block">
                        <?php /*
                        <div class="form-group col-md-12 flex-end">
                            <a href="/" onclick="load('panel/select_image', 'field=images[]', 'multiple=1', 'preview=#images-block')" style="cursor: pointer;">Add Image</a>
                        </div>
 */ ?>
                        <div class="form-group col-md-12 flex-start " id="images-block">
                            <?php if ($this->field->images) { ?>
                                <?php foreach ($this->field->images as $image) { ?>
                                    <div id="image_block_<?= $image->id ?>">
                                        <img id="<?= $image->id ?>" src="<?= _SITEDIR_ ?>data/form_builder/<?= $image->image ?>" alt="" height="50px" class="ml-2">
                                        <input type="hidden" id="hidden_<?= $image->id ?>" name="images[]" value="<?= $image->image ?>">
                                        <span class="img_del" onclick="removeFieldImage('<?= $image->id ?>')"><span class="fa fa-times-circle-o"></span></span>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- END IMAGE BLOCK -->

                    <!-- INFO BLOCK -->
                    <div class="form-row" id="info_block" <?php if ($this->field->type !== 'info') { ?>style="display: none;" <?php } ?>>
                        <h4>Content</h4>
                        <div class="form-group col-md-12">
                            <textarea class="form-control" name="answer_options" id="answer_options" rows="20"><?= post('answer_options', false, $this->field->answer_options); ?></textarea>
                        </div>
                    </div>
                    <!-- END INFO BLOCK -->

                    <!-- OPTION BLOCK -->
                    <div class="form-row" id="answers_block" <?php if (!in_array($this->field->type, ['checkbox', 'radio'])) { ?> style="display: none;" <?php } ?>>
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
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <!-- END OPTION BLOCK -->
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/forms/fields}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/forms/fields/edit/<?= $this->field->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/js/sample.js"></script>

<!-- Connect editor -->
<script>
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
        var html = '<div class="form-group col-md-6">'+
                        '<label>Option <i class="fa fa-trash-alt pointer" onclick="removeItem($(this));"></i></label>'+
                        '<input class="form-control" type="text" name="options[]">'+
                    '</div>';
        $('#answers_block').append(html);
    }

    function removeItem(item) {
        item.parent().parent().remove();
    }
</script>
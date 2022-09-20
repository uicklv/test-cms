<form id="form_box" action="{URL:panel/resources/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-comments-o" href="{URL:panel/resources}"></a>
                                    <h1 class="page_title"><?= $this->edit->title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/resources}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input class="form-control" type="text" name="title" id="title" value="<?= post('title', false, $this->edit->title); ?>">
                            </div>

                            <div class="form-group">
                                <label for="posted">Published</label>
                                <select class="form-control" name="posted" id="posted" required>
                                    <option value="yes" <?= checkOptionValue(post('posted'), 'yes', $this->edit->posted); ?>>Yes</option>
                                    <option value="no" <?= checkOptionValue(post('posted'), 'no', $this->edit->posted); ?>>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="file">File
                                    <?= ( $this->edit->file ? '<a href="' . _SITEDIR_ . 'data/resources/'  .   $this->edit->file . '" download="' . $this->edit->file . '"><i class="fas fa-download"></i> Download ' . $this->edit->file_real_name . '</a>' : '') ?>
                                </label>
                                <div class="flex-btw flex-vc">
                                    <div class="choose-file">
                                        <input type="hidden" name="file" id="file_input" value="<?= post($this->edit->file, false,  $this->edit->file ); ?>">
                                        <input type="file" accept="doc, docx, pdf, txt, fotd" onchange="initFile(this); load('page/upload/', 'name=<?= randomHash(); ?>', 'preview=#file_preview', 'field=#file_input');">
                                        <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                    </div>
                                    <div id="file_preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <!-- Image -->
                                <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                                <div class="choose-file modern">
                                    <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->edit->image); ?>">
                                    <a class="file-fake" onclick="load('panel/select_image', 'field=#image', 'width=490', 'height=300')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                    <div id="preview_image" class="preview_image">
                                        <img src="<?= _SITEDIR_ ?>data/resources/<?= post('image', false, $this->edit->image); ?>" alt="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" name="type" id="type" required>
                                    <option value="employers" <?= checkOptionValue(post('type'), 'employers', $this->edit->type); ?>>For Employers</option>
                                    <option value="seekers" <?= checkOptionValue(post('type'), 'seekers', $this->edit->type); ?>>For Job Seekers</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="author">Publisher</label>
                                <select class="form-control" name="author" id="author" required>
                                    <?php if ($this->users) { ?>
                                        <?php foreach ($this->users as $item) { ?>
                                            <option value="<?= $item->firstname . ' ' . $item->lastname ?>" <?php if ($this->edit->author == $item->firstname . ' ' . $item->lastname) echo 'selected'; ?>><?= $item->firstname . ' ' . $item->lastname ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Content</h4>
                    <div class="form-group mb-0">
                        <textarea class="form-control" name="content" id="text_content" rows="20"><?= post('content', false, $this->edit->content); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/resources}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="setTextareaValue(); load('panel/resources/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
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
        $('#text_content').val(editorField.getData());
    }

    $(function () {
        editorField = CKEDITOR.replace('text_content', {
            htmlEncodeOutput: false,
            wordcount: {
                showWordCount: true,
                showCharCount: true,
                countSpacesAsChars: true,
                countHTML: true,
            },
            // enterMode: CKEDITOR.ENTER_BR, // to remove <p>
            // removePlugins: 'zsuploader',

            filebrowserBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=files',
            filebrowserImageBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=images',
            filebrowserUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=files',
            filebrowserImageUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=images'
        });
    });
</script>
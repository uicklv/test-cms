<form id="form_box" action="{URL:panel/learning_development/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-graduation-cap" href="{URL:panel/learning_development}"></a>
                                    <h1 class="page_title"><?= $this->edit->title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/learning_development}"><i class="fas fa-reply"></i>Back</a>
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
                                <label for="category">Category</label>
                                <select class="form-control" name="category" id="category" required>
                                    <?php if (isset($this->sectors) && is_array($this->sectors) && count($this->sectors) > 0) { ?>
                                        <?php foreach ($this->sectors as $item) { ?>
                                            <option value="<?= $item->id; ?>" <?= checkOptionValue(post('category'), $item->id, $this->edit->category); ?>><?= $item->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="image">Image 590x390 px<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->edit->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image', 'width=590', 'height=390')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/learning_development/<?= post('image', false, $this->edit->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="time">Date Posted</label>
                            <input type="text" class="form-control" name="time" id="time" value="<?= post('time', false, date("d/m/Y", $this->edit->time)); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Posted</label>
                            <select class="form-control" name="posted" id="posted" required>
                                <option value="yes" <?= checkOptionValue(post('posted'), 'yes', $this->edit->posted); ?>>Yes</option>
                                <option value="no" <?= checkOptionValue(post('posted'), 'no', $this->edit->posted); ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="video">Video Link</label>
                            <input type="text" class="form-control" name="video" id="video" value="<?= post('video', false, $this->edit->video); ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <!-- File -->
                            <label for="file">File
                                <?= ($this->edit->file ? '<a href="'. _SITEDIR_ .'data/learning_development/' . $this->edit->file . '" download="file.' . File::format($this->edit->file ) . '"><i class="fas fa-download"></i> Download</a>' : '') ?>
                            </label>
                            <div class="flex-btw flex-vc" id="download">
                                <div class="choose-file">
                                    <input type="hidden" name="file" id="file" value="<?= post('file', false, $this->edit->file); ?>">
                                    <input type="file"  onchange="initFile(this); load('panel/upload/', 'name=<?= randomHash(); ?>', 'preview=#file_name', 'field=#file');">
                                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                </div>
                                <div id="file_name"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Deny access</label>
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->users) && is_array($this->users) && count($this->users) > 0) { ?>
                                    <?php foreach ($this->users as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="users[]" id="location_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('users'), $item->id, $this->edit->users_ids); ?>
                                            ><label class="custom-control-label" for="location_<?=$item->id?>"><?= $item->firstname . ' ' . $item->lastname; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->

            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Content</h4>

                    <div class="form-group">
                        <textarea class="form-control" name="description" id="description" rows="20"><?= post('content', false, $this->edit->content); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/learning_development}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/learning_development/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
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
<script>
    var editorField;

    function setTextareaValue() {
        $('#description').val(editorField.getData());
    }

    $(function () {
        $('#time').datepicker({dateFormat: 'dd/mm/yy'});

        initSlug('#slug', '#title');

        $("#title").keyup(function () {
            initSlug('#slug', '#title');
        });

        editorField = CKEDITOR.replace('description', {
            htmlEncodeOutput: false,
            wordcount: {
                showWordCount: true,
                showCharCount: true,
                countSpacesAsChars: true,
                countHTML: false,
            },
            removePlugins: 'zsuploader',

            filebrowserBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=files',
            filebrowserImageBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=images',
            filebrowserUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=files',
            filebrowserImageUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=images'
        });

    });
</script>


<div id="control-container">
    <div id="button-holder">
        <a href="{URL:panel/testimonials}" onclick="load('panel/testimonials');" class="btn add"><i class="fas fa-ban"></i>Cancel</a>
        <div class="clr"></div>
    </div>
    <h1>
        <i class="fas fa-users"></i>Testimonials <i class="fas fa-caret-right"></i>Edit
    </h1>
    <hr/>

    <?php if (isset($success) && $success) { ?>
        <div class="success">
            <i class="fas fa-check-circle"></i><?= $success; ?>
        </div>
    <?php } ?>
    <?php if (isset($error) && $error) { ?>
        <div class="error">
            <i class="fas fa-check-circle"></i><?= $error; ?>
        </div>
    <?php } ?>
    <?php //echo validation_errors('<div class="error"><i class="fas fa-check-circle"></i>', '</div>'); ?>


    <form id="form_box" action="{URL:panel/testimonials/edit/<?= $this->testimonial->id; ?>}" method="post" enctype="multipart/form-data">
        <div class="form-section">
            <span class="heading">General</span>

            <div class="col half_column_left">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?= post('name', false, $this->testimonial->name); ?>">

                <div class="col">
                    <label for="name">Position</label>
                    <input type="text" name="position" id="position" value="<?= post('position', false, $this->testimonial->position); ?>">
                </div>
            </div>

            <!-- Image -->
            <div class="col half_column_right">
                <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                <div class="choose-file modern">
                    <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->testimonial->image); ?>">
                    <input type="file" accept="image/jpeg,image/png,image/gif" onchange="initFile(this); load('panel/upload_image/', 'name=<?= User::get('id'); ?>', 'field=#image');">
                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose image</a>
                    <div id="preview_image" class="preview_image">
                        <img src="<?= _SITEDIR_ ?>data/testimonials/<?= post('image', false, $this->testimonial->image); ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="clr"></div>

            <div class="col full_column">
                <label for="user_image">Or choose consultant image</label>
                <select name="user_image" id="user_image" required>
                    <option value="0">- Choose consultant -</option>
                    <?php if (isset($this->team) && is_array($this->team) && count($this->team) > 0) { ?>
                        <?php foreach ($this->team as $member) { ?>
                            <option value="<?= $member->id; ?>" <?= checkOptionValue(post('user_image'), $member->id, $this->testimonial->user_image); ?>><?= $member->firstname . ' ' . $member->lastname; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>

            <div class="col full_column">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="20"><?= post('content', false, $this->testimonial->content); ?></textarea>
            </div>
            <div class="clr"></div>
        </div>

        <div class="form-section">
            <button type="submit" name="submit" class="btn submit" onclick="setTextareaValue(); load('panel/testimonials/edit/<?= $this->testimonial->id; ?>', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
            <a href="{URL:panel/testimonials}" onclick="load('panel/testimonials');" class="btn cancel"><i class="fas fa-ban"></i>Cancel</a>
            <div class="clr"></div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/js/sample.js"></script>
<script>
    var editorField;

    function setTextareaValue() {
        $('#content').val(editorField.getData());
    }

    $(function () {
        initSlug('#slug', '#name');

        $("#name").keyup(function () {
            initSlug('#slug', '#name');
        });

        editorField = CKEDITOR.replace('content', {
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
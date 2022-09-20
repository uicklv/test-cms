<form id="form_box" action="{URL:panel/candidates/edit/<?= $this->user->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-user-tie" href="{URL:panel/candidates}"></a>
                                    <h1 class="page_title"><?= $this->user->firstname . ' ' . $this->user->lastname; ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                    <?php if ($this->user->cv) { ?>
                                        <a class="btn-social copy_btn bs-tooltip" href="<?= _SITEDIR_ ?>data/cvs/<?= $this->user->cv ?>" target="_blank" title="Download CV"
                                           download="<?= $this->user->name ?>.<?= File::format($this->user->cv) ?>">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    <?php } ?>
                                </div>
                                <a class="btn btn-outline-warning" href="{URL:panel/candidates}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>
                        <div class="items_group items_group-wrap items_group-bottom">
                            <div class="items_left-side">
                                <div class="option-btns-list scroll-list">
                                    <a class="btn btn-rectangle_medium active"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                    <a href="{URL:panel/candidates/applications/<?= $this->user->id; ?>}" class="btn btn-rectangle_medium" title="Applications List"><i class="bs-tooltip far fa-user"></i>Applications</a>
                                </div>
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
                            <label for="firstname">First Name</label>
                            <input class="form-control" type="text" name="firstname" id="firstname" value="<?= post('firstname', false, $this->user->firstname); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input class="form-control" type="text" name="lastname" id="lastname" value="<?= post('lastname', false, $this->user->lastname); ?>"><!--required-->
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" value="<?= post('email', false, $this->user->email); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">Password (Leave Empty If Do Not Want to Change)</label>
                            <input class="form-control" type="password" name="password" id="password" value="<?= post('password', false); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input class="form-control" type="text" name="slug" id="slug" value="<?= post('slug', false, $this->user->slug); ?>">
                            </div>
                        </div>

                        <div class="form-group col-md-6 mb-0">
                            <!-- Image -->
                            <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->user->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'path=tmp', 'field=#image')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/candidates/<?= post('image', false, $this->user->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Details -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Details</h4>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="title">Title</label>
                            <input class="form-control" type="text" name="title" id="title" value="<?= post('title', false, $this->user->title); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tel">Telephone Number</label>
                            <input class="form-control" type="tel" name="tel" id="tel" value="<?= post('tel', false, $this->user->tel); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="linkedin">LinkedIn URL</label>
                            <input class="form-control" type="text" name="linkedin" id="linkedin" value="<?= post('linkedin', false, $this->user->linkedin); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="linkedin">Twitter URL</label>
                            <input class="form-control" type="text" name="twitter" id="twitter" value="<?= post('twitter', false, $this->user->twitter); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="linkedin">Skype</label>
                            <input class="form-control" type="text" name="skype" id="skype" value="<?= post('skype', false, $this->user->skype); ?>">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="20"><?= post('description', false, $this->user->description); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/candidates}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/candidates/edit/<?= $this->user->id; ?>', 'form:#form_box'); return false;">
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
        $('#description').val(editorField.getData());
    }

    $(function () {
        $("#firstname, #lastname").keyup(function () {
            initSlug('#slug', '#firstname,#lastname');
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
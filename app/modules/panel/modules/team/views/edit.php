<form id="form_box" action="{URL:panel/team/edit/<?= $this->user->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-user-friends" href="{URL:panel/team}"></a>
                                    <h1 class="page_title"><?= $this->user->firstname . ' ' . $this->user->lastname ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side">
                                <div class="items_small-block">
                                    <a href="{URL:about-us/profile/<?= $this->user->slug; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" title="View Member" target="_blank"></a>

                                    <div class="social-btns-list">
                                        <a onclick="share_linkedin(this);" class="btn-social" href="#" data-url="{URL:about-us/profile/<?= $this->user->slug; ?>}">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                        <a onclick="share_facebook(this);" class="btn-social" href="#" data-url="{URL:about-us/profile/<?= $this->user->slug; ?>}">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                        <a onclick="share_twitter(this);" class="btn-social" href="#" data-url="{URL:about-us/profile/<?= $this->user->slug; ?>}">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                        <a class="btn-social copy_btn" href="#" data-clipboard-text="{URL:about-us/profile/<?= $this->user->slug; ?>}">
                                            <i class="fa fa-copy"></i>
                                        </a>
                                    </div>
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/team}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                        <div class="items_group items_group-wrap items_group-bottom">
                            <div class="items_left-side">
                                <div class="option-btns-list scroll-list">
                                    <a class="btn btn-rectangle_medium active"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                    <a href="{URL:panel/team/vacancies/<?= $this->user->id; ?>}" class="btn btn-rectangle_medium" title="Applications List"><i class="bs-tooltip fas fa-briefcase"></i>Vacancies</a>
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

                    <div class="form-row-flex">
                        <div class="form-left-side">
                            <!-- Image -->
                            <div class="choose-file modern choose-file-blured">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->user->image); ?>">

                                <div class="form-image-block">
                                    <div id="preview_image" class="preview_image">
                                        <img src="<?= _SITEDIR_ ?>data/users/<?= post('image', false, $this->user->image); ?>" alt="">
                                    </div>
                                    <a class="file-fake" onclick="load('panel/select_image', 'field=#image', 'width=300', 'height=300')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                </div>
                            </div>

                            <label class="choose-file-label" for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                        </div>

                        <div class="form-right-side">
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
                                <div class="form-group col-md-6 form-group-flex">
                                    <label for="email">Email</label>
                                    <input class="form-control" type="email" name="email" id="email" value="<?= post('email', false, $this->user->email); ?>"><!--required-->
                                </div>

                                <div class="form-group col-md-6 form-group-flex">
                                    <label for="password">Password (Leave Empty If Do Not Want to Change)</label>
                                    <input class="form-control" type="password" name="password" id="password" value="<?= post('password', false); ?>">
                                </div>

                                <?php if (Request::getParam('tracker') == 'yes') { ?>
                                    <div class="form-group col-md-6">
                                        <label for="email">Auto-login link: (<i class="fa fa-eye"></i>Visible in test mode only!)</label>
                                        <div class="input-group">
                                            <input class="form-control" id="copy_url" value="<?= SITE_URL ?>panel/login?e=<?= $this->user->email ?>&p=<?= $this->user->password ?>" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-warning" type="button" onclick="copyToClipboard('copy_url');">Copy</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control" name="role" id="role" required>
                                            <option value="admin"  <?= checkOptionValue(post('role'), 'admin', $this->user->role); ?>>Admin</option>
                                            <option value="moder"  <?= checkOptionValue(post('role'), 'moder', $this->user->role); ?>>Consultant</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input class="form-control" type="text" name="slug" id="slug" value="<?= post('slug', false, $this->user->slug); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 mb-0">
                                    <label for="name">Is visible on site?</label>
                                    <div class="custom-control custom-checkbox checkbox-info">
                                        <input type="checkbox" class="custom-control-input" name="display_team" id="display_team"  value="yes" <?php if ($this->user->display_team === 'yes') echo 'checked'?>>
                                        <label class="custom-control-label" for="display_team">Display on team page</label>
                                    </div>
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
                        <div class="form-group col-md-6">
                            <label for="title">Job Title</label>
                            <input class="form-control" type="text" name="job_title" id="job_title" value="<?= post('job_title', false, $this->user->job_title); ?>">
                        </div>
                        <div class="form-group col-md-6">
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
                            <label for="linkedin">Skype</label>
                            <input class="form-control" type="text" name="skype" id="skype" value="<?= post('skype', false, $this->user->skype); ?>">
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="linkedin">LinkedIn URL</label>
                            <input class="form-control" type="text" name="linkedin" id="linkedin" value="<?= post('linkedin', false, $this->user->linkedin); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="linkedin">Twitter URL</label>
                            <input class="form-control" type="text" name="twitter" id="twitter" value="<?= post('twitter', false, $this->user->twitter); ?>">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="20"><?= post('description', false, $this->user->description); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>On-page SEO</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="meta_title">
                                Meta Title<a href="https://moz.com/learn/seo/title-tag" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_title" id="meta_title" value="<?= post('meta_title', false, $this->user->meta_title); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="meta_keywords">
                                Meta Keywords<a href="https://moz.com/learn/seo/what-are-keywords" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" value="<?= post('meta_keywords', false, $this->user->meta_keywords); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12 mb-0">
                            <label for="meta_desc">
                                Meta Description<a href="https://moz.com/learn/seo/meta-description" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_desc" id="meta_desc" value="<?= post('meta_desc', false, $this->user->meta_desc); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/team}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/team/edit/<?= $this->user->id; ?>', 'form:#form_box'); return false;">
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
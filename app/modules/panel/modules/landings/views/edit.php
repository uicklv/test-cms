<link rel="stylesheet" href="<?= _SITEDIR_ ?>public/css/backend/jquery.tagit.css" />
<link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<script src="<?= _SITEDIR_ ?>public/js/backend/Sortable.min.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/js/sample.js"></script>
<script src="<?= _SITEDIR_ ?>public/js/backend/tag-it.js"></script>
<script>
    function setTextareaValue(id) {
        let blName = eval('editorField' + id);
        $('#content__' + id).val(blName.getData());
    }

    function destroyCkeditor(name) {
        CKEDITOR.instances[name].destroy();
    }

    function addHtmlEditor(id) {
        return CKEDITOR.replace('content__' + id, {
            enterMode: CKEDITOR.ENTER_BR,
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
    }
</script>

    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-briefcase" href="{URL:panel/landings}"></a>
                                    <h1 class="page_title"><?= $this->edit->title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side">
                                <div class="items_small-block">
                                    <a href="{URL:landing/<?= $this->edit->ref; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" title="View Page" target="_blank"></a>
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/landings}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <form id="form_box" class="lg_em" action="{URL:panel/landings/edit/<?= $this->edit->id ?>}" method="post" enctype="multipart/form-data">

                <!-- General Info -->
                <div class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <h4>General Info</h4>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-0">
                                <label for="title">Title</label>
                                <input class="form-control" type="text" name="title" id="title" value="<?= post('title', false, $this->edit->title); ?>" required>
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label for="ref">Ref (Only numbers, letters and dash accepted)</label>
                                <input class="form-control" type="text" name="ref" id="ref" value="<?= post('ref', false, $this->edit->ref); ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <h4>On-page SEO</h4>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-group">
                                    <label for="meta_title">
                                        Meta Title
                                        <a href="https://moz.com/learn/seo/title-tag" target="_blank"><i class="fas fa-info-circle"></i></a>
                                    </label>
                                    <input class="form-control" type="text" name="meta_title" id="meta_title" value="<?= post('meta_title', false, $this->edit->meta_title ?: $this->edit->title); ?>">
                                </div>
                                <div class="form-group ">
                                    <label for="meta_keywords">
                                        Meta Keywords
                                        <a href="https://moz.com/learn/seo/what-are-keywords"
                                           target="_blank"><i class="fas fa-info-circle"></i></a>
                                    </label>
                                    <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" value="<?= post('meta_keywords', false, $this->edit->meta_keywords); ?>">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Open Graph Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                <div class="choose-file modern">
                                    <input type="hidden" name="og_image" id="og_image" value="<?= post('og_image', false, $this->edit->og_image); ?>">
                                    <a class="file-fake" onclick="load('panel/select_image', 'path=tmp', 'field=#og_image', 'preview=#pre_og_image')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                    <div id="pre_og_image" class="preview_image">
                                        <img src="<?= _SITEDIR_ ?>data/landings/<?= post('og_image', false, $this->edit->og_image); ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12 mb-0">
                                <label for="meta_desc">
                                    Meta Description
                                    <a href="https://moz.com/learn/seo/meta-description" target="_blank"><i class="fas fa-info-circle"></i></a>
                                </label>
                                <input class="form-control" type="text" name="meta_desc" id="meta_desc" value="<?= post('meta_desc', false, $this->edit->meta_desc); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- section row -->
                <input type="hidden" name="section_row" id="section_row" value="<?= post('section_row', false, $this->edit->section_row); ?>">
            </form>

            <div id="flFormsGrid" class="col-lg-12 layout-spacing">

                <div class="statbox widget box box-shadow">
                    <div class="heading-flex">
                        <h3 class="hf-title">Sections <span>(Drag & drop to sort)</span></h3>
                        <button class="btn btn-dark" onclick="load('panel/landings/add_section/<?= $this->edit->id ?>'); return false;">Add Section</button>
                    </div>
                    <script>
                        var itemsArray = []; // itemsArray
                        var itemsArrayTextAreas = []; // itemsArrays
                    </script>
                    <div id="sections_block">
                        <?php
                        $orderArr = explode('||', trim($this->edit->section_row, '|'));

                        foreach ($this->sections as $k => $v) {
                            if (!in_array($k, $orderArr))
                                $orderArr[] = $k;
                        }

                        foreach ($orderArr as $vid) {
                            $row = $this->sections[$vid];
                            if (!$row) continue;

                            $img = landingImage($row->type);
                            ?>
                            <div class="section-row-wrap" item-id="<?= $row->id ?>">
                                <div class="sr-icon-move"><i class="fa fa-sort" aria-hidden="true"></i></div>
                                <div class="section-row">
                                    <div class="sr-head">
                                        <div class="sr-icon"><img
                                                    src="<?= _SITEDIR_ ?>/public/images/icon/section_icon/<?= $img?>"
                                                    alt=""></div>
                                        <?= $row->name ?>
<!--                                        <input type="text" name="name" value="" onclick="return false;">-->
                                        <span class="sr-arrow"><i class="fas fa-chevron-down"></i></span>
                                    </div>
                                    <div class="sr-cont">
                                        <form id="form_<?= $row->id ?>">
                                            <?php if ($row->type == 'home') { ?>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <div class="form-group">
                                                        <label for="content1_<?= $row->id ?>">Title</label>
                                                        <input class="form-control" type="text" name="content1" id="content1_<?= $row->id ?>" value="<?= post('content1', false, $row->content1); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="content2_<?= $row->id ?>">Sub Title</label>
                                                        <input class="form-control" type="text" name="content2" id="content2_<?= $row->id ?>" value="<?= post('content2', false, $row->content2); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <!-- Image -->
                                                    <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                    <div class="choose-file modern">
                                                        <input type="hidden" name="content3" id="content3_<?= $row->id ?>" value="<?= post('content3', false, $row->content3); ?>">
                                                        <a class="file-fake" onclick="load('panel/select_image', 'field=#content3_<?= $row->id ?>', 'width=600', 'height=400', 'preview=#preview_<?= $row->id ?>')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                        <div id="preview_<?= $row->id ?>" class="preview_image">
                                                            <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content3', false, $row->content3); ?>" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } else if ($row->type == 'text') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content</label>
                                                            <textarea class="form-control" type="text" name="content1" id="content__<?= $row->id ?>" required><?= $row->content1; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Set html editor
                                                    var editorField<?= $row->id ?>;

                                                    //mandatory function for textarea initialization
                                                    function initText<?= $row->id ?>() {
                                                        if (editorField<?= $row->id ?>) {
                                                            //here we pass the id of the text area to be deleted
                                                            destroyCkeditor('content__<?= $row->id ?>');
                                                        }

                                                        //and re-initialize
                                                        editorField<?= $row->id ?> = addHtmlEditor('<?= $row->id ?>');
                                                    }

                                                    initText<?= $row->id ?>();
                                                </script>
                                            <?php } else if ($row->type == 'picture_text') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <div class="form-group">
                                                            <label>Options</label>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio radio-classic-warning">
                                                                    <input type="radio" class="new-control-input" name="options" value="left_text"
                                                                        <?php if ($row->options === 'left_text') echo 'checked' ?>>
                                                                    <span class="new-control-indicator"></span>Left Text & Right Image
                                                                </label>
                                                            </div>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio radio-classic-warning">
                                                                    <input type="radio" class="new-control-input" name="options" value="right_text"
                                                                        <?php if ($row->options === 'right_text') echo 'checked' ?>>
                                                                    <span class="new-control-indicator"></span>Right Text & Left Image
                                                                </label>
                                                            </div>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio radio-classic-warning">
                                                                    <input type="radio" class="new-control-input" name="options" value="on_image"
                                                                        <?php if ($row->options === 'on_image') echo 'checked' ?>>
                                                                    <span class="new-control-indicator"></span>Text on Image
                                                                </label>
                                                            </div>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio radio-classic-warning">
                                                                    <input type="radio" class="new-control-input" name="options" value="below_image"
                                                                        <?php if ($row->options === 'below_image') echo 'checked' ?>>
                                                                    <span class="new-control-indicator"></span>Text below of image
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content</label>
                                                            <textarea class="form-control" type="text" name="content1" id="content__<?= $row->id ?>" required><?= $row->content1; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content3" id="content3_<?= $row->id ?>" value="<?= post('content3', false, $row->content3); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content3_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content3', false, $row->content3); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Set html editor
                                                    var editorField<?= $row->id ?>;

                                                    //mandatory function for textarea initialization
                                                    function initText<?= $row->id ?>() {
                                                        if (editorField<?= $row->id ?>) {
                                                            //here we pass the id of the text area to be deleted
                                                            destroyCkeditor('content__<?= $row->id ?>');
                                                        }

                                                        //and re-initialize
                                                        editorField<?= $row->id ?> = addHtmlEditor('<?= $row->id ?>');
                                                    }

                                                    initText<?= $row->id ?>();
                                                </script>
                                            <?php } else if ($row->type == 'video') { ?>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <div class="form-group">
                                                        <label for="content__<?= $row->id ?>">Video Link</label>
                                                        <input class="form-control" type="text" name="content1" id="content1_<?= $row->id ?>" value="<?= post('content1', false, $row->content1); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <!-- Image -->
                                                    <label for="image">Preview Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                    <div class="choose-file modern">
                                                        <input type="hidden" name="content3" id="content3_<?= $row->id ?>" value="<?= post('content3', false, $row->content3); ?>">
                                                        <a class="file-fake" onclick="load('panel/select_image', 'field=#content3_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                        <div id="preview_<?= $row->id ?>" class="preview_image">
                                                            <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content3', false, $row->content3); ?>" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } else if ($row->type == 'video_text') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <div class="form-group">
                                                            <label>Options</label>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio radio-classic-warning">
                                                                    <input type="radio" class="new-control-input" name="options" value="left_text"
                                                                        <?php if ($row->options === 'left_text') echo 'checked' ?>>
                                                                    <span class="new-control-indicator"></span>Left Text & Right Video
                                                                </label>
                                                            </div>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio radio-classic-warning">
                                                                    <input type="radio" class="new-control-input" name="options" value="right_text"
                                                                        <?php if ($row->options === 'right_text') echo 'checked' ?>>
                                                                    <span class="new-control-indicator"></span>Right Text & Left Video
                                                                </label>
                                                            </div>
                                                            <div class="n-chk">
                                                                <label class="new-control new-radio radio-classic-warning">
                                                                    <input type="radio" class="new-control-input" name="options" value="below_video"
                                                                        <?php if ($row->options === 'below_video') echo 'checked' ?>>
                                                                    <span class="new-control-indicator"></span>Text below of video
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <!-- Image -->
                                                            <label for="image">Preview Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                            <div class="choose-file modern">
                                                                <input type="hidden" name="content3" id="content3_<?= $row->id ?>" value="<?= post('content3', false, $row->content3); ?>">
                                                                <a class="file-fake" onclick="load('panel/select_image', 'field=#content3_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                                <div id="preview_<?= $row->id ?>" class="preview_image">
                                                                    <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content3', false, $row->content3); ?>" alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label for="content__<?= $row->id ?>">Video Link</label>
                                                                <input class="form-control" type="text" name="content1" id="content1_<?= $row->id ?>" value="<?= post('content1', false, $row->content1); ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content</label>
                                                            <textarea class="form-control" type="text" name="content2" id="content__<?= $row->id ?>" required><?= $row->content2; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Set html editor
                                                    var editorField<?= $row->id ?>;

                                                    //mandatory function for textarea initialization
                                                    function initText<?= $row->id ?>() {
                                                        if (editorField<?= $row->id ?>) {
                                                            //here we pass the id of the text area to be deleted
                                                            destroyCkeditor('content__<?= $row->id ?>');
                                                        }

                                                        //and re-initialize
                                                        editorField<?= $row->id ?> = addHtmlEditor('<?= $row->id ?>');
                                                    }

                                                    initText<?= $row->id ?>();
                                                </script>
                                                <?php } else if ($row->type == '2_blocks') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 1<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content3" id="content3_<?= $row->id ?>" value="<?= post('content3', false, $row->content3); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content3_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content3', false, $row->content3); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 2<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content4" id="content4_<?= $row->id ?>" value="<?= post('content4', false, $row->content4); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content4_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>_2')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>_2" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content4', false, $row->content4); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 1</label>
                                                            <textarea class="form-control" type="text" name="content1" id="content__<?= $row->id ?>_1" required><?= $row->content1; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 2</label>
                                                            <textarea class="form-control" type="text" name="content2" id="content__<?= $row->id ?>_2" required><?= $row->content2; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Set html editor
                                                    var editorField<?= $row->id ?>_1;
                                                    var editorField<?= $row->id ?>_2;

                                                    //mandatory function for textarea initialization
                                                    function initText<?= $row->id ?>() {
                                                        if (editorField<?= $row->id ?>_1 && editorField<?= $row->id ?>_2) {
                                                            //here we pass the id of the text area to be deleted
                                                            destroyCkeditor('content__<?= $row->id ?>_1');
                                                            destroyCkeditor('content__<?= $row->id ?>_2');
                                                        }

                                                        //and re-initialize
                                                        editorField<?= $row->id ?>_1 = addHtmlEditor('<?= $row->id ?>_1');
                                                        editorField<?= $row->id ?>_2 = addHtmlEditor('<?= $row->id ?>_2');
                                                    }

                                                    initText<?= $row->id ?>();
                                                </script>
                                            <?php } else if ($row->type == '3_blocks') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 1<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content4" id="content4_<?= $row->id ?>" value="<?= post('content4', false, $row->content4); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content4_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>_2')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>_2" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content4', false, $row->content4); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 2<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content5" id="content5_<?= $row->id ?>" value="<?= post('content5', false, $row->content5); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content5_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>_3')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>_3" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content5', false, $row->content5); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 3<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content6" id="content6_<?= $row->id ?>" value="<?= post('content6', false, $row->content6); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content6_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>_4')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>_4" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content6', false, $row->content6); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 1</label>
                                                            <textarea class="form-control" type="text" name="content1" id="content__<?= $row->id ?>_1" required><?= $row->content1; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 2</label>
                                                            <textarea class="form-control" type="text" name="content2" id="content__<?= $row->id ?>_2" required><?= $row->content2; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 3</label>
                                                            <textarea class="form-control" type="text" name="content3" id="content__<?= $row->id ?>_3" required><?= $row->content3; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Set html editor
                                                    var editorField<?= $row->id ?>_1;
                                                    var editorField<?= $row->id ?>_2;
                                                    var editorField<?= $row->id ?>_3;

                                                    //mandatory function for textarea initialization
                                                    function initText<?= $row->id ?>() {
                                                        if (editorField<?= $row->id ?>_1 && editorField<?= $row->id ?>_2 && editorField<?= $row->id ?>_3) {
                                                            //here we pass the id of the text area to be deleted
                                                            destroyCkeditor('content__<?= $row->id ?>_1');
                                                            destroyCkeditor('content__<?= $row->id ?>_2');
                                                            destroyCkeditor('content__<?= $row->id ?>_3');
                                                        }

                                                        //and re-initialize
                                                        editorField<?= $row->id ?>_1 = addHtmlEditor('<?= $row->id ?>_1');
                                                        editorField<?= $row->id ?>_2 = addHtmlEditor('<?= $row->id ?>_2');
                                                        editorField<?= $row->id ?>_3 = addHtmlEditor('<?= $row->id ?>_3');
                                                    }

                                                    initText<?= $row->id ?>();
                                                </script>
                                            <?php } else if ($row->type == '4_blocks') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 1</label>
                                                            <textarea class="form-control" type="text" name="content1" id="content__<?= $row->id ?>_1" required><?= $row->content1; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 2</label>
                                                            <textarea class="form-control" type="text" name="content2" id="content__<?= $row->id ?>_2" required><?= $row->content2; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 3</label>
                                                            <textarea class="form-control" type="text" name="content3" id="content__<?= $row->id ?>_3" required><?= $row->content3; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 4</label>
                                                            <textarea class="form-control" type="text" name="content4" id="content__<?= $row->id ?>_4" required><?= $row->content4; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Set html editor
                                                    var editorField<?= $row->id ?>_1;
                                                    var editorField<?= $row->id ?>_2;
                                                    var editorField<?= $row->id ?>_3;
                                                    var editorField<?= $row->id ?>_4;

                                                    //mandatory function for textarea initialization
                                                    function initText<?= $row->id ?>() {
                                                        if (editorField<?= $row->id ?>_1 &&
                                                            editorField<?= $row->id ?>_2 &&
                                                            editorField<?= $row->id ?>_3 &&
                                                            editorField<?= $row->id ?>_4) {
                                                            //here we pass the id of the text area to be deleted
                                                            destroyCkeditor('content__<?= $row->id ?>_1');
                                                            destroyCkeditor('content__<?= $row->id ?>_2');
                                                            destroyCkeditor('content__<?= $row->id ?>_3');
                                                            destroyCkeditor('content__<?= $row->id ?>_4');
                                                        }

                                                        //and re-initialize
                                                        editorField<?= $row->id ?>_1 = addHtmlEditor('<?= $row->id ?>_1');
                                                        editorField<?= $row->id ?>_2 = addHtmlEditor('<?= $row->id ?>_2');
                                                        editorField<?= $row->id ?>_3 = addHtmlEditor('<?= $row->id ?>_3');
                                                        editorField<?= $row->id ?>_4 = addHtmlEditor('<?= $row->id ?>_4');
                                                    }

                                                    initText<?= $row->id ?>();
                                                </script>
                                            <?php } else if ($row->type == 'contact_us') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Form Title</label>
                                                            <input class="form-control" type="text" name="content1" id="content1_<?= $row->id ?>" value="<?= post('content1', false, $row->content1); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Email</label>
                                                            <input class="form-control" type="text" name="content2" id="content2_<?= $row->id ?>" value="<?= post('content2', false, $row->content2); ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else if ($row->type == 'how_it_work') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 1<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content4" id="content4_<?= $row->id ?>" value="<?= post('content4', false, $row->content4); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content4_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>_2')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>_2" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content4', false, $row->content4); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 2<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content5" id="content5_<?= $row->id ?>" value="<?= post('content5', false, $row->content5); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content5_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>_3')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>_3" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content5', false, $row->content5); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <!-- Image -->
                                                        <label for="image">Image 3<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                                        <div class="choose-file modern">
                                                            <input type="hidden" name="content6" id="content6_<?= $row->id ?>" value="<?= post('content6', false, $row->content6); ?>">
                                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#content6_<?= $row->id ?>', 'preview=#preview_<?= $row->id ?>_4')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                                            <div id="preview_<?= $row->id ?>_4" class="preview_image">
                                                                <img src="<?= _SITEDIR_ ?>data/landings/<?= post('content6', false, $row->content6); ?>" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 1</label>
                                                            <textarea class="form-control" type="text" name="content1" id="content__<?= $row->id ?>_1" required><?= $row->content1; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 2</label>
                                                            <textarea class="form-control" type="text" name="content2" id="content__<?= $row->id ?>_2" required><?= $row->content2; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-group">
                                                            <label for="content__<?= $row->id ?>">Content 3</label>
                                                            <textarea class="form-control" type="text" name="content3" id="content__<?= $row->id ?>_3" required><?= $row->content3; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    // Set html editor
                                                    var editorField<?= $row->id ?>_1;
                                                    var editorField<?= $row->id ?>_2;
                                                    var editorField<?= $row->id ?>_3;

                                                    //mandatory function for textarea initialization
                                                    function initText<?= $row->id ?>() {
                                                        if (editorField<?= $row->id ?>_1 &&
                                                            editorField<?= $row->id ?>_2 &&
                                                            editorField<?= $row->id ?>_3) {
                                                            //here we pass the id of the text area to be deleted
                                                            destroyCkeditor('content__<?= $row->id ?>_1');
                                                            destroyCkeditor('content__<?= $row->id ?>_2');
                                                            destroyCkeditor('content__<?= $row->id ?>_3');
                                                        }

                                                        //and re-initialize
                                                        editorField<?= $row->id ?>_1 = addHtmlEditor('<?= $row->id ?>_1');
                                                        editorField<?= $row->id ?>_2 = addHtmlEditor('<?= $row->id ?>_2');
                                                        editorField<?= $row->id ?>_3 = addHtmlEditor('<?= $row->id ?>_3');
                                                    }

                                                    initText<?= $row->id ?>();
                                                </script>
                                            <?php } else if ($row->type == 'map') { ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="address">
                                                            Address
                                                            <small>(Start typing to find exact location on map. Drag pin to adjust)</small>
                                                        </label>
                                                        <input class="form-control" type="text" name="content1" required id="address" value="<?= post('content1', false, $row->content1); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="address">
                                                            Coordinates
                                                            <small>(This field will be auto populated with coordinates of pin from map)
                                                            </small>
                                                        </label>
                                                        <input class="form-control" type="text" name="content2" id="coordinates" value="<?= post('content2', false, $row->content2); ?>"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col full_column">
                                                        <div id="map" style="height: 300px; width: 100%"></div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </form>
                                        <a class="btn btn-outline-primary" onclick="<?= landingEditor($row) ?> load('panel/landings/edit_section/<?= $row->id; ?>', 'form:#form_<?= $row->id ?>'); return false;">Save</a>
                                        <a class="btn btn-outline-danger delete-section" href="{URL:panel/landings/section_delete/<?= $row->id; ?>}" data-confirm="Are you sure to delete this section?">Delete Section</a>
                                    </div>
                                </div>
                                <script>
                                    // Add item to array
                                    itemsArray.push('<?= $row->id ?>');
                                    itemsArrayTextAreas['<?= $row->id ?>'] = '<?= $row->type ?>';
                                </script>
                            </div>
                        <?php } ?>
                    </div>

                    <script>
                        $(".sr-head").click(function (){
                            $(this).parent().toggleClass('active');
                            $(this).next().slideToggle(300);
                        });
                    </script>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/landings}"><i class="fas fa-reply"></i>Back</a>
                        <button type="submit" name="submit" class="btn btn-success" onclick="
                                processRows(); load('panel/landings/edit/<?= $this->edit->id ?>', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $this->maps_api ?>&callback=init_geocoder"></script>
<script>
    function processRows() {
        $.each(itemsArray, function( index, value ) {
            landingEditor(itemsArrayTextAreas[value], value);
            load('panel/landings/edit_section_process/'+value, 'form:#form_'+value);
        });
    }

    function landingEditor(row, id)
    {
        switch (row) {
            case 'text':
            case 'picture_text':
            case 'video_text':
                setTextareaValue(id);
                break;
            case '2_blocks':
                setTextareaValue(id +'_1');
                setTextareaValue(id +'_2');
                break;
            case '3_blocks':
            case 'how_it_work':
                setTextareaValue(id +'_1');
                setTextareaValue(id +'_2');
                setTextareaValue(id +'_3');
                break;
            case '4_blocks':
                setTextareaValue(id +'_1');
                setTextareaValue(id +'_2');
                setTextareaValue(id +'_3');
                setTextareaValue(id +'_4');
                break;
            default:
                break;
        }
    }

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

                //get the id of the section
                let itemId = $(evt.from).children('div')[toSectionPos].attributes['item-id'].nodeValue;

                //function name search by id
                if (function_exists('initText' + itemId)) {
                    eval('initText' + itemId + '()');
                }

                itemsArray = newArr;

                var ids_row = "|" + itemsArray.join('||') + "|";
                $('#section_row').val(ids_row);

                console.log(ids_row);
            }
        });
    });


//  ------------------  script for map ---------------
    var map, geocoder, marker = null;
    var default_coordinates = {lat: 51.5095146286, lng: -0.1244828354};

    function init_geocoder() {
        geocoder = new google.maps.Geocoder();

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 9,
            center: default_coordinates
        });
    }

    function edit_marker(lat, lng, address) {
        if (marker !== null) {
            marker.setMap(null);
            marker = null;
        }

        marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: lng
            },
            map: map,
            title: address,
            draggable: true
        });

        $('#coordinates').val(marker.getPosition().lat() + "," + marker.getPosition().lng());

        marker.addListener('mouseup', function () {
            $('#coordinates').val(marker.getPosition().lat() + "," + marker.getPosition().lng());
        });


        map.panTo({
            lat: lat,
            lng: lng
        });
    }

    $(function () {
        $('#address').keyup(function () {
            var address = $(this).val().trim();
            if (address.length <= 2) return;

            geocoder.geocode({'address': address}, function (results, status) {
                if (status === 'OK') {
                    edit_marker(results[0].geometry.location.lat(), results[0].geometry.location.lng(), address);
                } else {
                    var title = '';
                    if (status === 'ZERO_RESULTS')
                        title = 'Address not found on map';
                    if (status === 'OVER_QUERY_LIMIT')
                        title = 'Geocoder Limit exceed, wait few seconds and try again';
                }
            });
        });
    })
</script>

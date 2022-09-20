<form id="form_box" action="{URL:panel/microsites/edit/<?= $this->edit->id ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-cubes" href="{URL:panel/microsites}"></a>
                                    <h1 class="page_title"><?= $this->edit->title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side">
                                <div class="items_small-block">
                                    <a href="{URL:microsite/<?= $this->edit->ref; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" title="View Microsite" target="_blank"></a>

                                    <div class="social-btns-list">
                                        <a onclick="share_linkedin(this);" class="btn-social" href="#" data-url="{URL:microsite/<?= $this->edit->ref; ?>}">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                        <a onclick="share_facebook(this);" class="btn-social" href="#" data-url="{URL:microsite/<?= $this->edit->ref; ?>}">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                        <a onclick="share_twitter(this);" class="btn-social" href="#" data-url="{URL:microsite/<?= $this->edit->ref; ?>}">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                        <a class="btn-social copy_btn" href="#" data-clipboard-text="{URL:microsite/<?= $this->edit->ref; ?>}">
                                            <i class="fa fa-copy"></i>
                                        </a>
                                    </div>
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/microsites}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                        <div class="items_group items_group-wrap items_group-bottom">
                            <div class="items_left-side">
                                <div class="option-btns-list scroll-list">
                                    <a class="btn btn-rectangle_medium active"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                    <a href="{URL:panel/microsites/statistic/<?= $this->edit->id; ?>}" class="btn btn-rectangle_medium" title="Statistic"><i class="bs-tooltip fa fa-chart-bar"></i>Statistic</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Account Info</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="title">Title</label>
                            <input class="form-control" type="text" name="title" id="title" value="<?= post('title', false, $this->edit->title); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ref">Ref (Only numbers, letters and dash accepted)</label>
                            <input class="form-control" type="text" name="ref" id="ref" value="<?= post('ref', false, $this->edit->ref); ?>" required>
                        </div>

                        <div class="form-group col-md-6 mb-0">
                            <label for="">Logo<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="logo_image" id="logo_image" value="<?= post('logo_image', false, $this->edit->logo_image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'width=230', 'height=230', 'field=#logo_image', 'preview=#pre_logo_image', )" style="cursor: pointer;"> <div>
                                        Choose image<br>
                                        <span>(230 x 230 px min)</span>
                                    </div></a>

                                <div id="pre_logo_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/microsites/<?= post('logo_image', false, $this->edit->logo_image); ?>" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6 mb-0">
                            <label for="">Landing Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="header_image" id="header_image" value="<?= post('header_image', false, $this->edit->header_image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'width=1900', 'height=650', 'field=#header_image', 'preview=#pre_header_image', )" style="cursor: pointer;"> <div>
                                        Choose image<br>
                                        <span>(1900 x 650 px min)</span>
                                    </div></a>

                                <div id="pre_header_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/microsites/<?= post('header_image', false, $this->edit->header_image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Key Information</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="website">Website URL</label>
                            <input class="form-control" type="text" name="website" id="website" value="<?= post('website', false, $this->edit->website); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company_size">Company Size*</label>
                            <input class="form-control" type="text" name="company_size" id="company_size" value="<?= post('company_size', false, $this->edit->company_size); ?>" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="">Key Information Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="key_image" id="key_image" value="<?= post('key_image', false, $this->edit->key_image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'width=555', 'height=450', 'field=#key_image', 'preview=#pre_key_image', )" style="cursor: pointer;"> <div>
                                        Choose image<br>
                                        <span>(555 x 450 px min)</span>
                                    </div></a>

                                <div id="pre_key_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/microsites/<?= post('key_image', false, $this->edit->key_image); ?>" alt="">
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="form-group col-md-6">
                            <label for="">Overview Section Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="overview_image" id="overview_image" value="<?= post('overview_image', false, $this->edit->overview_image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'width=555', 'height=450', 'field=#overview_image', 'preview=#pre_overview_image', )" style="cursor: pointer;"> <div>
                                        Choose image<br>
                                        <span>(555 x 450 px min)</span>
                                    </div></a>

                                <div id="pre_overview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/microsites/<?= post('overview_image', false, $this->edit->overview_image); ?>" alt="">
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-md-6">
                            <label>Industries/Sectors</label>
                            <input type="text" class="form-control" id="sector_filter" value="" autocomplete="off" placeholder="Start typing to filter sectors below">
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->sectors) && is_array($this->sectors) && count($this->sectors) > 0) { ?>
                                    <?php foreach ($this->sectors as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="sector_ids[]" id="sector_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('sector_ids'), $item->id, $this->edit->sector_ids); ?>
                                            ><label class="custom-control-label sectors" for="sector_<?=$item->id?>"><?= $item->name; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Overview</label>
                            <div class="form-group">
                                <textarea class="form-control" name="content" id="content_box" rows="20"><?= post('content', false, $this->edit->content); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group col-md-6 mb-0">
                            <label>Headquarters</label>
                            <input type="text" class="form-control" id="head_filter" value="" autocomplete="off" placeholder="Start typing to filter headquarters below">
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->locations) && is_array($this->locations) && count($this->locations) > 0) { ?>
                                    <?php foreach ($this->locations as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="location_ids[]" id="tag_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('location_ids'), $item->id, $this->edit->location_ids); ?>>
                                            <label class="custom-control-label heads" for="tag_<?=$item->id?>"><?= $item->name; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group col-md-6 mb-0">
                            <label>Sectors</label>
                            <input type="text" class="form-control" id="tag_filter" value="" autocomplete="off" placeholder="Start typing to filter tag sectors below">
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->tag_sectors) && is_array($this->tag_sectors) && count($this->tag_sectors) > 0) { ?>
                                    <?php foreach ($this->tag_sectors as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="tag_sector_ids[]" id="location_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('tag_sector_ids'), $item->id, $this->edit->tag_sector_ids); ?>>
                                            <label class="custom-control-label tags" for="location_<?=$item->id?>"><?= $item->name; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>On-page SEO</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="meta_title">
                                    Meta Title
                                    <a href="https://moz.com/learn/seo/title-tag" target="_blank"><i class="fas fa-info-circle"></i></a>
                                </label>
                                <input class="form-control" type="text" name="meta_title" id="meta_title" value="<?= post('meta_title', false, $this->edit->meta_title); ?>">
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
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#og_image', 'preview=#pre_og_image', )" style="cursor: pointer;"> <div>
                                        Choose image
                                    </div></a>

                                <div id="pre_og_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/microsites/<?= post('og_image', false, $this->edit->og_image); ?>" alt="">
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

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/microsites}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/microsites/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
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

    $('#sector_filter').keyup(function () {
        var q = $(this).val().toLowerCase().trim();

        if (q.length > 0) {
            $('.sectors').each(function (i, sector) {
                if ($(sector).text().trim().toLowerCase().indexOf(q) === -1) {
                    $(sector).parent().addClass('hidden')
                } else {
                    $(sector).parent().removeClass('hidden')
                }
            });
        } else {
            $('.sectors').each(function (i, sector) {
                $(sector).parent().removeClass('hidden')
            });
        }
    });

    $('#head_filter').keyup(function () {
        var q = $(this).val().toLowerCase().trim();

        if (q.length > 0) {
            $('.heads').each(function (i, head) {
                if ($(head).text().trim().toLowerCase().indexOf(q) === -1) {
                    $(head).parent().addClass('hidden')
                } else {
                    $(head).parent().removeClass('hidden')
                }
            });
        } else {
            $('.heads').each(function (i, head) {
                $(head).parent().removeClass('hidden')
            });
        }
    });

    $('#tag_filter').keyup(function () {
        var q = $(this).val().toLowerCase().trim();

        if (q.length > 0) {
            $('.tags').each(function (i, tag) {
                if ($(tag).text().trim().toLowerCase().indexOf(q) === -1) {
                    $(tag).parent().addClass('hidden')
                } else {
                    $(tag).parent().removeClass('hidden')
                }
            });
        } else {
            $('.tags').each(function (i, tag) {
                $(tag).parent().removeClass('hidden')
            });
        }
    });

    var editorField;

    function setTextareaValue() {
        $('#content_box').val(editorField.getData());
    }

    $(function () {
        initSlug('#ref', '#title');

        $("#title").keyup(function () {
            initSlug('#ref', '#title');
        });

        editorField = CKEDITOR.replace('content_box', {
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
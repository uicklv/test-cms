<script src="<?= _SITEDIR_ ?>public/js/backend/Sortable.min.js"></script>

<form id="form_box" action="{URL:panel/shops/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing sticky-container">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-top">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-briefcase" href="{URL:panel/shops}"></a>
                                    <h1 class="page_title"><?= $this->edit->title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side">
                                <div class="items_small-block">
                                    <a href="{URL:shop/product/<?= $this->edit->slug; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" title="View Product" target="_blank"></a>

                                    <div class="social-btns-list">
                                        <a onclick="share_linkedin(this);" class="btn-social" href="#" data-url="{URL:shop/product/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                        <a onclick="share_facebook(this);" class="btn-social" href="#" data-url="{URL:shop/product/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                        <a onclick="share_twitter(this);" class="btn-social" href="#" data-url="{URL:shop/product/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                        <a class="btn-social copy_btn" href="#" data-clipboard-text="{URL:shop/product/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-copy"></i>
                                        </a>
                                    </div>
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/shops}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                        <div class="items_group items_group-wrap items_group-bottom">
                            <div class="items_left-side">
                                <div class="option-btns-list scroll-list">
                                    <a  href="{URL:panel/shops/edit/<?= $this->edit->id; ?>}" class="btn btn-rectangle_medium"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                    <a class="btn btn-rectangle_medium active"><i class="bs-tooltip fa fa-pencil-alt"></i>Media</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photos -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Images</h4>

                    <div class="form-row" id="images-box">
                        <div class="form-group col-md-12">
                            <div class="flex-btw">
                                <script>
                                    var index_images = 0;
                                </script>
                                <div>

                                </div>
                                <a class="btn btn-primary mb-2 mr-2" onclick="addPoint(index_images);">
                                    <i class="fas fa-plus"></i>
                                    Add New Image
                                </a>
                            </div>
                        </div>
                        <?php if ($this->images) { ?>
                            <?php foreach ($this->images as $k => $item) { ?>
                                <div id="images<?= $k ?>-remove" class="form-row col-md-12">
                                    <div class="form-group col-md-6">
                                        <!-- Image -->
                                        <label for="image<?= $k ?>">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                                        <div class="choose-file modern">
                                            <input type="hidden" name="images[]" id="image<?= $k ?>" value="<?= $item->media; ?>">
                                            <a class="file-fake" onclick="load('panel/select_image', 'field=#image<?= $k ?>', 'preview=#preview_image<?= $k ?>')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                            <div id="preview_image<?= $k ?>" class="preview_image">
                                                <img src="<?= _SITEDIR_ ?>data/shop/<?= $item->media; ?>" alt="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <a class="btn btn-danger" onclick="removePoint('images<?= $k ?>');">Remove Image</a>
                                    </div>
                                </div>
                                <script>
                                    index_images++;
                                </script>
                            <?php } ?>
                        <?php } ?>
                    </div>

                </div>
            </div>

            <!-- Videos -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Videos</h4>

                    <div class="form-row" id="videos-box">
                        <div class="form-group col-md-12">
                            <div class="flex-btw">
                                <script>
                                    var index_videos = 0;
                                </script>
                                <div>

                                </div>
                                <a class="btn btn-primary mb-2 mr-2" onclick="addVideo(index_videos);">
                                    <i class="fas fa-plus"></i>
                                    Add New Video
                                </a>
                            </div>
                        </div>
                        <?php if ($this->videos) { ?>
                            <?php foreach ($this->videos as $k => $item) { ?>
                                <div id="videos<?= $k ?>-remove" class="form-row col-md-12">
                                    <div class="form-group col-md-6">
                                        <label for="video<?= $k ?>">Youtube embed link</label>
                                        <input class="form-control" type="text" name="videos[]" id="video<?= $k ?>" value="<?= $item->media ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <a class="btn btn-danger" onclick="removePoint('videos<?= $k ?>');">Remove Video</a>
                                    </div>
                                </div>
                                <script>
                                    index_videos++;
                                </script>
                            <?php } ?>
                        <?php } ?>
                    </div>

                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/shops}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                load('panel/shops/media/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
<script>
//remove bullet point
function removePoint(element) {
    $('#' + element + '-remove').remove();
}

//add bullet point for entities
function addVideo(index) {

    let html = `<div id="videos${index}-remove" class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label for="video${index}">Youtube embed link</label>
                        <input class="form-control" type="text" name="videos[]" id="video${index}" value="">
                    </div>

                    <div class="form-group col-md-6">
                        <a class="btn btn-danger" onclick="removePoint('videos${index}');">Remove Video</a>
                    </div>
                </div>`;

    $('#videos-box').append(html);
    index_videos++;
}

//add bullet point for entities
function addPoint(index) {

    let html = `<div id="images${index}-remove" class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <!-- Image -->
                        <label for="image${index}">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                        <div class="choose-file modern">
                            <input type="hidden" name="images[]" id="image${index}" value="${index}">
                            <a class="file-fake" onclick="load('panel/select_image', 'field=#image${index}', 'preview=#preview_image${index}')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                            <div id="preview_image${index}" class="preview_image">
                                <img src="" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <a class="btn btn-danger" onclick="removePoint('images${index}');">Remove Image</a>
                    </div>
                </div>`;

    $('#images-box').append(html);
    index_images++;
}
</script>
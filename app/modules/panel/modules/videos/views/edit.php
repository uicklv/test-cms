<form id="form_box" action="{URL:panel/videos/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-video" href="{URL:panel/videos}"></a>
                                    <h1 class="page_title"><?= $this->edit->name ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/videos}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false, $this->edit->name); ?>">
                        </div>

                        <div class="form-group col-md-6 mb-0">
                            <div class="form-group">
                                <label for="name">Text</label>
                                <input class="form-control" type="text" name="text" id="text" value="<?= post('text', false, $this->edit->text); ?>">
                            </div>
                            <div class="form-group mb-0">
                                <label for="name">Video link</label>
                                <input class="form-control" type="text" name="video" id="video" value="<?= post('video', false, $this->edit->video); ?>">
                            </div>
                        </div>

                        <div class="form-group col-md-6 mb-0">
                            <!-- Image -->
                            <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->edit->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/videos/<?= post('image', false, $this->edit->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/videos}"><i class="fas fa-reply"></i>Back</a>
                        <button type="submit" name="submit" class="btn btn-success"
                                onclick="load('panel/videos/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
<form id="form_box" action="{URL:panel/microsites/videos/add}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h1 class="page_title"><a href="{URL:panel/microsites/videos/index/<?= $this->microsite_id ?>}">Videos</a>&nbsp;» New</h1>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="address">Name</label>
                                <input class="form-control" type="text" name="name" required id="name" value="<?= post('name', false); ?>">
                            </div>

                            <div class="form-group">
                                <label for="file">Video<small><i>(Videos must be under <?= file_upload_max_size_format() ?>, and <?= strtoupper(implode(', ', array_keys(File::$allowedVideoFormats))) ?> format)</i></small></label>
                                <div class="flex-btw flex-vc">
                                    <div class="choose-file">
                                        <input type="hidden" name="file" id="file" value="<?= post('file', false, $this->edit->file); ?>">
                                        <input type="file" accept="video/mp4, video/avi, video/mkv" onchange="initFile(this); load('panel/microsites/videos/uploadVideo/', 'name=<?= randomHash(); ?>', 'preview=#file_name', 'field=#file');">
                                        <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                    </div>

                                    <div id="file_name"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <!-- Image -->
                            <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                <div id="preview_image" class="preview_image"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div>
                            <button type="submit" name="submit" class="btn btn-success" onclick="load('panel/microsites/videos/add/<?= $this->microsite_id ?>', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
                            <a class="btn btn-outline-warning" href="{URL:panel/microsites/videos/index/<?= $this->microsite_id ?>}"><i class="fas fa-ban"></i>Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
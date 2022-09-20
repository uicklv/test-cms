<form id="form_box" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h1 class="page_title">Your Terms & Conditions</h1>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-group">
                        <!-- File -->
                        <label for="file">File
                            <?= ($this->edit->file ? '<a href="'. _SITEDIR_ .'data/talent/your_tc/' . $this->edit->file . '" download="file.' . File::format($this->edit->file ) . '"><i class="fas fa-download"></i> Download</a>' : '') ?>
                        </label>
                        <div class="flex-btw flex-vc" id="download">
                            <div class="choose-file">
                                <input type="hidden" name="file" id="file" value="<?= post('file', false, $this->edit->file); ?>">
                                <input type="file"  onchange="initFile(this); load('panel/upload/', 'name=<?= randomHash(); ?>', 'preview=#file_name', 'field=#file');">
                                <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                            </div>
                            <div id="file_name"></div>
                        </div>
                        <input type="hidden" name="file_id" value="<?= $this->edit->id ?>">
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div>
                            <button  name="submit" class="btn btn-success" onclick="load('panel/talents/your_tc/add', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>


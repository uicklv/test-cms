<form id="form_box" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h1 class="page_title">Password Protection</h1>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <input  type="checkbox" name="protection" id="protection" value="1" <?php if ($this->edit->protection == 1) echo 'checked'; ?>><!--required-->
                            <label for="protection">Enable Password Protection</label>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password">Password</label>
                            <input class="form-control" type="text" name="password" id="password" value="<?= post('password', false, $this->edit->password); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label>Areas</label>
                            <div class="form-check scroll_max_200 border_1">
                                <div class="custom-control custom-checkbox checkbox-info">
                                    <input class="custom-control-input" type="checkbox" name="areas[]" id="areas_1" value="anonymous_search"
                                           <?= checkCheckboxValue(post('areas'), 'anonymous_search', explode('||', trim($this->edit->areas, '|'))); ?>
                                    ><label class="custom-control-label" for="areas_1">Anonymous Profiles Search</label>
                                </div>
                                <div class="custom-control custom-checkbox checkbox-info">
                                    <input class="custom-control-input" type="checkbox" name="areas[]" id="areas_3" value="anonymous_profiles"
                                        <?= checkCheckboxValue(post('areas'), 'anonymous_profiles', explode('||', trim($this->edit->areas, '|'))); ?>
                                    ><label class="custom-control-label" for="areas_3">Anonymous Profiles</label>
                                </div>
                                <div class="custom-control custom-checkbox checkbox-info">
                                    <input class="custom-control-input" type="checkbox" name="areas[]" id="areas_4" value="open_profiles"
                                        <?= checkCheckboxValue(post('areas'), 'open_profiles', explode('||', trim($this->edit->areas, '|'))); ?>
                                    ><label class="custom-control-label" for="areas_4">Open Profiles</label>
                                </div>
                                <div class="custom-control custom-checkbox checkbox-info">
                                    <input class="custom-control-input" type="checkbox" name="areas[]" id="areas_5" value="hotlists"
                                        <?= checkCheckboxValue(post('areas'), 'hotlists', explode('||', trim($this->edit->areas, '|'))); ?>
                                    ><label class="custom-control-label" for="areas_5">Hot lists</label>
                                </div>
                                <div class="custom-control custom-checkbox checkbox-info">
                                    <input class="custom-control-input" type="checkbox" name="areas[]" id="areas_6" value="shortlists"
                                        <?= checkCheckboxValue(post('areas'), 'shortlists', explode('||', trim($this->edit->areas, '|'))); ?>
                                    ><label class="custom-control-label" for="areas_6">Short lists</label>
                                </div>
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
                            <button  name="submit" class="btn btn-success" onclick="load('panel/talents/password_protection', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>



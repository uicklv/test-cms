<form id="form_box" action="{URL:panel/learning_development/categories/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-graduation-cap" href="{URL:panel/learning_development/categories}"></a>
                                    <h1 class="page_title"><?= $this->edit->name ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/learning_development/categories}"><i class="fas fa-reply"></i>Back</a>
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
                        <div class="form-group col-md-12">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false, $this->edit->name); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="image">Image 590x390 px<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->edit->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image', 'width=590', 'height=390')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/learning_development/<?= post('image', false, $this->edit->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Deny access</label>
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->users) && is_array($this->users) && count($this->users) > 0) { ?>
                                    <?php foreach ($this->users as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="users[]" id="location_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('users'), $item->id, $this->edit->users_ids); ?>
                                            ><label class="custom-control-label" for="location_<?=$item->id?>"><?= $item->firstname . ' ' . $item->lastname; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/learning_development/categories}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                load('panel/learning_development/categories/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>



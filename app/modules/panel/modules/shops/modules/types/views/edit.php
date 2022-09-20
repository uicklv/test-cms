<form id="form_box" action="{URL:panel/shops/types/edit/<?= $this->sector->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-map-marker-alt" href="{URL:panel/shops/types}"></a>
                                    <h1 class="page_title"><?= $this->sector->name ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block"></div>
                                <a class="btn btn-outline-warning" href="{URL:panel/shops/types}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-group mb-0">
                        <label for="name">Name</label>
                        <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false, $this->sector->name); ?>">
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/shops/types}"><i class="fas fa-reply"></i>Back</a>
                        <button type="submit" name="submit" class="btn btn-success"
                                onclick="load('panel/shops/types/edit/<?= $this->sector->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

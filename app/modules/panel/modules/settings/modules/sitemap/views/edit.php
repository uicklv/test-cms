<form id="form_box"  enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-sitemap" href="{URL:panel/settings/sitemap}"></a>
                                    <h1 class="page_title">Site Map&nbsp;» Edit</h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block"></div>
                                <a class="btn btn-outline-warning" href="{URL:panel/settings/sitemap}"><i class="fas fa-reply"></i>Back</a>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="table">Table</label>
                                <input class="form-control" type="text" name="table" id="table" value="<?= post('table', false, $this->item->table); ?>">
                            </div>
                            <div class="form-group">
                                <label for="where">Where</label>
                                <input class="form-control" type="text" name="where" id="where" value="<?= post('where', false, $this->item->where); ?>">
                            </div>
                            <div class="form-group mb-0">
                                <label for="url">Url</label>
                                <input class="form-control" type="text" name="url" id="url" value="<?= post('url', false, $this->item->url); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/settings/sitemap}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="load('panel/settings/sitemap/edit/<?= $this->item->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

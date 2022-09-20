<form id="form_box" action="{URL:panel/settings/sitemap/add}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-top">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-sitemap"></a>
                                    <h1 class="page_title">Site Map</h1>
                                </div>
                            </div>

                            <div class="items_right-side">
                                <div class="items_small-block">
                                    <a href="<?= url('sitemap.xml') ?>" class="btn-rectangle bs-tooltip fa fa-eye" title="View sitemap.xml" target="_blank"></a>
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>

                    <div class="form-group">
                        <label for="base_url">Site URL</label>
                        <input class="form-control" type="text" name="base_url" id="base_url" value="<?= SITE_URL ?>">
                    </div>

                    <div class="form-group">
                        <label>Custom links</label>
                        <textarea class="form-control" name="links" id="links" rows="14" style="width: 100%;"><?= ($this->customLinks ? $this->customLinks->value : '') ?></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label>Generated links <i data-clipboard-text="<?= ($this->links ? $this->links : '') ?>" class="bs-tooltip copy_btn fa fa-copy btn__circle" style="cursor: pointer;"></i></label>
                        <textarea class="form-control" name="links" id="links" rows="14" style="width: 100%;"><?= ($this->links ? $this->links : '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Add Section -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Add Section</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="table">Choose Table</label>
                            <select class="form-control" name="table" id="table" required>
                                <?php if ($this->tables) foreach ($this->tables as $table) { ?>
                                    <option value="<?= $table; ?>" <?= checkOptionValue(post('table'), $table); ?>><?= $table; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ref">Where // ex: `deleted` = 'no'</label>
                            <input class="form-control" type="text" name="where" id="where" value="<?= post('condition', false); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <label>Url // ex: blog/{slug}</label>
                            <input class="form-control" type="text" name="url" id="url" value="<?= post('url', false); ?>">
                        </div>
                        <div class="form-group col-md-6 mb-0">
                            <div class="flex-btw flex-vb" style="height: 100%;">
                                <div></div>
                                <button type="submit" name="submit" class="btn btn-outline-primary" onclick="load('panel/settings/sitemap/add', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Add Section</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Sections -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Sections</h4>

                    <!-- Table -->
                    <div class="table-responsive mb-4 mt-4">
                        <table id="zero-config" class="table table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Table</th>
                                <th>Where</th>
                                <th>Url</th>
                                <th class="w_options">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                                <?php foreach ($this->list as $item) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $item->id; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->table; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->where; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->url; ?>
                                        </td>
                                        <td class="option__buttons">
                                            <a href="{URL:panel/settings/sitemap/edit/<?= $item->id; ?>}" class="bs-tooltip fa fa-pencil-alt" title="Edit"></a>
                                            <a href="{URL:panel/settings/sitemap/delete/<?= $item->id; ?>}" class="bs-tooltip fa fa-trash" title="Delete"></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="load('panel/settings/sitemap/save', 'links#links'); return false;"><i class="fas fa-save"></i>Generate Sitemap</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?= _SITEDIR_ ?>public/plugins/datatable/datatables.js"></script>
<script>
    $(function () {
        $('#zero-config').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "aoColumnDefs": [
                {
                    "aTargets": [-1],
                    "bSortable": false,
                },
                {
                    "aTargets": [-1],
                    "bSearchable": false
                }
            ],
            "stripeClasses": [],
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 25
        });
    });
</script>
<!-- END PAGE LEVEL SCRIPTS -->

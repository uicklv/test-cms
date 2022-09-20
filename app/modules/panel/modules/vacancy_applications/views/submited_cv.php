<!--LOADER-->
<div id="loader_fon">
    <div id="loader"></div>
</div>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">

                <!-- Head -->
                <div class="flex-btw flex-vc mob_fc">
                    <h1>Uploaded CVs</h1>
                    <div>
                        <a class="btn btn-primary mb-2 mr-2" href="{URL:panel/vacancy_applications/cvs_archive}">
                            <i class="fas fa-archive mp768_0"></i>
                            <span class="hide_block768">Archived</span>
                        </a>
                        <a class="btn btn-primary mb-2 mr-2" onclick="load('panel/vacancy_applications/export_data', 'type=upload'); return false;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span class="hide_block768">Export</span> to CSV
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive mb-4 mt-4">
                    <table id="zero-config" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th class="w_options">Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                            <?php foreach ($this->list as $item) { ?>
                                <tr class="tr-hovered">
                                    <td onclick="load('panel/vacancy_applications/submited_popup/<?= $item->id; ?>');" style="cursor: pointer">
                                        <?php if (!$item->status) { ?>
                                            <strong><?= $item->name; ?></strong>
                                        <?php } else { ?>
                                            <?= $item->name; ?>
                                        <?php } ?>
                                    </td>
                                    <td onclick="load('panel/vacancy_applications/submited_popup/<?= $item->id; ?>');" style="cursor: pointer">
                                        <?php if (!$item->status) { ?>
                                            <strong><?= $item->email; ?></strong>
                                        <?php } else { ?>
                                            <?= $item->email; ?>
                                        <?php } ?>
                                    </td>
                                    <td onclick="load('panel/vacancy_applications/submited_popup/<?= $item->id; ?>');" data-sort="<?= $item->time ?>" style="cursor: pointer">
                                        <?php if (!$item->status) { ?>
                                            <strong><?= date('Y-m-d', $item->time); ?></strong>
                                        <?php } else { ?>
                                            <?= date('Y-m-d', $item->time); ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="form-status-block">
                                            <div class="form-status">
                                                <div id="status_text_<?= $item->id; ?>">
                                                    <?= applicationStatus($item->status, true); ?>
                                                </div>
                                                <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                                            </div>
                                            <ul class="fs-list" style="cursor: pointer">
                                                <li onclick="load('panel/vacancy_applications/change_cv_status/<?= $item->id; ?>', 'status=reviewed');"><div class="fs-item var-1">Reviewed</div></li>
                                                <li onclick="load('panel/vacancy_applications/change_cv_status/<?= $item->id; ?>', 'status=spoken');"><div class="fs-item var-5">Spoken to Candidate</div></li>
                                                <li onclick="load('panel/vacancy_applications/change_cv_status/<?= $item->id; ?>', 'status=interviewed');"><div class="fs-item var-4">Interviewed</div></li>
                                                <li onclick="load('panel/vacancy_applications/change_cv_status/<?= $item->id; ?>', 'status=shortlisted');"><div class="fs-item var-6">Shortlisted</div></li>
                                                <li onclick="load('panel/vacancy_applications/change_cv_status/<?= $item->id; ?>', 'status=rejected');"><div class="fs-item var-2">Rejected</div></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="items-row align-items-center">
                                            <div class="dropdown dropup custom-dropdown-icon">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-3">
                                                    <?php if ($item->cv) { ?>
                                                        <a href="<?= _SITEDIR_ ?>data/cvs/<?= $item->cv; ?>" class="dropdown-item"><i class="fa fa-download"></i> Download CV</a>
                                                    <?php } ?>
                                                    <a href="{URL:panel/vacancy_applications/cv_delete/<?= $item->id; ?>}" class="dropdown-item remove-item"><i class="fa fa-trash-alt"></i> Delete</a>
                                                </div>
                                            </div>

                                            <div class="btns-list">
                                                <?php if ($item->cv) { ?>
                                                    <a href="<?= _SITEDIR_ ?>data/cvs/<?= $item->cv; ?>" download="<?= $item->name; ?>.<?= File::format($item->cv)?>" target="_blank" class="btn-rectangle active bs-tooltip fa fa-download" title="Download CV"></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                    <script>
                        $(".form-status").click(function() {
                            $('.fs-list').hide();
                            $(".form-status-block").removeClass('active');
                            $(this).parent().find('.fs-list').toggle();
                            $(this).parent().toggleClass('active');
                        });
                        $(document).on('click', function(e) {
                            if (!$(e.target).closest(".form-status-block").length) {
                                $('.fs-list').hide();
                                $(".form-status-block").removeClass('active');
                            }
                            e.stopPropagation();
                        });
                        function closeStatusBlock() {
                            $('.fs-list').hide();
                            $(".form-status-block").removeClass('active');
                        }
                    </script>
                </div>

            </div>
        </div>

    </div>
</div>


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
                    "aTargets": [-2, -1],
                    "bSearchable": false
                }
            ],
            "stripeClasses": [],
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 25,
            "order": [[0, "desc"]]
        });
    });
</script>
<!-- END PAGE LEVEL SCRIPTS -->



<!--LOADER-->
<div id="loader_fon">
    <div id="loader"></div>
</div>
<!--END LOADER-->
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <!-- Title ROW -->
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="statbox widget box box-shadow widget-top">
                <div class="widget-header">

                    <div class="items_group items_group-wrap">
                        <div class="items_left-side">
                            <div class="title-block">
                                <a class="btn-ellipse bs-tooltip fa fa-user-tie" href="{URL:panel/candidates}"></a>
                                <h1 class="page_title"><?= $this->user->firstname . ' ' . $this->user->lastname ?></h1>
                            </div>
                        </div>

                        <div class="items_right-side">
                            <div class="items_small-block">
                            </div>

                            <a class="btn btn-outline-warning" href="{URL:panel/candidates}"><i class="fas fa-reply"></i>Back</a>
                        </div>
                    </div>

                    <div class="items_group items_group-wrap items_group-bottom">
                        <div class="items_left-side">
                            <div class="option-btns-list scroll-list">
                                <a href="{URL:panel/candidates/edit/<?= $this->user->id; ?>}" class="btn btn-rectangle_medium"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                <a class="btn btn-rectangle_medium active" title="Applications List"><i class="bs-tooltip far fa-user"></i>Applications</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Table -->
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">

            <div class="widget-content widget-content-area br-6">
                <div class="flex-btw flex-vc mob_fc">
                    <div></div>
                    <div>
                        <a class="btn btn-outline-dark" title="Export CV's" onclick="loader(); load('panel/candidates/export_applied_job/<?= $this->user->id; ?>', 'type=cv');">
                            <i class="bs-tooltip fas fa-file-export"></i>Export CVs</a>
                    </div>
                </div>
                <div class="table-responsive mb-4 mt-4" id="resault_box">
                    <table id="zero-config" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Vacancy</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                            <?php foreach ($this->list as $item) { ?>
                                <tr class="tr-hovered">
                                    <td><?= $item->id ?></td>

                                    <td><?php /*onclick="load('panel/vacancies/application_popup/<?= $item->id; ?>');"*/ ?>
                                        <a href="{URL:panel/vacancy_applications/edit/<?= $item->id; ?>}">
                                            <?php if (!$item->status) { ?>
                                                <strong><?= $item->name; ?></strong>
                                            <?php } else { ?>
                                                <?= $item->name; ?>
                                            <?php } ?>
                                        </a>
                                    </td>

                                    <td>
                                        <?php if (!$item->status) { ?>
                                            <strong><?= $item->email; ?></strong>
                                        <?php } else { ?>
                                            <?= $item->email; ?>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <a href="{URL:panel/vacancies/edit/<?= $item->vacancy_id; ?>}">
                                            <?php if (!$item->status) { ?>
                                                <strong><?= $item->vacancy_title ? $item->vacancy_title : ''; ?></strong>
                                            <?php } else { ?>
                                                <?= $item->vacancy_title ? $item->vacancy_title : ''; ?>
                                            <?php } ?>
                                        </a>
                                    </td>

                                    <td data-sort="<?= $item->time ?>">
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
                                                <li onclick="load('panel/vacancies/change_app_status/<?= $item->id; ?>', 'status=reviewed', 'vacancy_id=<?= $this->edit->id; ?>');"><div class="fs-item var-1">Reviewed</div></li>
                                                <li onclick="load('panel/vacancies/change_app_status/<?= $item->id; ?>', 'status=spoken', 'vacancy_id=<?= $this->edit->id; ?>');"><div class="fs-item var-5">Spoken to Candidate</div></li>
                                                <li onclick="load('panel/vacancies/change_app_status/<?= $item->id; ?>', 'status=interviewed', 'vacancy_id=<?= $this->edit->id; ?>');"><div class="fs-item var-4">Interviewed</div></li>
                                                <li onclick="load('panel/vacancies/change_app_status/<?= $item->id; ?>', 'status=shortlisted', 'vacancy_id=<?= $this->edit->id; ?>');"><div class="fs-item var-6">Short-listed</div></li>
                                                <li onclick="load('panel/vacancies/change_app_status/<?= $item->id; ?>', 'status=rejected', 'vacancy_id=<?= $this->edit->id; ?>');"><div class="fs-item var-2">Rejected</div></li>
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
                                                    <a href="{URL:panel/vacancy_applications/edit/<?= $item->id; ?>}" class="dropdown-item"><i class="fa fa-pencil-alt"></i> Edit</a>
                                                    <?php if ($item->job_spec) { ?>
                                                        <a href="<?= _SITEDIR_ ?>data/cvs/<?= $item->job_spec; ?>" class="dropdown-item"><i class="fa fa-download"></i> Download Job Spec</a>
                                                    <?php } ?>
                                                    <?php if ($item->cv) { ?>
                                                        <a href="<?= _SITEDIR_ ?>data/cvs/<?= $item->cv; ?>" class="dropdown-item"><i class="fa fa-download"></i> Download CV</a>
                                                    <?php } ?>
                                                    <a href="{URL:panel/vacancy_applications/delete/<?= $item->id; ?>}" class="dropdown-item remove-item"><i class="fa fa-trash-alt"></i> Delete</a>
                                                </div>
                                            </div>

                                            <div class="btns-list">
                                                <?php if ($item->cv) { ?>
                                                    <a href="<?= _SITEDIR_ ?>data/cvs/<?= $item->cv; ?>" download="<?= $item->name; ?>.<?= File::format($item->cv)?>" target="_blank" class="btn-rectangle active bs-tooltip fa fa-download" title="Download CV"></a>
                                                <?php } ?>
                                                <?php if ($item->job_spec) { ?>
                                                    <a href="<?= _SITEDIR_ ?>data/cvs/<?= $item->job_spec; ?>" download="<?= $item->name; ?>.<?= File::format($item->job_spec)?>" target="_blank" class="btn-rectangle active bs-tooltip fa fa-download" title="Download Job Spec"></a>
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
            "stripeClasses": [],
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 25
        });
    });
</script>
<!-- END PAGE LEVEL SCRIPTS -->

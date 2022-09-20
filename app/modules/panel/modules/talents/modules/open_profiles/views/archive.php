<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">

                <!-- Head -->
                <div class="flex-btw flex-vc mob_fc">
                    <h1>Archived Profiles</h1>
                    <a class="btn btn-primary mb-2 mr-2" href="{URL:panel/talents/open_profiles}">
                        <i class="fas fa-plus"></i>
                        Active <span class="hide_block768">Profiles</span>
                    </a>
                </div>

                <!-- Table -->
                <div class="table-responsive mb-4 mt-4">
                    <table id="zero-config" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Reference Code</th>
                            <th>Consultant</th>
                            <th>Name</th>
                            <th>Job Title</th>
                            <th>Languages</th>
                            <th>Locations</th>
                            <th>Date Created</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                            <?php foreach ($this->list as $item) { ?>
                                <tr>
                                    <td>
                                        <?= $item->ref; ?>
                                    </td>
                                    <td>
                                        <?= $item->name; ?>
                                    </td>
                                    <td>
                                        <?= $item->consultant->firstname . ' ' . $item->consultant->lastname; ?>
                                    </td>
                                    <td>
                                        <?= $item->candidate_name ?>
                                    </td>
                                    <td>
                                        <?= $item->job_title ?>
                                    </td>
                                    <td>
                                        <?= implode(", ", array_map(function ($language) {
                                                return $language->language_name;
                                            }, $item->languages )
                                        ); ?>
                                    </td>
                                    <td>
                                        <?= implode(", ", array_map(function ($location) {
                                                return $location->location_name;
                                            }, $item->locations )
                                        ); ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', $item->time) ?>
                                    </td>
                                    <td class="option__buttons">
                                      N/A
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
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

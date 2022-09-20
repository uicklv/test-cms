<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">

                <!-- Head -->
                <div class="flex-btw flex-vc mob_fc">
                    <h1>Articles</h1>

                    <div>
                        <a class="btn btn-primary mb-2 mr-2" href="{URL:panel/club_blog/archive}">
                            <i class="fas fa-archive mp768_0"></i>
                            <span class="hide_block768">Archived</span>
                        </a>
                        <a class="btn btn-primary mb-2 mr-2" href="{URL:panel/club_blog/add}">
                            <i class="fas fa-plus"></i>
                            Add <span class="hide_block768">New Blog Post</span>
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive mb-4 mt-4">
                    <table id="zero-config" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th class="max_w_60">ID</th>
                            <th class="min_w_name">Title</th>
                            <th>Date</th>
                            <th class="max_w_55">Share</th>
                            <th class="w_options">Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                            <?php foreach ($this->list as $item) { ?>
                                <tr id="item_<?= $item->id; ?>" class="tr-hovered">
                                    <td class="mini_mize max_w_60" title="<?= $item->id; ?>">
                                        <?= $item->id; ?>
                                    </td>
                                    <td>
                                        <a href="{URL:panel/club_blog/edit/<?= $item->id ?>}"><?= $item->title; ?></a>
                                    </td>
                                    <td data-sort="<?= $item->time ?>">
                                        <?= date('Y-m-d', $item->time); ?>
                                    </td>
                                    <td>
                                        <a class="btn__ bs-tooltip fas fa-share-alt dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference<?= $item->id; ?>"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent"></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuReference<?= $item->id; ?>">
                                            <a onclick="share_linkedin(this);" class="dropdown-item copy_btn" href="#" data-url="{URL:members-growth-club/article/<?= $item->slug; ?>}"><i class="fa fa-linkedin"></i> Share to LinkedIn</a>
                                            <a onclick="share_facebook(this);" class="dropdown-item copy_btn" href="#" data-url="{URL:members-growth-club/article/<?= $item->slug; ?>}"><i class="fa fa-facebook"></i> Share to Facebook</a>
                                            <a onclick="share_twitter(this);" class="dropdown-item copy_btn" href="#" data-url="{URL:members-growth-club/article/<?= $item->slug; ?>}"><i class="fa fa-twitter"></i> Share to Twitter</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item copy_btn" href="#" data-clipboard-text="{URL:members-growth-club/article/<?= $item->slug; ?>}"><i class="fa fa-copy"></i> Copy Link</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="items-row align-items-center">
                                            <div class="dropdown dropup custom-dropdown-icon">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-3">
                                                    <a href="{URL:panel/club_blog/edit/<?= $item->id; ?>}" class="dropdown-item"><i class="fa fa-pencil-alt"></i> Edit</a>
                                                    <a href="{URL:members-growth-club/article/<?= $item->slug; ?>}" class="dropdown-item" target="_blank"><i class="fa fa-eye"></i> View Article</a>
                                                    <a href="{URL:panel/club_blog/views/<?= $item->id; ?>}" class="dropdown-item" target="_blank"><i class="fa fa-chart-bar"></i> Statistic</a>
                                                    <a @click="load('panel/club_blog/delete/<?= $item->id; ?>');" class="dropdown-item remove-item"><i class="fa fa-trash-alt"></i> Delete</a>
                                                </div>
                                            </div>

                                            <div class="btns-list">
                                                <a href="{URL:members-growth-club/article/<?= $item->slug; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" target="_blank" title="View Article"></a>
                                            </div>
                                        </div>
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
                    "aTargets": [-2, -1],
                    "bSortable": false,
                },
                {
                    "aTargets": [-2, -1],
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

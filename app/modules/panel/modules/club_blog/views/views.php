<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <!-- Title ROW -->
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="statbox widget box box-shadow widget-top">
                <div class="widget-header">

                    <div class="items_group items_group-wrap">
                        <div class="items_left-side">
                            <div class="title-block">
                                <a class="btn-ellipse bs-tooltip fas fa-lock" href="{URL:panel/club_blog}"></a>
                                <h1 class="page_title"><?= $this->blog->title ?></h1>
                            </div>
                        </div>

                        <div class="items_right-side">
                            <div class="items_small-block">
                                <a href="{URL:members-growth-club/article/<?= $this->blog->slug; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" title="View Blog" target="_blank"></a>

                                <div class="social-btns-list">
                                    <a onclick="share_linkedin(this);" class="btn-social" href="#" data-url="{URL:members-growth-club/article/<?= $this->blog->slug; ?>}">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                    <a onclick="share_facebook(this);" class="btn-social" href="#" data-url="{URL:members-growth-club/article/<?= $this->blog->slug; ?>}">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                    <a onclick="share_twitter(this);" class="btn-social" href="#" data-url="{URL:members-growth-club/article/<?= $this->blog->slug; ?>}">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                    <a class="btn-social copy_btn" href="#" data-clipboard-text="{URL:members-growth-club/article/<?= $this->blog->slug; ?>}">
                                        <i class="fa fa-copy"></i>
                                    </a>
                                </div>
                            </div>

                            <a class="btn btn-outline-warning" href="{URL:panel/club_blog}"><i class="fas fa-reply"></i>Back</a>
                        </div>
                    </div>

                    <div class="items_group items_group-wrap items_group-bottom">
                        <div class="items_left-side">
                            <div class="option-btns-list scroll-list">
                                <a href="{URL:panel/club_blog/edit/<?= $this->blog->id; ?>}" class="btn btn-rectangle_medium"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                <a class="btn btn-rectangle_medium active" title="Statistic"><i class="bs-tooltip fa fa-chart-bar"></i>Statistic</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <!-- Head -->
                <div class="flex-btw flex-vc mob_fc">
                    <h1>Views  <?= '"' . $this->blog->title . '"' ?></h1>
                </div>

                <!-- Table -->
                <div class="table-responsive mb-4 mt-4">
                    <table id="zero-config" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>User Id</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                            <?php foreach ($this->list as $item) { ?>
                                <tr>
                                    <td title="<?= $item->id; ?>">
                                        <?= $item->id ?>
                                    </td>
                                    <td title="<?= $item->id; ?>">
                                        <?= $item->firstname . ' ' . $item->lastname ?>
                                    </td>
                                    <td title="<?= $item->id; ?>">
                                        <?= $item->user_id ?>
                                    </td>
                                    <td data-sort="<?= $item->time ?>">
                                        <?= date('Y-m-d / H:i', $item->time); ?>
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
            "stripeClasses": [],
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 25
        });
    });
</script>
<!-- END PAGE LEVEL SCRIPTS -->
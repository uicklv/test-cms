<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?= _SITEDIR_ ?>assets/css/forms/switches.css">
<!-- END PAGE LEVEL STYLES -->
<script src="<?= _SITEDIR_ ?>public/js/backend/Sortable.min.js"></script>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <!-- Title ROW -->
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="statbox widget box box-shadow widget-top">
                <div class="widget-header">

                    <div class="items_group items_group-wrap">
                        <div class="items_left-side">
                            <div class="title-block">
                                <a class="btn-ellipse bs-tooltip fa fa-chart-line"></a>
                                <h1 class="page_title">Dashboard Settings</h1>
                            </div>
                        </div>

                        <div class="items_right-side">
                            <div class="items_small-block"></div>
                            <a class="btn btn-primary mt10-mob" href="{URL:panel/settings/dashboard/add}">
                                <i class="fas fa-plus"></i>
                                Add New Point
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6 pb-0">

                <!--   -->
                <script>
                    var itemsArray = []; // itemsArray <<<

                    function createList() {
                        var el = document.getElementById('selected_items');
                        var sortable = Sortable.create(el, {
                            group: 'shared',
                            handle: '.handle', // handle's class
                            filter: '.filtered', // 'filtered' class is not draggable
                            animation: 150,
                            preventOnFilter : true,
                            draggable : ".ds-item",
                            onSort: function (evt) {
                                let fromSectionPos = evt.oldIndex;
                                let toSectionPos = evt.newIndex;

                                // from
                                itemsArray.splice(fromSectionPos, 1);

                                // to
                                itemsArray.splice(toSectionPos, 0, evt.clone.attributes['item-id'].nodeValue);

                                var ids_row = "|" + itemsArray.join('||') + "|";
                                $('#sort_list').val(ids_row);

                                load('panel/settings/dashboard/save_sort', '#sort_list');
                            }
                        });
                    }
                </script>
                <input type="hidden" name="sort_list" id="sort_list" value="<?= post('sort_list', false, $this->dashboard_sort); ?>">
                <div class="dashboard-settings-block" id="selected_items">
                    <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                        <?php foreach ($this->list as $item) { ?>
                            <div class="ds-item" item-id="<?= $item->id; ?>">
                                <div class="ds-head">
                                    <h3 class="ds-name"><?= $item->title; ?></h3>
                                    <div class="ds-icon-block">
                                        <a class="ds-icon fa fa-pencil-alt"
                                           href="{URL:panel/settings/dashboard/edit/<?= $item->id; ?>}"></a>
                                        <a class="ds-icon fas fa-arrows-alt handle" style="cursor: pointer;"></a>
                                    </div>
                                </div>
                                <ul class="ds-info">
                                    <li style="width: 60px;"><span>ID</span> <?= $item->id; ?></li>
                                    <li><span>Value</span> <?= $item->count; ?></li>
                                    <li id="status_block_<?= $item->id ?>"  style="width: 98px;">
                                        <span>Status</span> <?= ($item->status === 'inactive' ? '<b style="color: red;">inactive</b>' : '<b style="color: green;">active</b>'); ?>
                                    </li>
                                    <li>
                                        <label class="switch s-icons s-outline s-outline-success" style="width: 48px;">
                                            <input type="checkbox" <?php if ($item->status == 'active') echo 'checked' ?>
                                            onchange="load('panel/settings/dashboard/change_status', 'item_id=<?= $item->id ?>')">
                                             <span class="slider round"></span>
                                        </label>
                                    </li>
                                </ul>
                                <script>
                                    itemsArray.push("<?= $item->id; ?>");
                                </script>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <script>
                    $(document).ready(function () {
                        createList();
                    });
                </script>
                <div class="clr"></div>

                <!-- Table -->
                <?php /*
                <div class="table-responsive mb-4 mt-4">
                    <table id="zero-config" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th class="max_w_60">ID</th>
                            <th>Title</th>
                            <th>Table</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th class="w_options">Options</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
                            <?php foreach ($this->list as $item) { ?>
                                <tr>
                                    <td><?= $item->id; ?></td>
                                    <td><?= $item->title; ?></td>
                                    <td><?= $item->table; ?></td>
                                    <td><?= $item->count; ?></td>
                                    <td><?= ($item->status === 'inactive' ? '<span style="color: red;">inactive</span>' : $item->status); ?></td>

                                    <td class="option__buttons">
                                        <a href="{URL:panel/settings/dashboard/edit/<?= $item->id; ?>}" class="bs-tooltip fa fa-pencil-alt" title="Edit"></a>
                                        <a href="{URL:panel/settings/dashboard/delete/<?= $item->id; ?>}" class="bs-tooltip fa fa-trash" title="Delete"></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
                */ ?>

            </div>
        </div>

    </div>
</div>

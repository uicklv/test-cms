<div id="control-container">
    <div id="button-holder">
        <a href="{URL:panel/form_tester/add}" onclick="load('panel/form_tester/add');" class="btn add">
            <i class="fas fa-plus-circle"></i> Add New Form
        </a>
        <div class="clr"></div>
    </div>
    <h1>
        <i class="fas fa-users"></i>Forms
    </h1>
    <hr/>

    <?php if (isset($success) && $success) { ?>
        <div class="success">
            <i class="fas fa-check-circle"></i><?php echo $success; ?>
        </div>
    <?php } ?>
    <?php if (isset($error) && $error) { ?>
        <div class="error">
            <i class="fas fa-check-circle"></i><?php echo $error; ?>
        </div>
    <?php } ?>

    <table id="team">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Modul/Action</th>
            <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Modul/Action</th>
            <th></th>
        </tr>
        </tfoot>
        <tbody>
        <?php if (isset($this->list) && is_array($this->list) && count($this->list)) { ?>
            <?php foreach ($this->list as $item) { ?>
                <tr>
                    <td>
                        <?php echo $item->id; ?>
                    </td>
                    <td>
                        <?php echo $item->name; ?>
                    </td>
                    <td>
                        <?php echo $item->action; ?>
                    </td>
                    <td>
                        <a onclick="load('form_tester/<?= $item->id; ?>')" class="icon fas fa-arrow-circle-down tooltip" target="_blank" title="Execute"></a>
                    <?php /*    <a href="{URL:panel/blog/edit/<?= $item->id; ?>}" class="icon fa fa-fw fa-pencil-alt tooltip" title="Edit"></a>*/ ?>
                        <a href="{URL:panel/data_generator/delete/<?= $item->id; ?>}" class="icon fa fa-fw fa-trash tooltip" title="Delete"></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<div id="copy-link-dialog" class="copy_link_modal" title="Copy Link">
    <input type="text" id="copy_link_profile_url" class="copy_link_modal_input" title="Copy Link" readonly value="">
</div>

<script>
    $(function () {
        $('#copy_link_profile_url').tooltipster({
            selfDestruction: false
        });

        var table = $('#team').DataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [[1, 'asc']],
            "aoColumnDefs": [{'bSortable': false, 'aTargets': [1]}],
            "iDisplayLength": 25,
            "stateSave": true,
            "colReorder": true
        });
        // Column Filter
        $('#team tfoot th').each(function () {
            var title = $('#team tfoot th').eq($(this).index()).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" value="" />');
        });
        var state = table.state.loaded();
        state && (table.columns().eq(0).each(function (a) {
            var b = state.columns[a].search;
            b.search && $("input", table.column(a).footer()).val(b.search)
        }), table.draw()), table.columns().eq(0).each(function (a) {
            $("input", table.column(a).footer()).on("keyup change", function () {
                table.column(a).search(this.value).draw()
            })
        });
    });
</script>
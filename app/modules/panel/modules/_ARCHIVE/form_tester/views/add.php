<div id="control-container">
    <div id="button-holder">
        <a href="{URL:panel/form_tester}" onclick="load('panel/form_tester');" class="btn add"><i class="fas fa-ban"></i>Cancel</a>
        <div class="clr"></div>
    </div>
    <h1>
        <i class="fas fa-users"></i>Form <i class="fas fa-caret-right"></i>New
    </h1>
    <hr/>

    <?php if (isset($success) && $success) { ?>
        <div class="success">
            <i class="fas fa-check-circle"></i><?= $success; ?>
        </div>
    <?php } ?>
    <?php if (isset($error) && $error) { ?>
        <div class="error">
            <i class="fas fa-check-circle"></i><?= $error; ?>
        </div>
    <?php } ?>
    <?php //echo validation_errors('<div class="error"><i class="fas fa-check-circle"></i>', '</div>'); ?>


    <form id="form_box"  method="post" enctype="multipart/form-data">
        <div class="form-section">
            <span class="heading">General</span>

            <div class="col half_column_left">
                <label for="table"> Form name</label>
                <input type="text" name="name" id="name">
            </div>
            <div class="col half_column_right">
                <label for="number">Modul/Action</label>
                <input type="text" name="action" id="action" >
            </div>
            <a href="#" class="btn add" id="add_button"><i class="fas fa-plus-circle"></i> Add </a>
            <div class="clr"></div>

            <div id="div_0">
                <div class="col half_column_left">
                    <label for="column"> Column name</label>
                    <input type="text" id="column" name="column[]" >
                </div>
                <div class="col half_column_right">
                    <label for="value"> Value</label>
                    <input type="text" id="value" name="value[]" >
                </div>
                <div class="clr"></div>
            </div>

            <div id="div_rows">

            </div>

            <div class="form-section">
                <button type="submit" name="submit" class="btn submit" onclick="load('panel/form_tester/add', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
                <div class="clr"></div>
            </div>
    </form>
</div>

<link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/js/sample.js"></script>
<script>

    let buttonsAdd = document.getElementById('add_button');

    let counter = 0;
    buttonsAdd.addEventListener('click', function (event) {
        event.preventDefault();
        counter++;
        let htmlString =
            '            <div id="div_' + counter + '">\n' +
            '            <div class="col half_column_left">\n' +
            '                <label for="column"> Column name</label>\n' +
            '                <input type="text" id="column" name="column[]" >\n' +
            '            </div>\n' +
            '            <div class="col half_column_right">\n' +
            '               <label for="value"> Value</label>\n' +
            '               <input type="text" id="value" name="value[]" >\n' +
            '            </div>\n' +
            '            <a href="#" class="btn add remove" id="remove_' + counter + '"><i class="fas fa-plus-circle"></i> Remove </a>\n' +
            '            <div class="clr"></div>\n' +
            '            </div>\n';

        document.getElementById('div_rows').innerHTML += htmlString ;


        let removeButtons = document.getElementsByClassName('remove');

        for (let i= 0; i < removeButtons.length; i++)
        {
            removeButtons[i].addEventListener('click', function (event) {
                event.preventDefault();

                //remove div with name and type column
                document.getElementById('div_' + removeButtons[i].getAttribute('id').slice(7)).remove();
            });
        }
    });


</script>
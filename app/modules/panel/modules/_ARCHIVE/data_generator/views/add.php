<div id="control-container">
    <div id="button-holder">
        <a href="{URL:panel/data_generator}" onclick="load('panel/data_generator');" class="btn add"><i class="fas fa-ban"></i>Cancel</a>
        <div class="clr"></div>
    </div>
    <h1>
        <i class="fas fa-users"></i> Fake Data <i class="fas fa-caret-right"></i>New
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
                <label for="table"> Table name</label>
                <input type="text" name="table" id="table">
            </div>
            <div class="col half_column_right">
                <label for="number">Number of rows</label>
                <input type="text" name="number" id="number" >
            </div>
            <a href="#" class="btn add" id="add_button"><i class="fas fa-plus-circle"></i> Add </a>
            <div class="clr"></div>

            <div id="div_0">
                <div class="col half_column_left">
                    <label for="column"> Column name</label>
                    <input type="text" name="column[]" >
                </div>
                <div class="col half_column_right">
                    <label for="type">Type</label>
                    <select name="type[]" class="select_type" id="select_0">
                        <option value="blog_title">blog title</option>
                        <option value="content_short">content short</option>
                        <option value="content_short">content</option>
                        <option value="job_title">job title</option>
                        <option value="company">company</option>
                        <option value="email">email</option>
                        <option value="telephone">telephone</option>
                        <option value="url">url</option>
                        <option value="first_name">first name</option>
                        <option value="last_name">last name</option>
                        <option value="full_name">full name</option>
                        <option value="time">time</option>
                        <option value="time_expire">time_expire</option>
                        <option value="city">city</option>
                        <option value="country">country</option>
                        <option value="contract_type">contract type</option>
                        <option value="salary_value">salary value</option>
                        <option value="image">image</option>
                        <option value="slug">slug</option>
                        <option value="sector">sector</option>
                        <option value="existing_table">existing table</option>
                        <option value="uniq_job_id">uniq job id</option>
                        <option value="password">password</option>
                        <option value="consultant">consultant</option>
                    </select>
                </div>
                <div class="clr"></div>
                <div id="div_image_0"></div>
            </div>

            <div id="div_rows">

            </div>

            <div class="form-section">
                <button type="submit" name="submit" class="btn submit" onclick="load('panel/data_generator/add', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
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
            '                <input type="text" name="column[]" >\n' +
            '            </div>\n' +
            '            <div class="col half_column_right">\n' +
            '                <label for="type">Type</label>\n' +
            '                <select name="type[]" class="select_type" id="select_' + counter + '">\n' +
            '                    <option value="blog_title">blog title</option>\n' +
            '                    <option value="content_short">content short</option>\n' +
            '                    <option value="content_short">content</option>\n' +
            '                    <option value="job_title">job title</option>\n' +
            '                    <option value="company">company</option>\n' +
            '                    <option value="email">email</option>\n' +
            '                    <option value="telephone">telephone</option>\n' +
            '                    <option value="url">url</option>\n' +
            '                    <option value="first_name">first name</option>\n' +
            '                    <option value="last_name">last name</option>\n' +
            '                    <option value="full_name">full name</option>\n' +
            '                    <option value="time">time</option>\n' +
            '                    <option value="time_expire">time_expire</option>\n'+
            '                    <option value="city">city</option>\n' +
            '                    <option value="country">country</option>\n' +
            '                    <option value="contract_type">contract type</option>\n' +
            '                    <option value="salary_value">salary value</option>\n' +
            '                    <option value="image">image</option>\n'+
            '                    <option value="slug">slug</option>\n'+
            '                    <option value="sector">sector</option>\n'+
            '                    <option value="existing_table">existing table</option>\n'+
            '                    <option value="uniq_job_id">uniq job id</option>\n'+
            '                    <option value="password">password</option>\n'+
            '                    <option value="consultant">consultant</option>\n'+
            '                </select>\n' +
            '            </div>\n' +
            '            <a href="#" class="btn add remove" id="remove_' + counter + '"><i class="fas fa-plus-circle"></i> Remove </a>\n' +
            '            <div class="clr"></div>\n' +
            '            <div id="div_image_' + counter + '"></div>\n' +
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
        imageFunction();
    });

    let imageFunction = function () {
        let typeSelects = document.getElementsByClassName('select_type');

        let count = 0;

        for (let i= 0; i < typeSelects.length; i++) {


            typeSelects[i].addEventListener('change', function (event) {

                count++;

                let imageString = ' <div class="col">\n'+
                    '                <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>\n'+
                    '                <div class="choose-file modern">\n'+
                    '                    <input type="hidden" name="image[]" id="image' + count +'" value="">\n'+
                    '                    <input type="file" accept="image/jpeg,image/png,image/gif" onchange="initFile(this); load(\'panel/upload_image/\', \'name='+ Date.now() + count +'\', \'field=#image' + count + '\', \'preview=#preview_image' + count + '\');">\n'+
                    '                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose image</a>\n'+
                    '                    <div id="preview_image' + count + '" class="preview_image"></div>\n'+
                    '                </div>\n'+
                    '            </div>';

                let columnString =
                    '            <div class="col">\n' +
                    '                <label for="number">Table and column (categories/id)</label>\n' +
                    '                <input type="text" name="existing_table[]"  >\n' +
                    '            </div>';

                let divImage =  document.getElementById('div_image_' + typeSelects[i].getAttribute('id').slice(7));

                if (typeSelects[i].value == 'image') {
                    if (divImage.innerHTML == '')
                        divImage.innerHTML += imageString;
                } else if(typeSelects[i].value == 'existing_table') {
                    if (divImage.innerHTML == '')
                        divImage.innerHTML += columnString;
                }else {
                    divImage.innerHTML = '';
                }
            });
        }
    };

    imageFunction();

</script>
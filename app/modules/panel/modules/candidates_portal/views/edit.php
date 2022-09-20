<form id="form_box" action="{URL:panel/candidates_portal/edit/<?= $this->user->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-users" href="{URL:panel/candidates_portal}"></a>
                                    <h1 class="page_title"><?= $this->user->firstname . ' ' . $this->user->lastname ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/candidates_portal}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>In process Candidate</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name</label>
                            <input class="form-control" type="text" name="firstname" id="firstname" value="<?= post('firstname', false, $this->user->firstname); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input class="form-control" type="text" name="lastname" id="lastname" value="<?= post('lastname', false, $this->user->lastname); ?>"><!--required-->
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="notice_period">Notice Period</label>
                            <input class="form-control" type="text" name="notice_period" id="notice_period" value="<?= post('notice_period', false, $this->user->notice_period); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="location">Location</label>
                            <input class="form-control" type="text" name="location" id="location" value="<?= post('location', false, $this->user->location); ?>"><!--required-->
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="linkedin">LinkedIn URL</label>
                            <input class="form-control" type="text" name="linkedin" id="linkedin" value="<?= post('linkedin', false, $this->user->linkedin); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="git_hub">GitHub URL</label>
                            <input class="form-control" type="text" name="git_hub" id="git_hub" value="<?= post('git_hub', false, $this->user->git_hub); ?>"><!--required-->
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stack_overflow">Stackoverflow URL</label>
                            <input class="form-control" type="text" name="stack_overflow" id="stack_overflow" value="<?= post('stack_overflow', false, $this->user->stack_overflow); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="site">Site URL</label>
                            <input class="form-control" type="text" name="site" id="site" value="<?= post('site', false, $this->user->site); ?>"><!--required-->
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="link1">Link</label>
                            <input class="form-control" type="text" name="link1" id="link1" value="<?= post('link1', false, $this->user->link1); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="link2">Link</label>
                            <input class="form-control" type="text" name="link2" id="link2" value="<?= post('link2', false, $this->user->link2); ?>"><!--required-->
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="salary">Salary</label>
                            <input class="form-control" type="text" name="salary" id="salary" value="<?= post('salary', false, $this->user->salary); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="file">CV
                                <small>
                                    <i>(Cv's must be under <?= file_upload_max_size_format() ?>, and <?= strtoupper(implode(', ', array_keys(File::$allowedDocFormats))) ?> format)</i>
                                </small>
                                <?= ($this->user->cv ? '<a href="'. _SITEDIR_ .'data/candidates/' . $this->user->cv . '" download="' . $this->user->firstname . '-' . $this->user->lastname .  '.' . File::format($this->user->cv) . '"><i class="fas fa-download"></i> Download</a>' : '') ?>
                            </label>

                            <div class="flex flex-vc" >
                                <div class="choose-file">
                                    <input type="hidden" name="file" id="file" value="<?= post('file', false, $this->user->cv); ?>">
                                    <input type="file"  accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('panel/upload/', 'field=#file', 'preview=#file_name');">
                                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                </div>
                                <div id="file_name"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Vacancies</label>
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->vacancies) && is_array($this->vacancies) && count($this->vacancies) > 0) { ?>
                                    <?php foreach ($this->vacancies as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="vacancy_ids[]" id="vacancy_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('vacancy_ids'), $item->id, $this->user->vacancy_ids); ?>
                                            ><label class="custom-control-label" for="vacancy_<?=$item->id?>"><?= $item->title; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="description">What’s great about this candidate for the role</label>
                            <textarea name="description" id="description" rows="20"><?= post('description', false, $this->user->description); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Hired Candidate</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" value="<?= post('email', false, $this->user->email); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tel">Telephone Number</label>
                            <input class="form-control" type="tel" name="tel" id="tel" value="<?= post('tel', false, $this->user->tel); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dob">Day of Birth</label>
                            <input class="form-control" type="text" name="dob" id="dob" value="<?= post('dob', false,  date('d/m/Y', $this->user->dob)); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">Address</label>
                            <input class="form-control" type="tel" name="address" id="address" value="<?= post('address', false, $this->user->address); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="file">ID
                                <small>
                                    <i>(ID's must be under <?= file_upload_max_size_format() ?>, and <?= strtoupper(implode(', ', array_keys(File::$allowedDocFormats))) ?> format)</i>
                                </small>
                                <?= ($this->user->id_file ? '<a href="'. _SITEDIR_ .'data/candidates/' . $this->user->id_file . '" download="' . $this->user->firstname . '-' . $this->user->lastname .  '.' . File::format($this->user->id_file) . '"><i class="fas fa-download"></i> Download</a>' : '') ?>
                            </label>

                            <div class="flex flex-vc" >
                                <div class="choose-file">
                                    <input type="hidden" name="id_file" id="id_file" value="<?= post('id_file', false, $this->user->id_file); ?>">
                                    <input type="file"  accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('panel/upload/', 'field=#id_file', 'preview=#id_file_name');">
                                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                </div>
                                <div id="id_file_name"></div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="file">Passport
                                <small>
                                    <i>(Passport's must be under <?= file_upload_max_size_format() ?>, and <?= strtoupper(implode(', ', array_keys(File::$allowedDocFormats))) ?> format)</i>
                                </small>
                                <?= ($this->user->passport ? '<a href="'. _SITEDIR_ .'data/candidates/' . $this->user->passport . '" download="' . $this->user->passport . '-' . $this->user->passport .  '.' . File::format($this->user->passport) . '"><i class="fas fa-download"></i> Download</a>' : '') ?>
                            </label>

                            <div class="flex flex-vc" >
                                <div class="choose-file">
                                    <input type="hidden" name="passport" id="passport" value="<?= post('passport', false, $this->user->passport); ?>">
                                    <input type="file"  accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('panel/upload/', 'field=#passport', 'preview=#passport_name');">
                                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                </div>
                                <div id="passport_name"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="start_date">Start Date</label>
                            <input class="form-control" type="text" name="start_date" id="start_date" value="<?= post('start_date', false, date('d/m/Y', $this->user->start_date)); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="hired_salary">Salary</label>
                            <input class="form-control" type="text" name="hired_salary" id="hired_salary" value="<?= post('hired_salary', false, $this->user->hired_salary); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="sector">Offer accepted</label>
                            <select class="form-control" name="customer_offer" id="customer_offer" required>
                                <option value="0">-</option>
                                <?php if (is_array($this->customers) && count($this->customers) > 0) foreach ($this->customers as $item){ ?>
                                        <option value="<?= $item->id; ?>" <?= checkOptionValue(post('customer_offer'), $item->id, $this->user->customer_offer) ?>>
                                            <?= $item->firstname . ' ' .$item->lastname ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox checkbox-info">
                                <input type="checkbox" class="custom-control-input" name="hide_offers" id="hide_offers"  value="yes" <?php if ($this->user->hide_offers === 'no') echo 'checked'?>>
                                <label class="custom-control-label" for="hide_offers">Hide from offers</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/candidates_portal}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/candidates_portal/edit/<?= $this->user->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>


<link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/js/sample.js"></script>
<script>
    var editorField;

    function setTextareaValue() {
        $('#description').val(editorField.getData());
    }

    $(function () {
        $('#dob').datepicker({dateFormat: 'dd/mm/yy'});

        $('#start_date').datepicker({dateFormat: 'dd/mm/yy'});

        initSlug('#slug', '#firstname,#lastname');

        $("#firstname, #lastname").keyup(function () {
            initSlug('#slug', '#firstname,#lastname');
        });

        editorField = CKEDITOR.replace('description', {
            htmlEncodeOutput: false,
            wordcount: {
                showWordCount: true,
                showCharCount: true,
                countSpacesAsChars: true,
                countHTML: false,
            },
            removePlugins: 'zsuploader',

            filebrowserBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=files',
            filebrowserImageBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=images',
            filebrowserUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=files',
            filebrowserImageUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=images'
        });
    });
</script>



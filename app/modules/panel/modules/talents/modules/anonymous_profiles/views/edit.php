<style>
    .w-dropdown-list a {
        cursor: pointer;
    }
    .w-dropdown {
        width: 100%;
    }

    .suggests_wrap {
        position: relative;
        z-index: 9999;
    }

    .suggests_result {
        position: absolute;
        top: 75px;
        left: 0;
        right: 0;
        background-color: white;
        width: 100%;
        min-height: 0;
        max-height: 300px;
        /*margin-top: -10px;*/
        border: 1px solid #2FAADF;
        border-radius: 10px;
        box-sizing: border-box;
        overflow-y: auto;
        z-index: 99999;
    }

    .suggests_result:empty {
        display: none;
    }

    .suggests_result .pc-item {
        padding: 0 20px;
        line-height: 60px;
        font-size: 20px;
    }

    .suggests_result .pc-item:hover {
        background-color: #2FAADF;
        color: white;
        cursor: pointer;
    }

    .hide {
        display: none;
    }
</style>
<script>
    function fillPostcode(el) {
        var code = trim($(el).text());
        $('#postcode').val(code);
        $('.suggests_result').html('');
    }

    function closeSuggest() {
        $('.suggests_result').html('');
    }

    function suggestPostcode(el) {
        if (trim($(el).val())) {
            load('panel/talents/open_profiles/postcode', 'postcode#postcode');
            console.log('suggestPostcode');
        }
    }
</script>

<form id="form_box" action="{URL:panel/talents/anonymous_profiles/edit/<?= $this->user->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-address-card" href="{URL:panel/talents/anonymous_profiles}"></a>
                                    <h1 class="page_title"><?= $this->user->job_title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/talents/anonymous_profiles}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="job_title">Current Job Title</label>
                            <input class="form-control" type="text" name="job_title" id="job_title" value="<?= post('job_title', false, $this->user->job_title); ?>"><!--required-->
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ref">Reference Code</label>
                            <input class="form-control" type="text" name="ref" id="ref" value="<?= post('ref', false, $this->user->ref); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="keywords">Top 3 Skills</label>
                            <select class="form-control tagging" multiple="multiple" id="keywords" name="keywords">
                                <?php if (is_array($this->keywords) && count($this->keywords) > 0) {
                                    foreach ($this->keywords as $item) { ?>
                                        <option value="<?=$item?>" selected><?= $item ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="statbox widget box box-shadow">
                                <label for="quote">Quote about the Candidate</label>
                                <div class="form-group">
                                    <textarea class="form-control" name="quote" id="quote" rows="20"><?= post('quote', false, $this->user->quote); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="locations">Location of Candidate</label>
                            <select class="form-control tagging" multiple="multiple" id="locations" name="locations">
                                <?php if (is_array($this->locations) && count($this->locations) > 0) { ?>
                                    <?php foreach ($this->locations as $item) { ?>
                                        <option value="<?= $item ?>" selected><?= $item ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="postcode">Candidate Postcode/ Zip Code</label>
                            <input class="form-control" type="text" name="postcode" id="postcode" onkeyup="suggestPostcode(this);" value="<?= post('postcode', false, $this->user->postcode); ?>">
                            <div class="suggests_result"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="radius">How far will this candidate commute? (in Miles or Km)</label>
                            <input class="form-control" type="number" name="radius" id="radius" min="0" step="0.01" value="<?= post('radius', false, $this->user->radius); ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" name="distance_type" id="distance_type" required style="margin-top: 30px;">
                                <option value="KM" <?= checkOptionValue(post('distance_type'), 'KM', $this->user->distance_type); ?>>KM</option>
                                <option value="MILES" <?= checkOptionValue(post('distance_type'), 'MILES', $this->user->distance_type); ?>>MILES</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="relocate">Will this candidate relocate?</label>
                            <select class="form-control" name="relocate" id="relocate" required>
                                <option value="no" <?= checkOptionValue(post('relocate'), 'no', $this->user->relocate); ?>>No</option>
                                <option value="yes" <?= checkOptionValue(post('relocate'), 'yes', $this->user->relocate); ?>>Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="contract">Contract Preference</label>
                            <select class="form-control" name="contract" id="contract" required>
                                <option value="contract" <?= checkOptionValue(post('contract'), 'contract', $this->user->contract); ?>>Contract</option>
                                <option value="permanent" <?= checkOptionValue(post('contract'), 'permanent', $this->user->contract); ?>>Permanent</option>
                                <option value="both" <?= checkOptionValue(post('contract'), 'both', $this->user->contract); ?>>Contract or Permanent</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="availability">Availability(Notice Period)</label>
                            <input class="form-control" type="text" name="availability" id="availability" value="<?= post('availability', false, $this->user->availability); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="min_annual_salary">Min Annual Salary for Permanent Positions (in £/$/€)</label>
                            <input class="form-control" type="number" name="min_annual_salary" id="min_annual_salary" min="0" step="0.01" value="<?= post('min_annual_salary', false, $this->user->min_annual_salary); ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" name="annual_currency" id="annual_currency" required style="margin-top: 30px;">
                                <option value="£" <?= checkOptionValue(post('annual_currency'), '£', $this->user->annual_currency); ?>>£</option>
                                <option value="$" <?= checkOptionValue(post('annual_currency'), '$', $this->user->annual_currency); ?>>$</option>
                                <option value="€" <?= checkOptionValue(post('annual_currency'), '€', $this->user->annual_currency); ?>>€</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="min_daily_salary">Min Daily Salary for Contract Roles (in £/$/€)</label>
                            <input class="form-control" type="number" name="min_daily_salary" id="min_daily_salary" min="0" step="0.01" value="<?= post('min_daily_salary', false, $this->user->min_daily_salary); ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" name="daily_currency" id="daily_currency" required style="margin-top: 30px;">
                                <option value="£" <?= checkOptionValue(post('daily_currency'), '£', $this->user->daily_currency); ?>>£</option>
                                <option value="$" <?= checkOptionValue(post('daily_currency'), '$', $this->user->daily_currency); ?>>$</option>
                                <option value="€" <?= checkOptionValue(post('daily_currency'), '€', $this->user->daily_currency); ?>>€</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="min_hourly_salary">Min Hourly Salary for Contract Roles (in £/$/€)</label>
                            <input class="form-control" type="number" name="min_hourly_salary" id="min_hourly_salary" min="0" step="0.01" value="<?= post('min_hourly_salary', false, $this->user->min_hourly_salary); ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" name="hourly_currency" id="hourly_currency" required style="margin-top: 30px;">
                                <option value="£" <?= checkOptionValue(post('hourly_currency'), '£', $this->user->hourly_currency); ?>>£</option>
                                <option value="$" <?= checkOptionValue(post('hourly_currency'), '$', $this->user->hourly_currency); ?>>$</option>
                                <option value="€" <?= checkOptionValue(post('hourly_currency'), '€', $this->user->hourly_currency); ?>>€</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="published_time">Published Date</label>
                            <input class="form-control" type="text" name="published_time" id="published_time" value="<?= post('published_time', false, date("d/m/Y", $this->user->published_time ? $this->user->published_time : time())); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="languages">Languages Spoken</label>
                            <select class="form-control tagging" multiple="multiple" id="languages" name="languages">
                                <?php if (is_array($this->languages) && count($this->languages) > 0) { ?>
                                    <?php foreach ($this->languages as $item) { ?>
                                        <option value="<?= $item ?>" selected><?= $item ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="education">Education</label>
                            <input class="form-control" type="text" name="education" id="education" value="<?= post('education', false, $this->user->education); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Hot Lists</label>
                            <input type="text" class="form-control" id="list_filter" value="" autocomplete="off" placeholder="Start typing to filter sectors below">
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->hotlists) && is_array($this->hotlists) && count($this->hotlists) > 0) { ?>
                                    <?php foreach ($this->hotlists as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="hotlist_ids[]" id="hotlist_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('hotlist_ids'), $item->id, $this->user->hotlist_ids); ?>>
                                            <label class="custom-control-label lists" for="hotlist_<?=$item->id?>"><?= $item->name; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CV -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Key Skills/ Experience</h4>
                    <div class="form-group">
                        <textarea class="form-control" name="skills" id="skills" rows="20"><?= post('skills', false, $this->user->skills); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Additional information -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="video_type">Video Type</label>
                            <select class="form-control" name="video_type" id="video_type" required>
                                <option value="file" <?= checkOptionValue(post('video_type'), 'file', $this->user->video_type); ?>>File</option>
                                <option value="youtube" <?= checkOptionValue(post('video_type'), 'youtube', $this->user->video_type); ?>>Youtube</option>
                                <option value="vimeo" <?= checkOptionValue(post('video_type'), 'vimeo', $this->user->video_type); ?>>Vimeo</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="video_link" id="video_link">Video Link</label>
                            <input type="text" class="form-control" name="video_link" value="<?= post('video_link', false, $this->user->video_link) ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="file">Video
                                <small>
                                    <i>(Videos must be under <?= file_upload_max_size_format() ?>, and <?= strtoupper(implode(', ', array_keys(File::$allowedVideoFormats))) ?> format)</i>
                                </small>
                                <?= ( $this->user->video_file ? '<a href="'.  $this->user->video_file . '" download="video.' . File::format($this->user->video_file) . '"><i class="fas fa-download"></i> Download</a>' : '') ?>
                            </label>
                            <div class="flex-btw flex-vc" id="video_file">
                                <div class="choose-file">
                                    <input type="hidden" name="video_file" id="video_f" value="<?= post($this->user->video_file, false,  $this->user->video_file ); ?>">
                                    <input type="file" accept="video/mp4, video/avi, video/mkv" onchange="initFile(this); load('panel/uploadVideo/', 'name=<?= randomHash(); ?>', 'preview=#video_preview', 'field=#video_f');">
                                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                </div>
                                <div id="video_preview>"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/talents/anonymous_profiles}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success"
                           onclick="setTextareaValue(); load('panel/talents/anonymous_profiles/edit/<?= $this->user->id; ?>', 'form:#form_box'); return false;">
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
<script src="<?= _SITEDIR_ ?>public/js/backend/select2.min.js"></script>
<script src="<?= _SITEDIR_ ?>public/js/backend/custom-select2.js"></script>
<script>
    $('#list_filter').keyup(function () {
        var q = $(this).val().toLowerCase().trim();

        if (q.length > 0) {
            $('.lists').each(function (i, list) {
                if ($(list).text().trim().toLowerCase().indexOf(q) === -1) {
                    $(list).parent().addClass('hidden')
                } else {
                    $(list).parent().removeClass('hidden')
                }
            });
        } else {
            $('.lists').each(function (i, list) {
                $(list).parent().removeClass('hidden')
            });
        }
    });

    if($('#video_type option:selected').val() != 'file') {
        $('#video_file').parent().fadeOut();
        $('#video_link').parent().fadeIn();
    } else {
        $('#video_file').parent().fadeIn();
        $('#video_link').parent().fadeOut();
    }


    $('#video_type').change(function () {
        if($('#video_type option:selected').val() != 'file') {
            $('#video_file').parent().fadeOut();
            $('#video_link').parent().fadeIn();
        } else {
            $('#video_file').parent().fadeIn();
            $('#video_link').parent().fadeOut();
        }
    });




    $("#keywords").select2({
        maximumSelectionLength: 3,
        tags:true,
    });

    $("#locations").select2({
        maximumSelectionLength: 3,
        tags:true
    });

    $("#languages").select2({
        maximumSelectionLength: 5,
        tags:true
    });

    var editorField;
    var editorField2;

    function setTextareaValue() {
        $('#quote').val(editorField.getData());
        $('#skills').val(editorField2.getData());
    }

    $(function () {
        $('#published_time').datepicker({dateFormat: 'dd/mm/yy'});

        editorField = CKEDITOR.replace('quote', {
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

        editorField2 = CKEDITOR.replace('skills', {
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

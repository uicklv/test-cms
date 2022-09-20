<script src="<?= _SITEDIR_ ?>public/js/backend/Sortable.min.js"></script>

<form id="form_box" action="{URL:panel/vacancies/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing sticky-container">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-top">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fa fa-briefcase" href="{URL:panel/vacancies}"></a>
                                    <h1 class="page_title"><?= $this->edit->title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side">
                                <div class="items_small-block">
                                    <a href="{URL:job/<?= $this->edit->slug; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" title="View Job" target="_blank"></a>

                                    <div class="social-btns-list">
                                        <a onclick="share_linkedin(this);" class="btn-social" href="#" data-url="{URL:job/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                        <a onclick="share_facebook(this);" class="btn-social" href="#" data-url="{URL:job/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                        <a onclick="share_twitter(this);" class="btn-social" href="#" data-url="{URL:job/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                        <a class="btn-social copy_btn" href="#" data-clipboard-text="{URL:job/<?= $this->edit->slug; ?>}">
                                            <i class="fa fa-copy"></i>
                                        </a>
                                    </div>
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/vacancies}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                        <div class="items_group items_group-wrap items_group-bottom">
                            <div class="items_left-side">
                                <div class="option-btns-list scroll-list">
                                    <a class="btn btn-rectangle_medium active"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                    <a href="{URL:panel/vacancies/applications/<?= $this->edit->id; ?>}" class="btn btn-rectangle_medium" title="Applications List"><i class="bs-tooltip far fa-user"></i>Applications</a>
                                    <a href="{URL:panel/vacancies/statistic/<?= $this->edit->id; ?>}" class="btn btn-rectangle_medium" title="Statistic"><i class="bs-tooltip fa fa-chart-bar"></i>Statistic</a>
                                </div>
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
                            <label for="title">Job Title</label>
                            <input class="form-control" type="text" name="title" id="title" value="<?= post('title', false, $this->edit->title); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ref">Job Ref (Letters, numbers and hyphens (-) only)</label>
                            <input class="form-control" type="text" name="ref" id="ref" value="<?= post('ref', false, $this->edit->ref); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Industries/Sectors</label>
                            <input type="text" class="form-control" id="sector_filter" value="" autocomplete="off" placeholder="Start typing to filter sectors below">
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->sectors) && is_array($this->sectors) && count($this->sectors) > 0) { ?>
                                    <?php foreach ($this->sectors as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="sector_ids[]" id="sector_<?=$item->id?>" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('sector_ids'), $item->id, $this->edit->sector_ids); ?>
                                            ><label class="custom-control-label sectors" for="sector_<?=$item->id?>"><?= $item->name; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Locations</label>
                            <input type="text" class="form-control" id="location_filter" value="" autocomplete="off" placeholder="Start typing to filter locations below">
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->locations) && is_array($this->locations) && count($this->locations) > 0) { ?>
                                <?php foreach ($this->locations as $item) { ?>
                                    <div class="custom-control custom-checkbox checkbox-info">
                                        <input class="custom-control-input" type="checkbox" name="location_ids[]" id="location_<?=$item->id?>" value="<?= $item->id; ?>"
                                            <?= checkCheckboxValue(post('location_ids'), $item->id, $this->edit->location_ids); ?>
                                        ><label class="custom-control-label locations" for="location_<?=$item->id?>"><?= $item->name; ?></label>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="salary_value">Salary</label>
                            <input class="form-control" type="text" name="salary_value" id="salary_value" value="<?= post('salary_value', false, $this->edit->salary_value); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contract_type">Contract Type</label>
                            <select class="form-control" name="contract_type" id="contract_type" required>
                                <option value="permanent" <?= checkOptionValue(post('contract_type'), 'permanent', $this->edit->contract_type); ?>>Permanent</option>
                                <option value="temporary" <?= checkOptionValue(post('contract_type'), 'temporary', $this->edit->contract_type); ?>>Temporary</option>
                                <option value="contract" <?= checkOptionValue(post('contract_type'), 'contract', $this->edit->contract_type); ?>>Contract</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="time">Date Published</label>
                            <input class="form-control" type="text" name="time" id="time" value="<?= post('time', false, date("d/m/Y", $this->edit->time)); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="time_expire">Date Expires</label>
                            <input class="form-control" type="text" name="time_expire" id="time_expire" value="<?= post('time_expire', false, date("d/m/Y", $this->edit->time_expire)); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="package">Package</label>
                            <input class="form-control" type="text" name="package" id="package" value="<?= post('package', false, $this->edit->package); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="postcode">Postcode</label>
                            <input class="form-control" type="text" name="postcode" id="postcode" value="<?= post('postcode', false, $this->edit->postcode); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="posted" class="bold red">Published</label>
                            <select class="form-control" name="posted" id="posted" required>
                                <option value="yes" <?= checkOptionValue(post('posted'), 'yes', $this->edit->posted); ?>>
                                    Yes
                                </option>
                                <option value="no" <?= checkOptionValue(post('posted'), 'no', $this->edit->posted); ?>>
                                    No
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Internal</label>
                            <div class="custom-control custom-checkbox checkbox-info">
                                <input type="checkbox" class="custom-control-input" name="internal" id="internal"  value="1" <?= $this->edit->internal ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="internal">Display on work for us page</label>
                            </div>
                        </div>
                        <?php /*
                        <div class="form-group col-md-6">
                            <!-- Image -->
                            <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->blog->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/vacancy/<?= post('image', false, $this->edit->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                        */ ?>
                    </div>
                </div>
            </div>

            <!-- Tech Stack -->
            <script>
                var itemsArray = []; // itemsArray <<<

                function createList() {
                    var el = document.getElementById('selected_tech');
                    var sortable = Sortable.create(el, {
                        group: 'shared', // set both lists to same group
                        handle: '.handle', // handle's class
                        filter: '.filtered', // 'filtered' class is not draggable
                        animation: 150,
                        onSort: function (evt) {
                            let fromSection = $(evt.from).attr('id');
                            let toSection = $(evt.to).attr('id');
                            let fromSectionPos = evt.oldIndex;
                            let toSectionPos = evt.newIndex;

                            // from
                            if (fromSection === 'selected_tech')
                                itemsArray.splice(fromSectionPos, 1);

                            // to
                            if (toSection === 'selected_tech')
                                itemsArray.splice(toSectionPos, 0, evt.clone.attributes['item-id'].nodeValue);

                            var ids_row = "|" + itemsArray.join('||') + "|";
                            $('#tech_stack').val(ids_row);
                            // $('#tech_stack').val("|" + itemsArray.join('||') + "|");
                            $('#num_co').text(itemsArray.length);
                        }
                    });
                }
            </script>
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <h4>Tech Stack <span style="color: #737373; font-weight: 400;">(Drag & drop tech to right side)</b></span></h4>
                            <input type="hidden" name="tech_stack" id="tech_stack" value="<?= post('tech_stack', false, $this->edit->tech_stack); ?>">

                            <?php
                            $selectedCompanies = array();
                            if ($this->tech) { ?>
                                <div class="add_cat mt_20">
                                    <div id="tech_block" class="vb400">
                                        <?php
                                        foreach ($this->tech as $row) {
                                            if (strpos($this->edit->tech_stack, '|'.$row->id.'|') !== false) {
                                                $selectedCompanies[] = $row;
                                                continue;
                                            }
                                            ?>
                                            <div class="company-item nos" item-id="<?= $row->id; ?>">
                                                <label for="co_<?php echo $row->id; ?>" class="flex flex-vc">
                                                    <div class="handle"><span class="icon_sort fas fa-sort"></span></div>
                                                    <div style="padding: 2px 20px; max-width: 800px;">
                                                        <div class="flex" style="color: #bc9856; line-height: 30px; position: relative;">
                                                            <div class="cas_logo"><img src="<?= _SITEDIR_ ?>data/tech_stack/<?= $row->image; ?>" alt=""></div>
                                                            <span style="margin-left: 60px;"><?= $row->name; ?></span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        var el_ser = document.getElementById('tech_block');
                                        var sortable_ser = Sortable.create(el_ser, {
                                            group: {
                                                name: 'shared', // set both lists to same group
                                                pull: true,
                                                // put: false // Do not allow items to be put into this list
                                            },
                                            sort: false,
                                            handle: '.handle', // handle's class
                                            filter: '.filtered', // 'filtered' class is not draggable
                                            animation: 150
                                        });

                                        console.log(sortable_ser);
                                    });
                                </script>
                            <?php } ?>
                        </div>


                        <div class="form-group col-md-6 mb-0">
                            <h4>
                                Selected Tech Stack <span style="color: #737373; font-weight: 400;">(Selected: <b id="num_co"><?= count($selectedCompanies); ?></b>/<b style="font-size: 12px;"><?= count($this->tech); ?>)</b></span>
                            </h4>

                            <div class="add_cat mt_20">
                                <div id="selected_tech" class="vb400">
                                    <?php
                                    $compArr = explode('||', trim($this->edit->tech_stack, '|'));
                                    foreach ($compArr as $elem) {
                                        foreach ($selectedCompanies as $row) {
                                            if ($elem != $row->id)
                                                continue;
                                            ?>
                                            <div class="company-item nos" item-id="<?= $row->id; ?>">
                                                <label for="co_<?php echo $row->id; ?>" class="flex flex-vc">
                                                    <div class="handle"><span class="icon_sort fas fa-sort"></span></div>
                                                    <div style="padding: 2px 20px; max-width: 800px;">
                                                        <div class="flex" style="color: #bc9856; line-height: 30px; position: relative;">
                                                            <div class="cas_logo"><img src="<?= _SITEDIR_ ?>data/tech_stack/<?= $row->image; ?>" alt=""></div>
                                                            <span style="margin-left: 60px;"><?= $row->name; ?></span>
                                                        </div>
                                                    </div>
                                                </label>
                                                <script>
                                                    itemsArray.push("<?= $row->id; ?>");
                                                </script>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function () {
                                createList();
                                // $('#tech_block, #selected_tech').addClass('scrollbarHide').scrollbar();
                            });
                        </script>
                        <div class="clr"></div>
                    </div>

                </div>
            </div>

            <!-- Short Description -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-12 mb-0">
                            <h4>Short Description</h4>
                            <textarea class="form-control" name="content_short" id="content_short" rows="20"><?= post('content_short', false, $this->edit->content_short); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-12 mb-0">
                            <h4>Description</h4>
                            <textarea class="form-control" name="content" id="description" rows="20"><?= post('content', false, $this->edit->content); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidates -->
            <style>
                .cand_item {
                    line-height: 36px;
                    /*border-radius: 4px;*/
                    padding: 0 6px;
                    color: black;
                }
                .cand_item:nth-child(2n+1) {
                    background-color: #f6f6f6;
                }
                .cand_item:hover {
                    background-color: #cdcdcd;
                }
            </style>
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Candidates</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <label for="package">Add Candidate</label>
                            <input class="form-control" type="text" name="candidate_name" id="candidate_name" placeholder="Type to search">

                            <div id="result_box">
                                <div style="margin-top: 10px;">
                                    <?php if (is_array($this->candidates) && count($this->candidates) > 0) foreach ($this->candidates as $item){?>
                                        <div class="cand_item" style="display: flex; justify-content: space-between; align-items: center;">
                                            <div><?= $item->firstname . ' ' . $item->lastname ?></div>
                                            <a onclick="load('panel/vacancies/add_candidate', 'candidate_name#candidate_name', 'candidate_id=<?=$item->id?>', 'vacancy_id=<?=$this->edit->id?>'); return false;"
                                               class="icon fa fa-fw fa-plus" title="Add" style="cursor: pointer;"></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                         </div>
                        <div class="form-group col-md-6 mb-0">
                            <label>Added Candidates</label>
                            <div id="candidates_box">
                                <?php if (is_array($this->vacancy_candidates) && count($this->vacancy_candidates) > 0) foreach ($this->vacancy_candidates as $item){?>
                                    <div class="cand_item" style="display: flex; justify-content: space-between; align-items: center;">
                                        <div><a href="{URL:panel/candidates_portal/edit/<?= $item->cid ?>}" target="_blank"
                                                style="text-decoration: none; color: #0075ff;"><?= $item->firstname . ' ' . $item->lastname ?></a></div>
                                        <a onclick="load('panel/vacancies/delete_candidate', 'candidate_id=<?=$item->cid?>', 'vacancy_id=<?=$this->edit->id?>'); return false;"
                                           class="icon fa fa-fw fa-trash-alt" title="Delete" style="cursor: pointer;"></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultant/Customers -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <h4>Consultant</h4>
                            <select class="form-control" name="consultant_id" id="consultant_id" required>
                                <option value="0">- No Consultant -</option>
                                <?php if (isset($this->consultants) && is_array($this->consultants) && count($this->consultants) > 0) { ?>
                                    <?php foreach ($this->consultants as $member) { ?>
                                        <option value="<?= $member->id; ?>" <?= checkOptionValue(post('consultant_id'), $member->id, $this->edit->consultant_id); ?>><?= $member->firstname . ' ' . $member->lastname; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-0">
                            <h4>Customers</h4>
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->team) && is_array($this->team) && count($this->team) > 0) { ?>
                                    <?php foreach ($this->team as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" id="cons_<?=$item->id?>" name="customer_id[]" value="<?= $item->id; ?>"
                                                <?= checkCheckboxValue(post('customer_id'), $item->id, $this->edit->customer_ids); ?>
                                            ><label class="custom-control-label" for="cons_<?=$item->id?>"><?= $item->firstname . ' ' . $item->lastname ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Microsite -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-12 mb-0">
                            <h4>Microsite</h4>
                            <select name="microsite_id" class="form-control" id="microsite_id" required>
                                <option value="0">- Choose Microsite -</option>
                                <?php if (isset($this->microsites) && is_array($this->microsites) && count($this->microsites) > 0) { ?>
                                    <?php foreach ($this->microsites as $microsite) { ?>
                                        <option value="<?= $microsite->id; ?>" <?= checkOptionValue(post('microsite_id'), $microsite->id, $this->edit->microsite_id); ?>><?= $microsite->title; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>On-page SEO</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="meta_title">
                                Meta Title<a href="https://moz.com/learn/seo/title-tag" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_title" id="meta_title" value="<?= post('meta_title', false, $this->edit->meta_title); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="meta_keywords">
                                Meta Keywords<a href="https://moz.com/learn/seo/what-are-keywords" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" value="<?= post('meta_keywords', false, $this->edit->meta_keywords); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <label for="meta_desc">
                                Meta Description<a href="https://moz.com/learn/seo/meta-description" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_desc" id="meta_desc" value="<?= post('meta_desc', false, $this->edit->meta_desc); ?>">
                        </div>
                        <div class="form-group col-md-6 mb-0">
                            <label for="slug">
                                URL Slug<a href="https://moz.com/blog/15-seo-best-practices-for-structuring-urls" target="_blank"><i class="fas fa-info-circle"></i></a>
                                &nbsp;&nbsp;{URL:job}/<?= $this->edit->slug; ?>
                            </label>
                            <input class="form-control" type="text" name="slug" id="slug" value="<?= $this->edit->slug; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/vacancies}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/vacancies/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
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

<!-- Connect editor -->
<script>
    $('.scroll-list').addClass('scrollbarHide');
    $('.scroll-list').scrollbar({
        "ignoreOverlay": false,
        "ignoreMobile": false
    });

    $('#sector_filter').keyup(function () {
        var q = $(this).val().toLowerCase().trim();

        if (q.length > 0) {
            $('.sectors').each(function (i, sector) {
                if ($(sector).text().trim().toLowerCase().indexOf(q) === -1) {
                    $(sector).parent().addClass('hidden')
                } else {
                    $(sector).parent().removeClass('hidden')
                }
            });
        } else {
            $('.sectors').each(function (i, sector) {
                $(sector).parent().removeClass('hidden')
            });
        }
    });

    $('#location_filter').keyup(function () {
        var q = $(this).val().toLowerCase().trim();

        if (q.length > 0) {
            $('.locations').each(function (i, location) {
                if ($(location).text().trim().toLowerCase().indexOf(q) === -1) {
                    $(location).parent().addClass('hidden')
                } else {
                    $(location).parent().removeClass('hidden')
                }
            });
        } else {
            $('.locations').each(function (i, location) {
                $(location).parent().removeClass('hidden')
            });
        }
    });

    var editorField;
    var editorField2;

    function setTextareaValue() {
        $('#content_short').val(editorField.getData());
        $('#description').val(editorField2.getData());
    }

    $(function () {
        $("#candidate_name").on("change paste keyup", function() {
            load('panel/vacancies/search_candidates','candidate_name=' + $(this).val(), 'vacancy_id=<?=$this->edit->id?>'); return false;
        });

        $('#time').datepicker({dateFormat: 'dd/mm/yy'});
        $('#time_expire').datepicker({dateFormat: 'dd/mm/yy'});

        editorField = CKEDITOR.replace('content_short', {
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

        editorField2 = CKEDITOR.replace('description', {
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

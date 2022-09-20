<link rel="stylesheet" href="<?= _SITEDIR_; ?>public/css/backend/jquery.tagit.css" />
<script src="<?= _SITEDIR_; ?>public/js/backend/Sortable.min.js"></script>
<script src="<?= _SITEDIR_; ?>public/js/backend/tag-it.js"></script>
<form id="form_box" action="{URL:panel/forms/sections/edit/<?= $this->section->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-rss" href="{URL:panel/forms/sections}"></a>
                                    <h1 class="page_title"><?= $this->section->title ?></h1>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- General -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="<?= post('title', false, $this->section->title); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12 flex-end">
                            <a href="" onclick="load('panel/forms/sections/field_popup/<?= $this->section->id ?>')">Add Field</a>
                        </div>
                        <!-- Sort input for Fields -->
                        <input type="hidden" name="fields_row" id="fields_row" value="<?= post('fields_row', false, $this->section->fields_row); ?>">
                        <script>
                            var itemsArray = []; // itemsArray
                        </script>
                        <div class="form-group col-md-12" id="sections_block">
                           <?php
                           if ($this->fields) {
                               include _SYSDIR_ . 'modules/panel/modules/forms/modules/sections/views/fields.php';
                           }
                           ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/forms/sections}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="
                                load('panel/forms/sections/edit/<?= $this->section->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
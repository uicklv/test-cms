<form id="form_box" action="{URL:panel/forms/fields/add}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h1 class="page_title"><a href="{URL:panel/forms/fields}">Fields</a>&nbsp;» Add</h1>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="title">Field Title</label>
                            <input class="form-control" type="text" name="title" id="title" value="<?= post('title', false); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="type">Type</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value="input">Input</option>
                                <option value="textarea">Textarea</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio</option>
                                <option value="info">Info(Textarea)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div>
                            <button type="submit" name="submit" class="btn btn-success" onclick="load('panel/forms/fields/add', 'form:#form_box'); return false;">Save Changes</button>
                            <a class="btn btn-outline-warning" href="{URL:panel/forms/fields}"><i class="fas fa-ban"></i>Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
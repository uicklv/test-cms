<form id="form_box" action="{URL:panel/redirects_builder}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h1 class="page_title">Redirects builder</h1>
                    </div>
                </div>
            </div>

            <!-- General -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="name">Sheet Name <span>(optional)</span></label>
                                <input class="form-control" type="text" name="sheet_name" id="sheet_name">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <!-- File -->
                            <div class="flex-btw">
                                <label for="document">
                                    Document
                                </label>
                            </div>
                            <div class="flex-btw flex-vc" id="download">
                                <div class="choose-file">
                                    <input type="hidden" name="document" id="document"
                                           value="<?= post('file', false, $this->edit->document); ?>">
                                    <input type="file"
                                           onchange="initFile(this); load('panel/upload/', 'name=<?= randomHash(); ?>', 'preview=#document_name', 'field=#document');">
                                    <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                </div>
                                <div id="document_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="old_cell">Column name with Old URLs</label>
                                <input class="form-control" type="text" name="old_cell" id="old_cell">
                            </div>
                            <div class="form-group">
                                <label for="new_cell">Column name with New URLs</label>
                                <input class="form-control" type="text" name="new_cell" id="new_cell">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="name">Start Cell Number</label>
                                <input class="form-control" type="text" name="start_cell" id="start_cell">
                            </div>
                            <div class="form-group">
                                <label for="name">End Cell Number</label>
                                <input class="form-control" type="text" name="end_cell" id="end_cell">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div>
                            <a class="btn btn-success" onclick="load('panel/redirects_builder/parse', 'form:#form_box'); return false;">
                                Parse
                            </a>
                            <a class="btn btn-outline-warning" href="{URL:panel/redirects_builder}"><i class="fas fa-ban"></i>Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

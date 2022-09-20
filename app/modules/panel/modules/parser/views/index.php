

<form id="form_box" method="post" enctype="multipart/form-data">
    <input type="hidden" value="" name="abs_path" id="abs_path">
    <input type="hidden" value="" name="total_count" id="total_count">
    <input type="hidden" value="" name="file_name" id="file_name">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap p-1">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <h1 class="page_title">Excel/CSV Parser</h1>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- General -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">

                    <div class="form-row">
                        <div class="form-group col-md-6 mt-1">
                            <div class="form-group">

                                <div class="flex-btw flex-vc">
                                    <div class="choose-file">
                                        <input type="file" accept="xlsx, xls, csv"
                                               onchange="initFile(this); load('panel/parser/upload_parse/', 'name=<?= randomHash(); ?>', 'preview=#file_preview', 'field=#file_input', 'type#type');">
                                        <a class="file-fake"><i class="fas fa-folder-open"></i>Choose file</a>
                                    </div>
                                </div>
                                <label class="pt-2" id="filename" for="file">File <span id="file_preview"></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row" id="select"></div>
                    <div class="form-row" id="table"></div>
                    <div class="form-row" id="get_data_box">
                        <div>
                        <a class="btn btn-success" onclick="
                                load('panel/parser/get_data', 'form:#form_box'); return false;">
                            Get Array
                        </a>

                        </div>
                    </div>
                    <div class="form-row" id="result">
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
    $('#get_data_box').hide();
    $('#filename').hide();
</script>



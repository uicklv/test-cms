<form id="form_box" action="{URL:panel/event_card/add}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h1 class="page_title"><a href="{URL:panel/event_card}">Events</a>&nbsp;» Add</h1>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                            <h4>Name</h4>
                            <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false); ?>">
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div>
                            <button type="submit" name="submit" class="btn btn-success" onclick="load('panel/event_card/add', 'form:#form_box'); return false;">Save Changes</button>
                            <a class="btn btn-outline-warning" href="{URL:panel/event_card}">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

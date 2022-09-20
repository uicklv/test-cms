<style>
    .time-control {
        position: relative;
        z-index: 99999;
    }
</style>

<form id="form_box" action="{URL:panel/event_card/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-calendar-week" href="{URL:panel/event_card}"></a>
                                    <h1 class="page_title"><?= $this->edit->name ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block">
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/event_card}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false, $this->edit->name); ?>">
                            </div>
                            <div class="form-group">
                                <label for="subtitle">Subtitle</label>
                                <input class="form-control" type="text" name="subtitle" id="subtitle" value="<?= post('subtitle', false, $this->edit->subtitle); ?>">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <!-- Image -->
                            <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>

                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->edit->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>
                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/events/<?= post('image', false, $this->edit->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Category(ies)</label>
                            <input type="text" class="form-control" id="sector_filter" value="" autocomplete="off" placeholder="Start typing to filter categories below">
                            <div class="form-check scroll_max_200 border_1">
                                <?php if (isset($this->sectors) && is_array($this->sectors) && count($this->sectors) > 0) { ?>
                                    <?php foreach ($this->sectors as $item) { ?>
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input class="custom-control-input" type="checkbox" name="category[]" id="sector_<?=$item->id?>" value="<?= "{$item->id}"; ?>"
                                                <?= checkCheckboxValue(post('sector_ids'), "{$item->id}", stringToArray($this->edit->category)); ?>
                                            ><label class="custom-control-label sectors" for="sector_<?=$item->id?>"><?= $item->name; ?></label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="time">Date Of Event</label>
                            <input class="form-control time-control" type="text" name="time" id="time" value="<?= post('time', false, date("d/m/Y", $this->edit->time)); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="link">Link</label>
                            <input class="form-control" type="text" name="link" id="link" value="<?= post('link', false, $this->edit->link); ?>">
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <label for="posted">Published</label>
                            <select class="form-control" name="posted" id="posted" required>
                                <option value="yes" <?= checkOptionValue(post('posted'), 'yes', $this->edit->posted); ?>>Yes</option>
                                <option value="no" <?= checkOptionValue(post('posted'), 'no', $this->edit->posted); ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/event_card}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success"
                           onclick="load('panel/event_card/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes
                        </a>
                    </div>
                </div>
            </div>
            </div>

        </div>
    </div>
</form>
<script>
    var editorField;

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

    $(function () {
        $('#time').datepicker({dateFormat: 'dd/mm/yy'});
    });
</script>

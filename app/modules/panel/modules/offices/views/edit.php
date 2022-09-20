<form id="form_box" action="{URL:panel/offices/edit/<?= $this->edit->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-map-marker-alt" href="{URL:panel/offices}"></a>
                                    <h1 class="page_title"><?= $this->edit->name ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side hide_block1024">
                                <div class="items_small-block"></div>
                                <a class="btn btn-outline-warning" href="{URL:panel/offices}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false,  $this->edit->name); ?>">
                        </div>
                        <div class="form-group  col-md-6">
                            <label for="email">Email</label>
                            <input class="form-control" type="text" name="email" required id="email" value="<?= post('email', false,  $this->edit->email); ?>">
                        </div>
                    </div>

                    <?php /*
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <!-- Image -->
                            <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                            <div class="choose-file modern">
                                <input type="hidden" name="image_main" id="image_main" value="<?= post('image_main', false, $this->edit->image_main); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image_main', 'preview=#preview_image_main', 'width=700', 'height=550')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                <div id="preview_image_main" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/offices/<?= post('image_main', false, $this->edit->image_main); ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <!-- Image -->
                            <label for="image">Image on the map<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->edit->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image', 'width=600', 'height=400')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/offices/<?= post('image', false, $this->edit->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    */ ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="postcode">Zipcode</label>
                                <input class="form-control" type="text" name="postcode" required id="postcode" value="<?= post('postcode', false,  $this->edit->postcode); ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="tel">Phone Number</label>
                                <input class="form-control" type="text" name="tel" required id="tel" value="<?= post('tel', false,  $this->edit->tel); ?>">
                            </div>
                        </div>
                        <div class="form-group  col-md-6">
                            <label for="posted">Published</label>
                            <select class="form-control" name="posted" id="posted" required>
                                <option value="yes" <?= checkOptionValue(post('posted'), 'yes', $this->edit->posted); ?>>Yes</option>
                                <option value="no" <?= checkOptionValue(post('posted'), 'no', $this->edit->posted); ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <h4>Schedule</h4>
                            <div class="form-group">
                                <label for="day_1">Days</label>
                                <input class="form-control" type="text" name="day_1" required id="day_1" value="<?= post('day_1', false,  $this->edit->day_1); ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="day_2" required id="day_2" value="<?= post('day_2', false,  $this->edit->day_2); ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6" style="margin-top: 36px;">
                            <div class="form-group">
                                <label for="time_1">Time</label>
                                <input class="form-control" type="text" name="time_1" required id="time_1" value="<?= post('time_1', false,  $this->edit->time_1); ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="time_2" required id="time_2" value="<?= post('time_2', false,  $this->edit->time_2); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="address">
                                Address
                                <small>(Start typing to find exact location on map. Drag pin to adjust)</small>
                            </label>
                            <input class="form-control" type="text" name="address" required id="address" value="<?= post('address', false,  $this->edit->address); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">
                                Coordinates
                                <small>(This field will be auto populated with coordinates of pin from map)
                                </small>
                            </label>
                            <input class="form-control" type="text" name="coordinates" id="coordinates" value="<?= post('coordinates', false,  $this->edit->coordinates); ?>"/>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <div class="col full_column map-col">
                            <div id="map" style="height: 300px; width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apply -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Apply to</h4>
                    <div class="form-group mb-0">
                        <textarea class="form-control" name="apply" id="apply" rows="20"><?= post('apply', false, $this->edit->apply); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Content</h4>

                    <div class="form-group mb-0">
                        <textarea class="form-control" name="content" id="text_content" rows="20"><?= post('content', false, $this->edit->content); ?></textarea>
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
                                &nbsp;&nbsp;{URL:locations}/<?= $this->edit->slug; ?>
                            </label>
                            <input class="form-control" type="text" name="slug" id="slug" value="<?= $this->edit->slug; ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/offices}"><i class="fas fa-reply"></i>Back</a>
                        <button type="submit" name="submit" class="btn btn-success" onclick="
                                setTextareaValue();
                                load('panel/offices/edit/<?= $this->edit->id; ?>', 'form:#form_box'); return false;">
                            <i class="fas fa-save"></i>Save Changes</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $this->maps_api ?>&callback=init_geocoder&language=en"></script>
<link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/ckeditor/samples/js/sample.js"></script>

<!-- Connect editor -->
<script>
    var editorField;
    var editorField1;

    $("#name").keyup(function () {
        initSlug('#slug', '#name');
    });


    function setTextareaValue() {
        $('#apply').val(editorField.getData());
        $('#text_content').val(editorField1.getData());
    }

    $(function () {
        editorField = CKEDITOR.replace('apply', {
            htmlEncodeOutput: false,
            wordcount: {
                showWordCount: true,
                showCharCount: true,
                countSpacesAsChars: true,
                countHTML: true,
            },
            // removePlugins: 'zsuploader',

            filebrowserBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=files',
            filebrowserImageBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=images',
            filebrowserUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=files',
            filebrowserImageUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=images'
        });

        editorField1 = CKEDITOR.replace('text_content', {
            htmlEncodeOutput: false,
            wordcount: {
                showWordCount: true,
                showCharCount: true,
                countSpacesAsChars: true,
                countHTML: true,
            },
            // removePlugins: 'zsuploader',

            filebrowserBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=files',
            filebrowserImageBrowseUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/browse.php?opener=ckeditor&type=images',
            filebrowserUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=files',
            filebrowserImageUploadUrl: '<?= _SITEDIR_ ?>public/plugins/kcfinder/upload.php?opener=ckeditor&type=images'
        });
    });

    var map, geocoder, marker = null;
    var default_coordinates = {lat: 51.5095146286, lng: -0.1244828354};

    function init_geocoder() {
        geocoder = new google.maps.Geocoder();

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 9,
            center: default_coordinates
        });
    }

    function edit_marker(lat, lng, address) {
        if (marker !== null) {
            marker.setMap(null);
            marker = null;
        }

        marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: lng
            },
            map: map,
            title: address,
            draggable: true
        });

        $('#coordinates').val(marker.getPosition().lat() + "," + marker.getPosition().lng());

        marker.addListener('mouseup', function () {
            $('#coordinates').val(marker.getPosition().lat() + "," + marker.getPosition().lng());
        });


        map.panTo({
            lat: lat,
            lng: lng
        });
    }

    $(function () {
        $('#address').keyup(function () {
            var address = $(this).val().trim();
            if (address.length <= 2) return;

            geocoder.geocode({'address': address}, function (results, status) {
                if (status === 'OK') {
                    edit_marker(results[0].geometry.location.lat(), results[0].geometry.location.lng(), address);
                } else {
                    var title = '';
                    if (status === 'ZERO_RESULTS')
                        title = 'Address not found on map';
                    if (status === 'OVER_QUERY_LIMIT')
                        title = 'Geocoder Limit exceed, wait few seconds and try again';
                }
            });
        });
    })
</script>

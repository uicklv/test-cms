<form id="form_box" action="{URL:panel/microsites/offices/add/<?= $this->microsite_id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h1 class="page_title"><a href="{URL:panel/microsites/offices/index/<?= $this->microsite_id; ?>}">Office</a>&nbsp;» New</h1>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="address">
                                Address
                                <small>(Start typing to find exact location on map. Drag pin to adjust)</small>
                            </label>
                            <input class="form-control" type="text" name="address" required id="address" value="<?= post('address', false); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">
                                Coordinates
                                <small>(This field will be auto populated with coordinates of pin from map)
                                </small>
                            </label>
                            <input class="form-control" type="text" name="coordinates" id="coordinates" value="<?= post('coordinates', false); ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col full_column">
                            <div id="map" style="height: 300px; width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div>
                            <button type="submit" name="submit" class="btn btn-success" onclick="load('panel/microsites/offices/add/<?= $this->microsite_id?>', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>
                            <a class="btn btn-outline-warning" href="{URL:panel/microsites/offices/index/<?= $this->microsite_id; ?>}"><i class="fas fa-ban"></i>Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $this->maps_api ?>&callback=init_geocoder"></script>

<script>
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
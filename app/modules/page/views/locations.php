<style>
    .location-map {
        width: 48%;
        height: 800px;
    }
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
        top: 100%;
        left: 0;
        right: 0;
        background-color: white;
        width: 100%;
        min-height: 0;
        max-height: 300px;
        /*margin-top: -10px;*/
        border: 1px solid #009F98;
        box-sizing: border-box;
        overflow-y: auto;
        z-index: 99999;
    }

    .suggests_result:empty {
        display: none;
    }

    .suggests_result2 .pc-item {
        padding: 0 20px;
        line-height: 60px;
        font-size: 12px;
    }

    .suggests_result2 .pc-item:hover {
        background-color:  #009F98;
        color: white;
        cursor: pointer;
    }

    .hide {
        display: none;
    }

    /* Marker tweaks */
    .mapboxgl-popup-close-button {
        display: none;
    }

    .mapboxgl-popup-content {
        font: 400 15px/22px 'Source Sans Pro', 'Helvetica Neue', Sans-serif;
        padding: 0;
        width: 180px;
    }

    .mapboxgl-popup-content-wrapper {
        padding: 1%;
    }

    .mapboxgl-popup-content h3 {
        background: #009F98;
        color: #fff;
        margin: 0;
        display: block;
        padding: 10px;
        border-radius: 3px 3px 0 0;
        font-weight: 700;
        margin-top: -15px;
    }

    .mapboxgl-popup-content h4 {
        margin: 0;
        display: block;
        padding: 10px;
        font-weight: 400;
    }

    .mapboxgl-popup-content div {
        padding: 10px;
    }

    .mapboxgl-popup-content .map-icon {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mapboxgl-popup-content .map-icon a {
        max-width: 32px;
    }

    .mapboxgl-popup-content .map-icon a:first-child {
        max-width: 26px;
    }

    .mapboxgl-popup-content .map-icon a:nth-child(3){
        max-width: 25px;
    }

    .mapboxgl-popup-content .map-icon a:last-child {
        max-width: 20px;
    }

    .mapboxgl-container .leaflet-marker-icon {
        cursor: pointer;
    }

    .mapboxgl-popup-anchor-top > .mapboxgl-popup-content {
        margin-top: 15px;
    }

    .mapboxgl-popup-anchor-top > .mapboxgl-popup-tip {
        border-bottom-color: #91c949;
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
            load('page/postcode', 'postcode#postcode');
        }
    }
</script>
<div class="first-block-inner">
    <div class="fixed-l">
        <div class="fixed-2">
            <div class="title-page-block mar">
                <h3 class="title-page" data-aos="fade-right" data-aos-duration="1500">
                        our <br> <span>locations</span>
                </h3>
            </div>
            <div class="fb-cont-btn" data-aos="fade-up" data-aos-duration="1500">
                <form class="search-bar" id="search_form">
                    <div class="hs-postcode" style="position: relative;">
                        <div style="display: flex; justify-content: flex-start; align-items: center; flex-wrap: wrap;">
                            <span onclick="currentLocation()" style="cursor: pointer;"><i class="fas fa-search-location" style="font-size: 30px; margin-right: 10px"></i>use my current location</span>
                        </div>
                        <div class="search-bar-wrap">
                            <input class="sb-text-field-2" name="postcode" id="postcode" type="text" placeholder="Zip Code" value="<?= post('postcode')?>" onkeyup="suggestPostcode(this);">
                            <input class="sb-sub" type="submit" value="" onclick="load('page/search_locations', 'form:#search_form'); findLocation(); return false;">
                        </div>
                        <div class="suggests_result"></div>
                    </div>
                    <?php /*
                    <div style="position: relative">
                        <input class="sb-text-field" type="text" name="name" placeholder="Search">
                        <input class="sb-sub" type="submit" value="" onclick="load('page/search', 'form:#search_form'); return false;">
                    </div>
 */ ?>
                </form>
            </div>
            <div class="location-flex">
                <div class="location-wrap">
                    <ul class="location-grid" id="search_results_box"  data-aos="fade-up" data-aos-duration="1500">
                        <?php if (is_array($this->list) && count($this->list) > 0) { ?>
                            <?php foreach ($this->list as $item) { ?>
                                <?php
                                    list($lat, $lng) = explode(",", $item->coordinates);
                                ?>
                                <li onclick="getOffice(<?= $item->id ?>);" class="locations-li">
                                    <div>
                                        <h3><?= $item->name ?></h3>
                                        <div>
                                            <?= $item->address ?>,<br> <?= $item->postcode ?> <br>
                                            <?= $item->tel ?>
                                        </div>
                                        <div class="lg-time">
                                            <div><span><?= $item->day_1 ?></span><?= $item->time_1 ?></div>
                                            <div><span><?= $item->day_2 ?></span><?= $item->time_2 ?></div>
                                        </div>
                                        <?php /*
                                        <a class="lg-link" href="{URL:location/<?= $item->slug ?>}"></a>
                                        */ ?>
                                        <a class="lg-details" >View details</a>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>

                        <?php /*
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Alton Town Center</h3>
                                <div>
                                    5320 Donald Ross Rd, #100 <br>
                                    Palm Beach Gardens, FL 33418 <br>
                                    (561) 437-6620
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:30am - 8:30pm</div>
                                    <div><span>Sunday</span> 11:30am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Boca Raton</h3>
                                <div>
                                    5030 Champion Blvd G1D <br>
                                    Boca Raton, FL 33496 <br>
                                    (561) 609-1781
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 8:00pm</div>
                                    <div><span>Sunday</span> 11:30am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li class="disable" data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Brandon <span>(Coming Soon!)</span></h3>
                                <div>
                                    1544 W Brandon Blvd, #102 <br>
                                    Brandon, FL 33510 <br>
                                    (813) 412-5085
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> tbd</div>
                                    <div><span>Sunday</span> tbd</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li class="disable" data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Brickell <span>(Coming Soon!)</span></h3>
                                <div>
                                    TBD <br>
                                    TBD <br>
                                    TBD
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> tbd</div>
                                    <div><span>Sunday</span> tbd</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>CORAL SPRINGS</h3>
                                <div>
                                    9204 Wiles Rd <br>
                                    Coral Springs, FL 33067 <br>
                                    (954) 509-7650
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am -10:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 9:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Gainesville</h3>
                                <div>
                                    2905 SW 42nd At, #70 <br>
                                    Gainesville, FL 32608 <br>
                                    (352) 554-8910
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 8:30pm</div>
                                    <div><span>Sunday</span> 11:00am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>JUPITER</h3>
                                <div>
                                    1697 West Indiantown Rd Suite 1 <br>
                                    Jupiter, FL 33458 <br>
                                    (561) 658-9258
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 8:30pm</div>
                                    <div><span>Sunday</span> 11:00am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>KENDALL</h3>
                                <div>
                                    8746 Mills Dr <br>
                                    Miami, FL 33183 <br>
                                    (786) 785-5580
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 9:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>MIAMI LAKES</h3>
                                <div>
                                    15141 NW 67th Ave <br>
                                    Miami Lakes, FL 33014 <br>
                                    (786) 688-0710
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 9:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Orlando – Lake Nona</h3>
                                <div>
                                    1211 Narcoossee Rd #120 <br>
                                    Orlando, FL 32832 <br>
                                    (407) 743-0230
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 8:30pm</div>
                                    <div><span>Sunday</span> 11:00am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Oviedo</h3>
                                <div>
                                    1079 Alafaya Trail, #1203 <br>
                                    Oviedo, FL 32765 <br>
                                    (407) 706-7077
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 8:30pm</div>
                                    <div><span>Sunday</span> 11:00am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Palm Beach Gardens</h3>
                                <div>
                                    3333 Northlake Blvd, #8 <br>
                                    Palm Beach Gardens, FL 33403 <br>
                                    (561) 437-5690
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 8:30pm</div>
                                    <div><span>Sunday</span> 11:00am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Pembroke Pines</h3>
                                <div>
                                    151 N Hiatus Rd <br>
                                    Pembroke Pines, FL 33026 <br>
                                    (954) 606-9404
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Pinecrest</h3>
                                <div>
                                    7880 SW 104th At, #101 <br>
                                    Miami, FL 33156 <br>
                                    (786) 800-9835
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 9:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Royal Palm Beach</h3>
                                <div>
                                    250 South State Rd 7, #100 <br>
                                    Royal Palm Beach, FL 33414 <br>
                                    (561) 899-0111
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 9:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li class="disable" data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>St. Petersburg <span>(Coming Soon!)</span></h3>
                                <div>
                                    TBD <br>
                                    TBD <br>
                                    TBD
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> TBD</div>
                                    <div><span>Sunday</span> TBD</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>SOUTH BOCA</h3>
                                <div>
                                    7152-B Beracasa Way <br>
                                    Boca Raton, FL 33433 <br>
                                    (561) 990-2940
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 8:30pm</div>
                                    <div><span>Sunday</span> 11:00am - 8pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>WEST PALM BEACH</h3>
                                <div>
                                    1880 Okeechobee Blvd, Suite A <br>
                                    West Palm Beach, FL 33409 <br>
                                    (561) 440-1120
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:30pm</div>
                                    <div><span>Sunday</span> 11:00am - 9:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li class="disable" data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>WEST BOCA <span>(Coming Soon!)</span></h3>
                                <div>
                                    9560 Glades Rd, Suite 130 <br>
                                    Boca Raton, FL 33428 <br>
                                    (561) 437 6620
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> TBD</div>
                                    <div><span>Sunday</span> TBD</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>West Pembroke Pines</h3>
                                <div>
                                    14810 Pines Blvd <br>
                                    Pembroke Pines, FL 33027 <br>
                                    (954) 777-8420
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 8:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
                        <li data-aos="fade-up" data-aos-duration="1500">
                            <div>
                                <h3>Winter Park</h3>
                                <div>
                                    1971 Aloma Ave <br>
                                    Winter Park, FL 32792 <br>
                                    (407) 794-0360
                                </div>
                                <div class="lg-time">
                                    <div><span>Monday-Saturday</span> 11:00am - 9:00pm</div>
                                    <div><span>Sunday</span> 11:00am - 9:00pm</div>
                                </div>
                                <a class="lg-link" href="/"></a>
                            </div>
                        </li>
         */ ?>
                    </ul>
                </div>
                <div class="location-map" id="map">

                </div>
            </div>
        </div>
    </div>
</div>
<?php
$markers = [];
foreach ($this->list as $i => $office) {
    list($lat, $lng) = explode(",", $office->coordinates);
    $markers[] = array(
        "lat" => floatval($lat),
        "lng" => floatval($lng),
        "id"  => $office->id,
        "name" => $office->name,
        "tel" => $office->tel,
        "image" => $office->image,
        "slug" => $office->slug,
    );
}
?>
<!-- MapBox -->
<link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js"></script>
<!-- End MapBox -->
<!-- Turf.js plugin -->
<script src='https://npmcdn.com/@turf/turf/turf.min.js'></script>
<script>
    //add class active
    $('.locations-li').on('click', function () {
        $('.locations-li').removeClass('active');
        $(this).addClass('active');
    })

    var popup = false;


    function openPopup(entity, zoomCoordinates) {
        if (popup) {
            popup.remove();
        }

        popup = new mapboxgl.Popup()
            .setLngLat(zoomCoordinates)
            .setHTML('<h3>' + entity.properties.title + '</h3>' +
                //'<img src="<?//= _SITEDIR_ ?>//data/offices/' + points[0].properties.image + '" alt="" style="display: block;width: 100%;" >' +
                '<div class="map-icon">' +
                '<a href="tel:' + entity.properties.tel + '"><img src="<?= _SITEDIR_ ?>public/images/phone.svg"></a>' +
                '<a href="<?= url('/') ?>" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/order.svg"></a>' +
                '<a href="<?= url('locations/') ?>' +  entity.properties.slug + '" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/store.svg"></a>' +
                '<a href="https://maps.google.com/?q=' + entity.geometry.coordinates[1] + ',' + entity.geometry.coordinates[0] + '" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/panel/pin-red.svg"></a>' +
                '</div>')
            .addTo(map);
    }

    //find location by zipcode
    function findLocation() {
        let zipcode = $('#postcode').val();

        if (!zipcode)
            alert('Enter zip code')

        load('<?= SITE_URL ?>page/get_location', 'postcode=' + zipcode);
    }

    // get info about office by id
    function getOffice(id) {
        load('<?= SITE_URL ?>page/get_office_by_id', 'id=' + id);
    }

    //find office on map after insert zip code
    function findRestaurant(location) {

        let office = JSON.parse(location);
        let coordinates = office.features[0].geometry.coordinates;

        console.log(coordinates)
        //find distane to all offices
        var options = { units: 'miles' };
        points.forEach(function(store) {
            Object.defineProperty(store.properties, 'distance', {
                value: turf.distance(coordinates, store.geometry, options),
                writable: true,
                enumerable: true,
                configurable: true
            });
        });

        // sort by distance
        points.sort(function(a, b) {
            if (a.properties.distance > b.properties.distance) {
                return 1;
            }
            if (a.properties.distance < b.properties.distance) {
                return -1;
            }
            return 0; // a must be equal to b
        });

        let zoomCoordinates = points[0].geometry.coordinates;

        map.flyTo({
            center: zoomCoordinates,
            essential: true, // this animation is considered essential with respect to prefers-reduced-motion
            zoom: 8
        });

        openPopup(points[0], zoomCoordinates);

        map.on('click', 'point-red', function (e) {
            openPopup(points[0], zoomCoordinates);
        });

        map.loadImage(
            "<?= _SITEDIR_ . 'public/images/panel/pin-red.png'; ?>",
            function (error, image) {
                if (error) throw error;

// Add the image to the map style.

                if (map.hasImage('red')) {
                    map.removeImage('red');
                }
                map.addImage('red', image);

// Add a data source containing one point feature.

                if(map.getLayer('point-red')) {
                    map.removeLayer('point-red');
                }

                if (map.getSource('point')) {
                    map.removeSource('point');
                }

                map.addSource('point', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': [
                            {
                                'type': 'Feature',
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': zoomCoordinates
                                }
                            }
                        ],
                    }
                });


                map.addLayer({
                    'id': 'point-red',
                    'type': 'symbol',
                    'source': 'point', // reference the data source
                    'layout': {
                        'icon-image': 'red', // reference the image
                    }
                });
            }
        );

    }


    //zoom to office when onclick li
    function zoomOffice(office) {

        let restaurant = JSON.parse(office);
        let coordinates = restaurant.coordinates.split(',');
        let zoomCoordinates = [coordinates[1], coordinates[0]];

        map.flyTo({
            center: zoomCoordinates,
            essential: true, // this animation is considered essential with respect to prefers-reduced-motion
            zoom: 8
        });

        function openPopup() {
            if (popup) {
                console.log('popup')
                console.log(popup)
                popup.remove();
            }
            popup = new mapboxgl.Popup()
                .setLngLat(zoomCoordinates)
                .setHTML('<h3>' + restaurant.name + '</h3>' +
                    //'<img src="<?//= _SITEDIR_ ?>//data/offices/' + restaurant.image + '" alt="" style="display: block;width: 100%;" >' +
                    '<div class="map-icon">' +
                    '<a href="tel:' + restaurant.tel + '"><img src="<?= _SITEDIR_ ?>public/images/phone.svg"></a>' +
                    '<a href="<?= url('/') ?>" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/order.svg"></a>' +
                    '<a href="<?= url('locations/') ?>' +  restaurant.slug + '" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/store.svg"></a>' +
                    '<a href="https://maps.google.com/?q=' + coordinates[0] + ',' + coordinates[1] + '" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/panel/pin-red.svg"></a>' +
                    '</div>')
                .addTo(map);
        }

        openPopup();

        map.on('click', 'point-red', function (e) {
            openPopup();
        });

        map.loadImage(
            "<?= _SITEDIR_ . 'public/images/panel/pin-red.png'; ?>",
            function (error, image) {
                if (error) throw error;

// Add the image to the map style.

                if (map.hasImage('red')) {
                    map.removeImage('red');
                }
                map.addImage('red', image);

// Add a data source containing one point feature.

                if(map.getLayer('point-red')) {
                    map.removeLayer('point-red');
                }

                if (map.getSource('point')) {
                    map.removeSource('point');
                }

                map.addSource('point', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': [
                            {
                                'type': 'Feature',
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': zoomCoordinates
                                }
                            }
                        ],
                    }
                });

                map.addLayer({
                    'id': 'point-red',
                    'type': 'symbol',
                    'source': 'point', // reference the data source
                    'layout': {
                        'icon-image': 'red', // reference the image
                    }
                });
            }
        );
    }

    var markers = <?= json_encode($markers)?>;

    var points = [];
    markers.forEach(function (obj) {
        points.push({
            'type': 'Feature',
            'geometry': {
                'type': 'Point',
                'coordinates': [
                    obj.lng, obj.lat
                ]
            },
            'properties': {
                'title': obj.name,
                'tel': obj.tel,
                'image': obj.image,
                'id': obj.id,
                'slug': obj.slug,
            }
        });

    });

    mapboxgl.accessToken = 'pk.eyJ1IjoidWlja2x2IiwiYSI6ImNrcXRldWc1cDFxaWoyeXFoMDR1NXQxZWkifQ.tUC3RHLi-2QVIRWwnSXoDg';
    var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: [markers[0].lng, markers[0].lat], // starting position [lng, lat]
        zoom: 6 // starting zoom
    });

    map.on('load', function () {
// Add an image to use as a custom marker
        map.loadImage(
            "<?= _SITEDIR_ . 'public/images/panel/pin-green.png'; ?>",
            function (error, image) {
                if (error) throw error;
                map.addImage('custom-marker', image);

                map.addSource('points', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': points
                    }
                });

// Add a symbol layer
                map.addLayer({
                    'id': 'points',
                    'type': 'symbol',
                    'source': 'points',
                    'layout': {
                        'icon-image': 'custom-marker',
                    }
                });
            }
        );
    });

    // open popup when onclick green point
    map.on('click', 'points', function (e) {
       openPopup(e.features[0], e.lngLat);
    });
</script>
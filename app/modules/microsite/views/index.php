<link href="<?= _SITEDIR_ ?>public/css/microsites.css" type="text/css" rel="stylesheet" />
<link href="<?= _SITEDIR_ ?>public/plugins/lightbox2-master/dist/css/lightbox.css" rel="stylesheet">

<style type="text/css">
    <?php if ($this->microsite->header_image) { ?>
        .open-header-sec {
            background: url("<?= _SITEDIR_ . 'data/microsites/'. $this->microsite->header_image; ?>") center top no-repeat !important;
            background-size: cover !important;
        }
    <?php } ?>

    <?php if ($this->microsite->opportunities_image) { ?>
        .company-sec.sub-page::before {
            background: url(<?= _SITEDIR_ . 'data/microsites/'. $this->microsite->opportunities_image; ?>) bottom center no-repeat;
            background-size: cover;
        }
    <?php } ?>

    a {
        color: #64C2C8;
    }
    .carousel-control-next, .carousel-control-prev {
        opacity: .5 !important;
    }
    .carousel-control-next:hover, .carousel-control-prev:hover {
        opacity: .9 !important;
    }
</style>

<div class="main-wrapper">
    <div class="header-sec open-header-sec">
        <div class="about-bottom ptop150">
            <div class="about-bottom-content">
                <div class="main-logo wow fadeInUp"
                     style="background: url(<?= _SITEDIR_ . 'data/microsites/'. $this->microsite->logo_image; ?>) center center no-repeat; background-size: cover;">
                </div>

                <h2 class="main-title maxwidth90 wow fadeInUp fbsp" data-wow-delay="1s" data-wow-duration="1s">
                    <?= $this->microsite->title; ?>
                </h2>
            </div>
        </div>
        <?php if (stristr($this->microsite->header_image, "mp4")) { ?>
            <video class="m-visible" id="bgvid" playsinline autoplay muted loop>
                <source src="<?= _SITEDIR_ . 'data/microsites/' . $this->microsite->header_image; ?>" type="video/mp4">
            </video>
        <?php } ?>
    </div>

    <!-- KEY INFORMATION -->
    <div class="employer-sec-page">
        <div class="container">
            <div class="employer-cont">
                <ul>
                    <li>
                        <div class="img-sec" style="height:450px; background: url(
                        <?= $this->microsite->key_image
                            ? _SITEDIR_ . 'data/microsites/' . $this->microsite->key_image
                            : (isset($photosURLs) ? _SITEDIR_ . 'data/microsites/' . $photosURLs[array_rand($photosURLs)] : _SITEDIR_ . 'images/key-bg.png'); ?>) center center no-repeat; background-size: cover; border-top-left-radius: 10px;">
                        </div>
                    </li>
                    <li>
                        <div class="img-cont">
                            <h1 title="keyinfo fbsp" style="margin-bottom: 20px;">Key <span>Information</span><br></h1>
                            <ul>
                                <?php if ($this->microsite->website){ ?>
                                <li>
                                    <span>Website</span>
                                    <span>
                                        <a href="<?= $this->microsite->website; ?>" target="_blank">
                                            <?= str_replace(array("https://", "http://"), "", $this->microsite->website); ?>
                                        </a>
                                    </span>
                                </li>
                                <?php } ?>
                                <?php if ($this->microsite->company_size){ ?>
                                <li>
                                    <span>Company Size</span><span><?= number_format($this->microsite->company_size, 0); ?></span>
                                </li>
                                <?php } ?>
                                <?php if (is_array($this->microsite->locations) && count($this->microsite->locations) > 0){ ?>
                                <li>
                                    <span>Headquarters</span>
                                    <span>
                                        <a id="overview">
                                            <?= implode(
                                                ", ",
                                                array_map(function ($obj) {
                                                    return $obj->location_name;
                                                }, $this->microsite->locations)
                                            ); ?>
                                        </a>
                                    </span>
                                </li>
                                <?php } ?>
                                <?php if (is_array($this->microsite->sectors) && count($this->microsite->sectors) > 0){ ?>
                                <li>
                                    <span>Industry</span>
                                    <span>
                                        <?= implode(
                                            ", ",
                                            array_map(function ($obj) {
                                                return $obj->sector_name;
                                            }, $this->microsite->sectors)
                                        ); ?>
                                    </span>
                                </li>
                                <?php } ?>
                                <?php if (is_array($this->microsite->tag_sectors) && count($this->microsite->tag_sectors) > 0){ ?>
                                <li>
                                    <span>Sectors</span>
                                    <span>
                                        <?= implode(
                                            ", ",
                                            array_map(function ($obj) {
                                                return $obj->sector_name;
                                            }, $this->microsite->tag_sectors)
                                        ); ?>
                                    </span>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                </ul>
                <ul>
                    <li>
                        <div class="img-cont">
                            <p><?= reFilter($this->microsite->content); ?></p>
                        </div>
                    </li>
                    <li><a id="jobs"></a>
                        <div class="img-sec" style="height:450px; background:url(<?= $this->microsite->overview_image
                            ? _SITEDIR_ . 'data/microsites/' . $this->microsite->overview_image
                            : (isset($photosURLs) ? _SITEDIR_ . 'data/microsites/' . $photosURLs[array_rand($photosURLs)] : _SITEDIR_ . 'images/key-bg.png'); ?>) center center no-repeat; background-size: cover; border-bottom-right-radius: 10px;">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- JOB OPPORTUNITIES -->
    <?php if (is_array($this->vacancies) && count($this->vacancies) > 0) { ?>
        <div class="company-sec sub-page">
            <div class="container">
                <div class="latest-sect company-cont sub-cont">
                    <h3 class="title-big wow fadeInUp center"><span>Job</span> Opportunities</h3>
<!--                    <h2 class="sub-head wow fadeInUp">Job <span>Opportunities</span></h2>-->

                    <div class="inner-sect slide-sec">
                        <ul id="vacancies-slider" class="slides-sect">
                            <?php
                            foreach ($this->vacancies as $vacancy) {
//                                if (!in_array($vacancy->id, $this->microsite->vacancy_ids)) continue;
                                ?>
                                <div class="rs-item">
                                    <div class="rs-pic">
                                        <?php
                                        $techArray = explodeString('|', trim($vacancy->tech_stack, '|'));
                                        foreach ($techArray as $tech)
                                            echo '<img src="'._SITEDIR_.'data/tech_stack/' . $this->tech_list[ $tech ]->image . '" height="66" width="48" alt="' . $this->tech_list[ $tech ]->name . '" title="' . $this->tech_list[ $tech ]->name . '"/>';
                                        ?>
                                    </div>
                                    <div class="rs-cont">
                                        <h3 class="rs-title"><?= $vacancy->title; ?></h3>
                                        <p>
                                            <?= implode(", ", array_map(function ($location) {
                                                return $location->location_name;
                                            }, $vacancy->locations)); ?>
                                        </p>
                                        <p>
                                            <b><?= ucfirst($vacancy->contract_type); ?></b>
                                            <br><?= $vacancy->salary_value; ?>
                                        </p>
                                        <p><?= reFilter($vacancy->content_short); ?></p>
                                    </div>
                                    <a class="rs-more" href="{URL:job/<?= $vacancy->slug; ?>}">Find out more</a>
                                </div>
                            <?php } ?>
                        </ul>
                    </div>
                    <a id="more"></a>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- TESTIMONIALS -->
    <?php if (is_array($this->testimonials) && count($this->testimonials) > 0) { ?>
        <div class="testi-sec" id="testimonials">
            <div class="container">
                <div class="testi-cont">
                    <div id="testisec" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($this->testimonials as $i => $testimonial) { ?>
                                <div class="carousel-item <?= $i === 0 ? 'active' : ''; ?> wow fadeInUp">
                                    <ul>
                                        <?php if ($testimonial->image) { ?>
                                            <li>
                                                <div class="img-sec"
                                                     style="background-image: url('<?= _SITEDIR_ . 'data/microsites/testimonials/' . $testimonial->image; ?>'); background-size: cover;"
                                                    <?php if ($testimonial->video) { ?>
                                                        data-toggle="modal"
                                                        data-target="#testimonial-<?= $testimonial->microsite_id; ?>"
                                                    <?php } ?>></div>
                                                <?php if ($testimonial->video) { ?>
                                                    <img src="<?= _SITEDIR_ ?>public/images/panel/play-btn.png"
                                                         data-toggle="modal"
                                                         data-target="#testimonial-<?= $testimonial->microsite_id; ?>"
                                                         width="100"
                                                         class="play-button">
                                                <?php } ?>
                                                <div class="img-cont"></div>
                                                <div class="clearfix"></div>
                                            </li>
                                        <?php } ?>
                                        <li <?php if(!$testimonial->image) { ?>style="width:100%;text-align:center;"<?php } ?>>
                                            <h3 class="testi-cont-text">
                                                <?= reFilter($testimonial->content); ?>
                                                <br/><br/>
                                                <p><strong><?= $testimonial->name; ?>, <?= $this->microsite->title; ?></strong></p>
                                            </h3>
                                        </li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <span class="carousel-control-prev" href="#testisec" data-slide="prev">
                            <img src="<?= _SITEDIR_ ?>public/images/panel/slide-arrow.png">
                        </span>
                        <span class="carousel-control-next" href="#testisec" data-slide="next">
                            <img src="<?= _SITEDIR_ ?>public/images/panel/slide-arrow-2.png">
                        </span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach ($this->testimonials as $i => $testimonial) { ?>
            <?php if ($testimonial->video) { ?>
                <div class="modal fade"
                     id="testimonial-<?= $testimonial->microsite_id; ?>"
                     style="margin-top: 60px"
                     tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="background-color: transparent;">
                            <button type="button" class="close text-right"
                                    data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times; Close</span>
                            </button>
                            <div class='vimeo embed-container'>
                                <iframe class="vimeo"
                                        src="https://player.vimeo.com/video/<?= $testimonial->video; ?>?title=0&byline=0&portrait=0"
                                        frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="clearfix"></div>
    <?php } ?>

    <!-- PHOTOS -->
    <?php if (is_array($this->photos) && count($this->photos) > 0) { ?>
        <div class="gallery-title-sec">
            <div class="container">
                <h1 class="title-big wow fadeInUp center">Photo <span>Gallery</span></h1>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="gallery-sec">
            <ul id="photos-slider">
                <?php foreach ($this->photos as $photo) { ?>
                    <li data-fancybox href="<?= _SITEDIR_ . 'data/microsites/photos/' . $photo->image ?>"
                        style="background: url(<?= _SITEDIR_ . 'data/microsites/photos/' . $photo->image; ?>) center center no-repeat; background-size: cover; cursor: pointer;">
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="clearfix"></div>
    <?php } ?>

    <!-- VIDEOS -->
    <?php if (is_array($this->videos) && count($this->videos) > 0) { ?>
        <div class="gallery-title-sec">
            <div class="container">
                <h1 class="title-big wow fadeInUp center">Video <span>Gallery</span></h1>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="gallery-sec">
            <ul id="videos-slider">
                <?php foreach ($this->videos as $video) { ?>
                    <li data-fancybox href="<?= _SITEDIR_ . 'data/microsites/videos/' . $video->video ?>"
                        style="background: url(<?= _SITEDIR_ . 'data/microsites/videos/' . $video->image; ?>) center center no-repeat; background-size: cover; cursor: pointer;">
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="clearfix"></div>
    <?php } ?>

    <!-- OFFICES -->
    <?php if (is_array($this->offices) && count($this->offices) > 0) { ?>
        <div class="our-location" id="offices">
            <div class="container">
                <div class="location-cont">
                    <h3 class="title-big wow fadeInUp center">Office <span style="color: white;">Locations</span></h3>
<!--                    <h1 class="heading wow fadeInUp">Office <span>Locations</span></h1>-->
                    <div id="office-locations">
                        <?php
                        $markers = array();
                        foreach ($this->offices as $i => $office) {
                            list($lat, $lng) = explode(",", $office->coordinates);
                            $markers[] = array(
                                "lat" => floatval($lat),
                                "lng" => floatval($lng),
                            );
                            ?>
                            <span style="cursor: pointer;" onclick="mapZoom(this, '<?= $office->coordinates; ?>');"><?= $office->name; ?></span>
                            <?php
                            if ($i + 1 !== count($this->offices))
                                echo ' / ';
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="map-cont">
                        <div class="map" id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- CONTACTS -->
    <div class="ms-ctas">
        <h3 class="title-big wow fadeInUp center"><span>Contact</span> <?= SITE_NAME ?></h3>
        <h2>Call us on <span>+44 0113 468 6700</span></h2>
        <h2>Email us at <span>info@amsource.io</span></h2>
        <div class="center" style="margin-bottom: 30px;">
            <a href="{URL:contact-us}" target="_blank" class="btn-yellow" style="display: inline-block; width: 224px; line-height: 45px;">Or click here to contact us</a>
        </div>
    </div>
</div>

<script src="<?= _SITEDIR_ ?>public/plugins/lightbox2-master/dist/js/lightbox.js"></script>
<script src="<?= _SITEDIR_ ?>public/js/backend/slick.min.js"></script>
<script>
    $(document).ready(function () {
        // $(this).scrollTop(0);
        $(document).scroll(function () {
            if ($(window).width() > 767) {
                var y = $(this).scrollTop();
                if (y > 800) {
                    $('#company-subnav').fadeIn();
                } else {
                    $('#company-subnav').fadeOut();
                }
            }
        });

        // Videos
        <?php /*if (is_array($getVideos) && count($getVideos) > 0) { ?>
            <?php foreach ($getVideos as $i => $video) { ?>
            $('#vimeo-<?= $video['video_id']; ?>').on('hidden.bs.modal', function (e) {
                var iframe = $(this).find('iframe.vimeo');
                if (iframe.length > 0) {
                    var player = new Vimeo.Player(iframe[0]);
                    player.pause();
                }
            });
            <?php } ?>
        <?php } */?>

        $('#vacancies-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplay: true,
            rows: 1,
            arrows: true,
            prevArrow: '<a class="slick-arrow carousel-control-prev content-pages-arrow-prev"><img src="<?= _SITEDIR_ ?>public/images/panel/arrow-l.png"></a>',
            nextArrow: '<a class="slick-arrow carousel-control-next content-pages-arrow-next"><img src="<?= _SITEDIR_ ?>public/images/panel/arrow-r.png"></a>',
            responsive: [
                {
                    breakpoint: 1030,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
        $('#pages-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplay: true,
            rows: 1,
            arrows: true,
            prevArrow: '<a class="slick-arrow carousel-control-prev content-pages-arrow-prev"><img src="<?= _SITEDIR_ ?>public/images/panel/arrow-l.png"></a>',
            nextArrow: '<a class="slick-arrow carousel-control-next content-pages-arrow-next"><img src="<?= _SITEDIR_ ?>public/images/panel/arrow-r.png"></a>',
            responsive: [
                {
                    breakpoint: 1030,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

        $('#photos-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplay: true,
            rows: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1030,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

        $('#videos-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplay: true,
            rows: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1030,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    });
    var markers = <?= isset($markers) && is_array($markers) && count($markers) > 0 ? json_encode($markers) : "[]"; ?>,
        map;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            icon: "<?= _SITEDIR_ . 'public/images/panel/map-pin.png'; ?>",
            center: markers.length > 0 ? markers[0] : null,
            scrollwheel: true,
            navigationControl: false,
            mapTypeControl: false,
            scaleControl: false,
            draggable: true,
            styles: [
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e9e9e9"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 29
                        },
                        {
                            "weight": 0.2
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 18
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#dedede"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "saturation": 36
                        },
                        {
                            "color": "#333333"
                        },
                        {
                            "lightness": 40
                        }
                    ]
                },
                {
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        },
                        {
                            "lightness": 19
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 17
                        },
                        {
                            "weight": 1.2
                        }
                    ]
                }
            ]
        });

        $(markers).each(function (i, marker) {
            new google.maps.Marker({
                position: marker,
                map: map,
                icon: "<?= _SITEDIR_ . 'public/images/panel/map-pin.png'; ?>"
            });
        });
    }

    function mapZoom(el, coordinates) {
        $('.map-location-active').removeClass('map-location-active');
        $(el).addClass('map-location-active');
        coordinates = coordinates.split(',');
        if (coordinates.length === 2) {
            map.panTo({
                lat: parseFloat(coordinates[0].trim()),
                lng: parseFloat(coordinates[1].trim()),
            });
            map.setZoom(10);
        }
    }

    function imgZoom(img) {
        if (!$(img).attr('width').length)
            $(img).attr('width', 300);
        else
            $(img).attr('width', '')
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?callback=initMap&language=en&key=<?= $this->maps_api_key ?>"></script>
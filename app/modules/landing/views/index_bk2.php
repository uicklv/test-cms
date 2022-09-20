<link href="<?= _SITEDIR_ ?>public/css/landings.css" type="text/css" rel="stylesheet" />

<?php
$orderArr = explode('||', trim($this->landing->section_row, '|'));
foreach ($this->landing->sections as $k => $v) {
    if (!in_array($v->id, $orderArr))
        $orderArr[] = $v->id;
}

// Display sections
if (is_array($orderArr) && count($orderArr) > 0) {
    foreach ($orderArr as $vid) {
        $item = $this->landing->sections[$vid];
        if (!$item) continue;

        // Blocks
        switch ($item->type) {
            case 'home': ?>
                <div class="land__head-block" style="background: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>') no-repeat center center;">
                    <div class="land__fixed">
                        <div class="land__head-cont">
                            <div>
                                <h1 class="land__gen-title"><?= $item->content1 ?></h1>
                                <div class="land__gen-title-text"><?= $item->content2 ?></div>
                                <a class="land__btn" href="/">Button</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php break;

            case 'text': ?>
                <section class="land__grey-block">
                    <div class="land__fixed">
                        <div class="land__grey-block-text">
                            <div class="land__text-block content__formatting"><?= reFilter($item->content1) ?></div>
                        </div>
                    </div>
                </section>
                <?php break;

            case 'picture_text': ?>
                <!-- right text -->
                <?php if ($item->options == 'right_text') { ?>
                    <section class="land__block-pic-left">
                        <div class="land__fixed">
                            <div class="land__pl-flex">
                                <div class="land__pl-left"><img src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/></div>
                                <div class="land__pl-cont">
                                    <!--<h3 class="land__title">Finance & Accountancy</h3>-->
                                    <div class="land__text-block content__formatting"><?= reFilter($item->content1) ?></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- left text -->
                <?php } else if ($item->options == 'left_text') { ?>
                    <section class="land__block-pic-right">
                        <div class="land__fixed">
                            <div class="land__pl-flex">
                                <div class="land__pl-right"><img src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/></div>
                                <div class="land__pl-cont">
                                    <!--<h3 class="land__title">About Us</h3>-->
                                    <div class="land__text-block content__formatting"><?= reFilter($item->content1) ?></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- on image -->
                <?php } else if ($item->options == 'on_image') { ?>
                    <section class="land__block-pic-fon" style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>')">
                        <div class="land__fixed">
                            <div class="land__pic-fon-text">
                                <!--<h3 class="land__title">Text on image</h3>-->
                                <div class="land__text-block content__formatting"><?= reFilter($item->content1) ?></div>
                            </div>
                        </div>
                    </section>
                    <!-- below image -->
                <?php } else if ($item->options == 'below_image') { ?>
                    <section class="land__block">
                        <div class="land__fixed">
                            <!--<h3 class="land__title">Text below of image</h3>-->
                            <div class="land__pic-block"><img src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/></div>
                            <div class="land__text-block content__formatting"><?= reFilter($item->content1) ?></div>
                        </div>
                    </section>
                <?php } ?>
                <?php break;

            case 'video': ?>
                <section class="land__section-video">
                    <div class="land__fixed">
                        <div class="land__video-block land__big-video">
                            <a data-fancybox href="<?= $item->content1 ?>">
                                <img src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/>
                                <span class="land__play"><span></span></span>
                            </a>
                        </div>
                    </div>
                </section>
                <?php break;

            case 'video_text': ?>
                <!-- right text -->
                <?php if ($item->options == 'right_text') { ?>
                    <section class="land__block-video-left">
                        <div class="land__fixed">
                            <div class="land__pl-flex">
                                <div class="land__pl-left">
                                    <div class="land__video-block">
                                        <a data-fancybox href="<?= $item->content1 ?>">
                                            <img src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/>
                                            <span class="land__play">
                                        <span></span>
                                        </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="land__pl-cont">
                                    <!--<h3 class="land__title">Text and video</h3>-->
                                    <div class="land__text-block content__formatting"><?= reFilter($item->content2) ?></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- left text -->
                <?php } else if ($item->options == 'left_text') { ?>
                    <section class="land__block-video-right">
                        <div class="land__fixed">
                            <div class="land__pl-flex">
                                <div class="land__pl-right">
                                    <div class="land__video-block">
                                        <a data-fancybox href="<?= $item->content1 ?>">
                                            <img src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/>
                                            <span class="land__play">
                                    <span></span>
                                    </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="land__pl-cont">
                                    <!--<h3 class="land__title">Text and video</h3>-->
                                    <div class="land__text-block content__formatting"><?= reFilter($item->content2) ?></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- below video -->
                <?php } else if ($item->options == 'below_video') { ?>
                    <section class="land__section-video">
                        <div class="land__fixed">
                            <div class="land__video-block">
                                <a data-fancybox href="<?= $item->content1 ?>">
                                    <img src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/>
                                    <span class="land__play">
                            <span></span>
                            </span>
                                </a>
                            </div>
                            <div class="land__video-info">
                                <div>
                                    <!--<h3 class="land__title">Text and video</h3>-->
                                    <div class="land__text-block content__formatting"><?= reFilter($item->content2) ?></div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php } ?>
                <?php break;

            case '2_blocks': ?>
                <section class="land__block">
                    <div class="land__fixed">
                        <h3 class="land__title land__title-center">2 blocks</h3>
                        <ul class="land__block-list content__formatting">
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>')">
                                <a class="land__bl-text" href="/">
                                    <?= reFilter($item->content1) ?>
                                </a>
                            </li>
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content4 ?>')">
                                <a class="land__bl-text" href="/">
                                    <?= reFilter($item->content2) ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </section>
                <?php break;

            case '3_blocks': ?>
                <section class="land__block">
                    <div class="land__fixed">
                        <h3 class="land__title land__title-center">3 blocks</h3>
                        <ul class="land__grid-block content__formatting">
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content4 ?>')">
                                <a class="land__gb-item" href="/"><?= reFilter($item->content1) ?></a>
                            </li>
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content5 ?>')">
                                <a class="land__gb-item" href="/"><?= reFilter($item->content2) ?></a>
                            </li>
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content6 ?>')">
                                <a class="land__gb-item" href="/"><?= reFilter($item->content3) ?></a>
                            </li>
                        </ul>
                    </div>
                </section>
                <?php break;

            case '4_blocks': ?>
                <section class="land__grey-block">
                    <div class="land__fixed">
                        <h3 class="land__title land__title-center">4 blocks grid</h3>
                        <ul class="land__grid-cont content__formatting">
                            <li>
                                <?= reFilter($item->content1) ?>
                            </li>
                            <li>
                                <?= reFilter($item->content2) ?>
                            </li>
                            <li>
                                <?= reFilter($item->content3) ?>
                            </li>
                            <li>
                                <?= reFilter($item->content4) ?>
                            </li>
                        </ul>
                    </div>
                </section>
                <?php break;

            case 'contact_us': ?>
                <?php if ($item->content2) { ?>
                    <section class="land__block">
                        <div class="land__fixed">
                            <h3 class="land__title land__title-center"><?= $item->content1 ?></h3>
                            <form class="land__contact-form" id="form_<?= $item->id ?>">
                                <div class="land__cf-flex">
                                    <div class="land__cf-cell">
                                        <label class="land__cf-label">First Name</label>
                                        <input class="land__cf-text-field" name="firstname" type="text">
                                    </div>
                                    <div class="land__cf-cell">
                                        <label class="land__cf-label">Last Name</label>
                                        <input class="land__cf-text-field" name="lastname" type="text">
                                    </div>
                                    <div class="land__cf-cell">
                                        <label class="land__cf-label">Email Address</label>
                                        <input class="land__cf-text-field" name="email" type="text">
                                    </div>
                                    <div class="land__cf-cell">
                                        <label class="land__cf-label">Phone Number</label>
                                        <input class="land__cf-text-field" name="tel" type="text">
                                    </div>
                                    <div class="land__cf-cell land__cf-cell-full">
                                        <label class="land__cf-label">Your message</label>
                                        <textarea class="land__cf-textarea" name="message"></textarea>
                                    </div>
                                    <input type="hidden" name="contact_email" value="<?= $item->content2 ?>">
                                    <div class="land__cf-cell land__cf-cell-center">
                                        <input class="land__btn" type="submit" value="Button" onclick="load('contact-landing', 'form:#form_<?= $item->id ?>', 'section_id=<?= $item->id ?>'); return false;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                <?php } ?>
                <?php break;

            case 'how_it_work': ?>
                <section class="land__block">
                    <div class="land__fixed">
                        <h3 class="land__title var-2 land__title-center ">How it work</h3>
                        <!--<div class="land__text-block land__tb-center">An instant and digital audit trail means you’ll always know where your money is moving.</div>-->
                        <ul class="land__how-work-list content__formatting">
                            <li>
                                <div class="land__hw-pic">
                                    <div style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content4 ?>')"></div>
                                </div>
                                <?= reFilter($item->content1) ?>
                            </li>
                            <li>
                                <div class="land__hw-pic">
                                    <div style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content5 ?>')"></div>
                                </div>
                                <?= reFilter($item->content2) ?>
                            </li>
                            <li>
                                <div class="land__hw-pic">
                                    <div style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content6 ?>')"></div>
                                </div>
                                <?= reFilter($item->content3) ?>
                            </li>
                        </ul>
                    </div>
                </section>
                <?php break;

            case 'map': ?>
                <!--<section class="section-map">-->
                <div id="map" style="width: 100%; height: 500px;"></div>
                <?php
                list($lat, $lng) = explode(",", $item->content2);
                $markers[] = array(
                    "lat" => floatval($lat),
                    "lng" => floatval($lng),
                );
                ?>
                <script>
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
                <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=<?= isset($this->maps_api_key->value) && $this->maps_api_key->value ? $this->maps_api_key->value : ""; ?>"></script>
                <!--</section>-->
                <?php break;
        }

    }
}
?>

<section class="land__block">
    <div class="land__fixed">
        <h3 class="land__title land__title-center">Testimonials</h3>
        <div class="land__reviews-slider">
            <div>
                <div class="land__rs-item">
                    <div class="land__rs-pic">
                        <div style="background-image: url('<?= _SITEDIR_ ?>public/images/landings/rs-pic.png')"></div>
                    </div>
                    <div class="land__rs-cont">
                        <p>Early this year we worked with Next on their brand refresh. As an extension to the updated logotype, we also produced a bespoke typeface. Early this year we worked with Next on their brand refresh. </p>
                        <div class="land__rs-name">Amanda Thomas</div>
                    </div>
                </div>
            </div>
            <div>
                <div class="land__rs-item">
                    <div class="land__rs-pic">
                        <div style="background-image: url('<?= _SITEDIR_ ?>public/images/landings/rs-pic.png')"></div>
                    </div>
                    <div class="land__rs-cont">
                        <p>Early this year we worked with Next on their brand refresh. As an extension to the updated logotype, we also produced a bespoke typeface. Early this year we worked with Next on their brand refresh. </p>
                        <div class="land__rs-name">Amanda Thomas</div>
                    </div>
                </div>
            </div>
            <div>
                <div class="land__rs-item">
                    <div class="land__rs-pic">
                        <div style="background-image: url('<?= _SITEDIR_ ?>public/images/landings/rs-pic.png')"></div>
                    </div>
                    <div class="land__rs-cont">
                        <p>Early this year we worked with Next on their brand refresh. As an extension to the updated logotype, we also produced a bespoke typeface. Early this year we worked with Next on their brand refresh. </p>
                        <div class="land__rs-name">Amanda Thomas</div>
                    </div>
                </div>
            </div>
            <div>
                <div class="land__rs-item">
                    <div class="land__rs-pic">
                        <div style="background-image: url('<?= _SITEDIR_ ?>public/images/landings/rs-pic.png')"></div>
                    </div>
                    <div class="land__rs-cont">
                        <p>Early this year we worked with Next on their brand refresh. As an extension to the updated logotype, we also produced a bespoke typeface. Early this year we worked with Next on their brand refresh. </p>
                        <div class="land__rs-name">Amanda Thomas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--
<div class="land__btn-block-group">
    <button class="land__btn-block">Button</button>
    <a class="land__btn-block land__btn-yellow" href="/">Button</a>
    <a class="land__btn-block land__btn-orange" href="/">Button</a>
    <a class="land__btn-block land__btn-blue" href="/">Button</a>
    <a class="land__btn-block land__btn-red" href="/">Button</a>
    <a class="land__btn-block land__btn-grey land__btn-small" href="/">Button</a>
    <a class="land__btn-block land__btn-black land__btn-small" href="/">Button</a>
    <a class="land__btn-block land__btn-small" href="/">Button</a>
    <a class="land__btn-block land__btn-yellow land__btn-small" href="/">Button</a>
    <a class="land__btn-block land__btn-orange land__btn-small" href="/">Button</a>
    <a class="land__btn-block land__btn-blue land__btn-small" href="/">Button</a>
    <a class="land__btn-block land__btn-red land__btn-small" href="/">Button</a>
    <a class="land__btn-block land__btn-yellow land__btn-text-black" href="/">Button</a>
    <a class="land__btn-block land__btn-yellow land__btn-small land__btn-text-black" href="/">Button</a>
</div>
-->

<script>
    jQuery( document ).ready(function($){

        // Slick Reviews
        $('.land__reviews-slider').slick({
            speed: 350,
            arrows: true,
            dots: true,
        });

    });
</script>

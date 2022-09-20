<link href="<?= _SITEDIR_ ?>public/css/landings.css" type="text/css" rel="stylesheet" />
<style>
    h1 {
        color: #64C2C8;
        margin-bottom: 63px;
        font: 117px/91px "Black Space", sans-serif;
    }
    h1 span { color: #F2B825; }

    h2 {
        margin-bottom: 18px;
        font: 500 36px/130% "BigCityGrotesquePro",sans-serif;
        color: #64C2C8;
    }

    h3 {
        font: bold 22px/135% "BigCityGrotesquePro",sans-serif !important;
        /*color: #fff;*/
    }

    .section_8 h1,
    .section_8 h2,
    .section_8 h3 {
        color: #F2B825;
    }

    .contact-form textarea.cf-text-field {
        height: 120px;
        max-height: 120px;
        padding: 16px;
        resize: none;
    }

    .text_type {
        font: 21px/130% "BigCityGrotesquePro",sans-serif;
        color: #707070;
    }

    .map-cont {
        margin: 40px auto 40px;
        width: 100%;
        height: auto;
        background: #64C2C8;
        padding: 20px;
        border-radius: 10px;
        position: relative;
        z-index: 9;
    }

    .scaling-pic {
        margin-bottom: 100px;
    }
</style>

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
            //+
            case 'home': ?>
                <section class="head-block" style="background: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>') no-repeat center center;">
                    <div class="fixed">
                        <div class="head-cont">
                            <div class="gen-title-name">
                                <?= reFilter($item->content2) ?>
                            </div>

                            <h1 class="gen-title">
                                <?= reFilter($item->content1) ?>
                            </h1>
                        </div>
                    </div>

                    <a class="explore" href="#explore" onclick="scrollToEl('#explore|500');" style="color: white; text-shadow: 2px 2px 5px #000;">Explore <span class="icon-arrow-down-circle"></span></a>
                    <span class="pattern_9"><img src="<?= _SITEDIR_ ?>public/images/pattern_9.png" height="297" width="119" alt=""/></span>
                </section>
                <div id="explore"></div>
                <?php break;

            //+
            case 'text': ?>
                <section id="explore" class="section_6">
                    <div class="fixed">
                        <div class="section_1_text">
                            <?= reFilter($item->content1) ?>
                        </div>
                    </div>
                    <span class="pattern_10"><img src="<?= _SITEDIR_ ?>public/images/pattern_10.png" height="262" width="420" alt=""/></span>
                </section>
                <?php break;

            //++++
            case 'picture_text': ?>

                <?php switch ($item->options) {
                    case 'right_text': ?>
                        <section id="explore" class="section_6">
                            <div class="fixed">
                                <h3 class="title-big">
                                    <?= reFilter($item->name) ?>
                                </h3>
                                <div class="do-flex">
                                    <div class="highlights-block">
                                        <div class="hb-pic"><img data-aos="" src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" height="414" width="620" alt=""/></div>
                                    </div>

                                    <div class="df-text">
                                        <?= reFilter($item->content1) ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <?php break;

                    case 'left_text': ?>
                        <section id="explore" class="section_6">
                            <div class="fixed">
                                <h3 class="title-big">
                                    <?= reFilter($item->name) ?>
                                </h3>
                                <div class="do-flex">
                                    <div class="df-text">
                                        <?= reFilter($item->content1) ?>
                                    </div>

                                    <div class="highlights-block">
                                        <div class="hb-pic"><img data-aos="" src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" height="414" width="620" alt=""/></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <?php break;

                    case 'on_image': ?>
                        <section class="land__block-pic-fon" style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>')">
                            <div class="land__fixed">
                                <div class="land__pic-fon-text">
                                    <div class="land__text-block content__formatting" style="background-color: #00000059; padding: 0 24px 24px;">
                                        <?= reFilter($item->content1) ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <?php break;

                    case 'below_image': ?>
                        <section class="section_9">
                            <div class="fixed">
                                <div class="scaling-pic" style="height: auto;">
                                    <img class="animated" src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" alt=""/>
                                </div>

                                <div class="scaling-flex">
                                    <div class="scaling-text">
                                        <?= reFilter($item->content1) ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <?php break;
                } ?>

                <?php break;

            //+
            case 'video': ?>
                <section class="section_8 looking-inner">
                    <div class="fixed">
                        <div class="highlights-block">
                            <div class="hb-pic anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" height="677" width="1240" alt=""/></div>
                            <span class="hb-logo"><img src="<?php echo _SITEDIR_; ?>public/images/Amsource Logo.png" height="63" width="228" alt=""/></span>
                            <div class="hb-cont">
                                <div>
                                    <div class="hb-name"><?= $this->video->text; ?></div>
                                    <div class="hb-text">Watch our video</div>
                                </div>
                                <a class="hb-icon" data-fancybox="" href="<?= $item->content1 ?>"><span class="icon-play"></span></a>
                            </div>
                        </div>
                    </div>

                    <span class="pattern_15"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_15.png" height="349" width="233" alt=""/></span>
                </section>
                <?php break;

            //+++
            case 'video_text': ?>
                <?php if ($item->options == 'right_text') { ?>
                    <section id="explore" class="section_6">
                        <div class="fixed">
                            <div class="do-flex">
                                <div class="df-text">
                                    <?= reFilter($item->content2) ?>
                                </div>

                                <div class="highlights-block">
                                    <div class="hb-pic"><img data-aos="" src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" height="414" width="620" alt=""/></div>
                                    <div class="hb-cont">
                                        <div>
                                            <div class="hb-text">
                                                Watch our video
                                            </div>
                                        </div>
                                        <a class="hb-icon" data-fancybox="" href="<?= $item->content1 ?>"><span class="icon-play"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php } else if ($item->options == 'left_text') { ?>
                    <section id="explore" class="section_6">
                        <div class="fixed">
                            <div class="do-flex">
                                <div class="highlights-block">
                                    <div class="hb-pic"><img data-aos="" src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" height="414" width="620" alt=""/></div>
                                    <div class="hb-cont">
                                        <div>
                                            <div class="hb-text">
                                                Watch our video
                                            </div>
                                        </div>
                                        <a class="hb-icon" data-fancybox="" href="<?= $item->content1 ?>"><span class="icon-play"></span></a>
                                    </div>
                                </div>

                                <div class="df-text">
                                    <?= reFilter($item->content2) ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php } else if ($item->options == 'below_video') { ?>
                    <section class="section_9">
                        <div class="fixed">
                            <div class="highlights-block">
                                <div class="hb-pic"><img data-aos="" src="<?= _SITEDIR_ ?>data/landings/<?= $item->content3 ?>" height="414" width="620" alt=""/></div>
                                <div class="hb-cont">
                                    <div>
                                        <div class="hb-text">Watch our video</div>
                                    </div>
                                    <a class="hb-icon" data-fancybox="" href="<?= $item->content1 ?>"><span class="icon-play"></span></a>
                                </div>
                            </div>

                            <div class="scaling-flex">
                                <div class="scaling-text" style="margin-top: 30px;">
                                    <?= reFilter($item->content2) ?>
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
                                <div class="land__bl-text">
                                    <?= reFilter($item->content1) ?>
                                </div>
                            </li>
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content4 ?>')">
                                <div class="land__bl-text">
                                    <?= reFilter($item->content2) ?>
                                </div>
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
                                <div class="land__gb-item"><?= reFilter($item->content1) ?></div>
                            </li>
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content5 ?>')">
                                <div class="land__gb-item"><?= reFilter($item->content2) ?></div>
                            </li>
                            <li style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content6 ?>')">
                                <div class="land__gb-item"><?= reFilter($item->content3) ?></div>
                            </li>
                        </ul>
                    </div>
                </section>
                <?php break;

            //+
            case '4_blocks': ?>
                <section class="section_8 looking-inner">
                    <div class="fixed">
                        <ul class="step-list">
                            <li>
                                <div class="sl-flex">
                                    <div class="sl-number">1</div>
<!--                                    <h3 class="sl-title">Employer branding</h3>-->
                                </div>
                                <?= reFilter($item->content1) ?>
                            </li>

                            <li>
                                <div class="sl-flex">
                                    <div class="sl-number">2</div>
<!--                                    <h3 class="sl-title">Smarter processes</h3>-->
                                </div>
                                <?= reFilter($item->content2) ?>
                            </li>

                            <li>
                                <div class="sl-flex">
                                    <div class="sl-number">3</div>
<!--                                    <h3 class="sl-title">Intelligent automation</h3>-->
                                </div>
                                <?= reFilter($item->content3) ?>
                            </li>

                            <li>
                                <div class="sl-flex">
                                    <div class="sl-number">4</div>
<!--                                    <h3 class="sl-title">Market insight</h3>-->
                                </div>
                                <?= reFilter($item->content4) ?>
                            </li>
                        </ul>
                    </div>

                    <span class="pattern_11"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_11.png" height="80" width="317" alt=""/></span>
                    <span class="pattern_14"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_14.png" height="176" width="176" alt=""/></span>
                    <span class="pattern_15"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_15.png" height="349" width="233" alt=""/></span>
                </section>
                <?php break;

            //+
            case 'contact_us': ?>
                <?php if ($item->content2) { ?>
                    <section class="section_16" style="padding-bottom: 0;">
                        <div class="fixed">
                            <form id="form_<?= $item->id ?>" class="contact-form">
                                <h3 class="title-small"><?= $item->content1 ?></h3>
                                <div class="cf-row">
                                    <label class="cf-label">First Name</label>
                                    <input class="cf-text-field" type="text" name="firstname" placeholder="Type first name">
                                </div>
                                <div class="cf-row">
                                    <label class="cf-label">Last Name</label>
                                    <input class="cf-text-field" type="text" name="lastname" placeholder="Type last name">
                                </div>
                                <div class="cf-row">
                                    <label class="cf-label">Email Address</label>
                                    <input class="cf-text-field" type="text" name="email" placeholder="Type email">
                                </div>
                                <div class="cf-row">
                                    <label class="cf-label">Phone Number</label>
                                    <input class="cf-text-field" type="text" name="tel" placeholder="Type phone number">
                                </div>

                                <div class="cf-row">
                                    <label class="cf-label">Your message</label>
                                    <textarea class="cf-text-field" name="message"></textarea>
                                </div>

                                <input type="hidden" name="contact_email" value="<?= $item->content2 ?>">
                                <button class="btn-yellow" onclick="load('contact-landing', 'form:#form_<?= $item->id ?>', 'section_id=<?= $item->id ?>'); return false;">Submit</button>
                            </form>
                        </div>
                        <span class="pattern_24"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_24.png" height="302" width="182" alt=""/></span>
                    </section>
                <?php } ?>
                <?php break;

            //+
            case 'how_it_work': ?>
                <section class="land__block">
                    <div class="fixed center">
                        <h1 class="title-big">
                            <?= reFilter($item->name) ?>
                        </h1>
                        <ul class="land__how-work-list content__formatting">
                            <li>
                                <div class="land__hw-pic">
                                    <div style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content4 ?>')"></div>
                                </div>
                                <div class="text_type"><?= reFilter($item->content1) ?></div>
                            </li>
                            <li>
                                <div class="land__hw-pic">
                                    <div style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content5 ?>')"></div>
                                </div>
                                <div class="text_type"><?= reFilter($item->content2) ?></div>
                            </li>
                            <li>
                                <div class="land__hw-pic">
                                    <div style="background-image: url('<?= _SITEDIR_ ?>data/landings/<?= $item->content6 ?>')"></div>
                                </div>
                                <div class="text_type"><?= reFilter($item->content3) ?></div>
                            </li>
                        </ul>
                    </div>
                </section>
                <?php break;

            //+
            case 'map': ?>
                <section class="section_16" style="padding-bottom: 0; margin-top:40px;">
                    <div class="fixed">
                        <h3 class="title-big">
                            <?= reFilter($item->name) ?>
                        </h3>

                        <div class="map-cont">
                            <div id="map" style="width: 100%; height: 500px;"></div>
                        </div>
                    </div>

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
                    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=<?= $this->maps_api_key ?>"></script>
                </section>
                <?php break;
        }

    }
}
?>

<!--
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
-->

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

        // // Slick Reviews
        // $('.land__reviews-slider').slick({
        //     speed: 350,
        //     arrows: true,
        //     dots: true,
        // });

    });
</script>

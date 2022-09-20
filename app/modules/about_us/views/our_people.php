<section class="head-block" style="background-image: url('<?= _SITEDIR_ ?>public/images/header_bg5.jpg')">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">
                <contentElement name="about-us" type="input">About us</contentElement>
            </div>
            <h1 class="gen-title">
                <contentElement name="our-people" type="input"><span>OuR</span><br> people</contentElement>
            </h1>
        </div>
    </div>
    <a class="explore" href="#explore" onclick="scrollToEl('#explore|500');">Explore <span class="icon-arrow-down-circle"></span></a>
    <span class="pattern_9"><img src="<?= _SITEDIR_ ?>public/images/pattern_9.png" height="297" width="119" alt=""/></span>
</section>

<section id="explore" class="section_1">
    <div class="fixed">
        <div class="half-text">
            <contentElement name="our-people-desc">It’s our people that make <?= SITE_NAME ?> such a great place to work – each with diverse skills, unique talents, and a hearty appetite for success.</contentElement>
        </div>
        <div class="persons-grid">
            <?php
            $count = count($this->list);
            $minItems = [
                1 => 1,
                2 => 1,
                3 => 3,
                4 => 1,
                5 => 1,
                6 => 1,
                7 => 3,
            ];
            for ($i = 0, $iCycle = 0; $i < $count; $i++) {
                $iCycle++;
                if ( ($count - ($i)) < $minItems[$iCycle]) {
                    $iCycle++;
                    if ($iCycle > 7) $iCycle = 1;
                }

                echo profileNet($this->list, $i, $iCycle);

                if ( ($count - ($i)) >= $minItems[$iCycle])
                    $i += $minItems[$iCycle] - 1;

                if ($iCycle >= 7) $iCycle = 0;
            }

            function profileItem($obj, $i) {
                $sizeArray = array(
                    0 => array('h' => 500, 'w' => 611),
                    1 => array('h' => 500, 'w' => 1240)
                );
                return '<a class="persons-item" href="{URL:about-us/profile/' . $obj->slug . '}" data-aos="fade-up" data-aos-duration="1000">'
                    .'<span class="pi-pic" style="background-image: url(\'' . _SITEDIR_ . 'data/users/' . $obj->image . '\');"></span>'
//                    .'<span class="pi-pic"><img src="' . _SITEDIR_ . 'data/users/' . $obj->image . '" height="' . $sizeArray[$i]['h'] . '" width="' . $sizeArray[$i]['w'] . '" alt=""/></span>'
                    .'<span class="pi-name">' . $obj->firstname . ' ' . $obj->lastname . ' <span>' . $obj->title . '</span></span>'
                    .'<span class="pi-more">Find out more <span class="icon-arrow-right"></span></span>'
                .'</a>';
            }

            function profileNet($arrayList, $i, $iCycle) {
                switch ($iCycle) {
                    case 1:
                    case 2:
                    case 5:
                    case 6:
                        return profileItem($arrayList[$i], 0);
                    case 3:
                        return '<div class="pg-flex"><div class="pg-half-height">'
                                    .profileItem($arrayList[$i], 0)
                                    .profileItem($arrayList[$i+1], 0)
                                .'</div><div class="pg-full-height">'
                                    .profileItem($arrayList[$i+2], 0)
                                .'</div></div>';
                    case 4:
                        return '<div class="pg-full-width">' . profileItem($arrayList[$i], 1) . '</div>';
                    case 7:
                        return '<div class="pg-flex"><div class="pg-full-height">'
                                    .profileItem($arrayList[$i], 0)
                                .'</div><div class="pg-half-height">'
                                    .profileItem($arrayList[$i+1], 0)
                                    .profileItem($arrayList[$i+2], 0)
                                .'</div></div>';
                }
            }
            ?>
        </div>
    </div>
</section>

<section class="section_connect section_connect_2">
    <div class="fixed">
        <div class="connect-block">
            <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/connect_2.jpg" height="666" width="1240" alt=""/></div>
            <div class="connect-cont">
                <div class="title">
                    <contentElement name="great-place-to-work" type="input"><span>A great place</span> <br> to work</contentElement>
                </div>
                <div class="connect-text">
                    <contentElement name="great-place-desc" type="input">Join our talented team and become part of an<br> inspiring organisation that brings opportunities to<br> those working in the tech sector.</contentElement>
                </div>
                <a class="btn-yellow" href="{URL:contact-us}">Submit your interest</a>
                <div class="connect-tel">
                    <contentElement name="call-us" type="input">Alternatively call us on <b>+44 0113 468 6700</b></contentElement>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section_5 mar_2">
    <div class="fixed">
        <h3 class="title">Our Blog</h3>
        <ul class="blog-list">
            <script>load('blogs/our_blogs');</script>
        </ul>
    </div>
</section>
<section class="head-block" style="background-image: url('<?= _SITEDIR_ ?>public/images/header_bg3.jpg')">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">
                <contentElement name="about-us" type="input">About us</contentElement>
            </div>
            <h1 class="gen-title">
                <contentElement name="about-us-title" type="input"><span>IT’S AN</span><br> <?= SITE_NAME ?><br> THING</contentElement>
            </h1>
        </div>
    </div>
    <a class="explore" href="#explore" onclick="scrollToEl('#explore|500');">Explore <span class="icon-arrow-down-circle"></span></a>
    <span class="pattern_9"><img src="<?= _SITEDIR_ ?>public/images/pattern_9.png" height="297" width="119" alt=""/></span>
</section>

<section id="explore" class="section_1 mar">
    <div class="fixed">
        <div class="about-flex">
            <div class="af-left">
                <contentElement name="work-for-us-desc"><p>Forget recruitment.  We’re in the business of uniting talent with opportunity. We help dynamic start-ups grow into world-beating brands that disrupt and dominate. And we help ambitious individuals fulfil their potential.</p></contentElement>
                <contentElement name="work-for-us-desc-2"><p><?= SITE_NAME ?> has re-engineered the talent acquisition model.  Our tech-led approach has upgraded and enriched the engagement process – fuelling our clients’ success and shaping our candidates’ careers.</p></contentElement>
            </div>

            <?php if ($this->video) { ?>
                <div class="af-video anim-wrap">
                    <img class="animated " data-aos="" src="<?= _SITEDIR_ ?>data/videos/<?= $this->video->image; ?>" height="318" width="545" alt=""/>
                    <a class="af-video-icon" data-fancybox="" href="<?= $this->video->video; ?>"><span class="icon-play"></span></a>
                </div>
            <?php } ?>

            <div class="af-cont">
                <h3 class="title">
                    <contentElement name="training-development" type="input"><span>TRAINING AND</span> DEVELOPMENT</contentElement>
                </h3>
                <contentElement name="training-development-desc"><p>Our success depends entirely on our people. So we’re fully committed to helping every member of the <?= SITE_NAME ?> team accelerate their own personal and professional development.</p></contentElement>
                <contentElement name="training-development-desc-2"><p>We provide market-leading technology, training and support to empower our people do an incredible job. And with a clearly-defined progression path, your success is limited only by your own ambition.</p></contentElement>
            </div>
        </div>
    </div>
</section>

<section class="section-blue_dark">
    <div class="fixed">
        <ul class="stride-list">
            <li>
                <h3 class="stride-title">In-house development</h3>
                <div class="stride-number">One</div>
                <div class="stride-pic"><div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/stride-pic_1.jpeg" height="493" width="419" alt=""/></div></div>
                Best-in-class training to develop your expertise across the recruitment life-cycle.
            </li>
            <li>
                <h3 class="stride-title">External training</h3>
                <div class="stride-number">Two</div>
                <div class="stride-pic"><div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/stride-pic_2.jpeg" height="493" width="419" alt=""/></div></div>
                World-class sales training from recruitment guru Trevor Pinder.
            </li>
            <li>
                <h3 class="stride-title">Online platform</h3>
                <div class="stride-number">Three</div>
                <div class="stride-pic"><div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/stride-pic_3.jpeg" height="493" width="419" alt=""/></div></div>
                Accelerate your own development with our industry-leading training platform.
            </li>
            <li>
                <h3 class="stride-title">Career path</h3>
                <div class="stride-number">FOUr</div>
                <div class="stride-pic"><div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/stride-pic_4.jpeg" height="493" width="419" alt=""/></div></div>
                Clearly defined development road map puts you in control of your career.
            </li>
        </ul>
    </div>
</section>

<section class="section_9">
    <div class="fixed">
        <div class="uniquely-block">
            <h3 class="title">
                <contentElement name="uniquely-amsource" type="input"><span>UNIQUELY</span><br> <?= SITE_NAME ?></contentElement>
            </h3>
            <div class="un-slider">
                <?php
                foreach ($this->testimonials as $item) {
                    if ($item->user_image > 0)
                        $img = _SITEDIR_ . 'data/users/' . About_usModel::getUserByID($item->user_image)->image;
                    else
                        $img = _SITEDIR_ . 'data/testimonials/' . $item->image;
                    ?>
                    <div>
                        <div class="un-flex">
                            <div class="un-pic" style="background-image: url('<?= $img; ?>')"></div>
                            <div class="un-cont">
                                <div class="un-text"><?= reFilter($item->content); ?></div>
                                <div class="un-name"><?= $item->name; ?>, <?= $item->position; ?></div>
                            </div>
                        </div>
                    </div>
                    <?php
                    /*
                    <div class="un-pic anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>data/testimonials/<?= $item->image; ?>" height="283" width="283" alt=""/></div>
                    */
                }
                ?>
            </div>
        </div>

        <script>
            $(document).ready(function($) {
                $('.un-slider').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: true,
                });
            });
        </script>
        <style>
            .dif {
                height: 126px;
                width: 126px;
            }
        </style>


        <h3 class="title title-benefit">
            <contentElement name="great-benefits" type="input"><span>Great</span><br>Benefits</contentElement>
        </h3>
        <ul class="benefit-flex">
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_1.png" height="69" width="79" alt=""/></div>
                Uncapped commission strUcture
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_2.png" class="dif" height="69" width="67" alt=""/></div>
                Pension scheme
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_3.png" height="69" width="67" alt=""/></div>
                GenerOUs HOliday AllOwance
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_4.png" height="64" width="63" alt=""/></div>
                Take yOUr<br> birthday off
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_5.png" height="87" width="87" alt=""/></div>
                Gig tickets at<br> Leeds arena
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_6.png" height="64" width="75" alt=""/></div>
                Private<br> heAlthcAre
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_7.png" height="88" width="88" alt=""/></div>
                Summer & winter incentives
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_9.png" class="dif" height="72" width="71" alt=""/></div>
                Duvet dAys
            </li>
            <li>
                <div class="bf-pic"><img src="<?= _SITEDIR_ ?>public/images/bf-pic_8.png" class="dif" height="72" width="71" alt=""/></div>
                FAntAstic & uniqUe office environment
            </li>
        </ul>
    </div>
    <span class="pattern_22"><img src="<?= _SITEDIR_ ?>public/images/pattern_22.png" height="394" width="236" alt=""/></span>
</section>

<section class="section_1">
    <div class="fixed">
        <div class="stigma-flex">
            <h3 class="title">
                <contentElement name="great-place-to-work" type="input"><span>A GREaT PLaCE</span><br> To WoRK</contentElement>
            </h3>
            <!--            <h3 class="title"><span>Forget</span><br> the stigmA</h3>-->
            <div class="stigma-cont">
                <contentElement name="great-place-to-work-desc">
                    <p>We’re not like any of the recruiters you’ve heard about.</p>
                    <p>Imagine a job where you help businesses grow, and help people shape their future. That’s what we do, every day. Sound exiting?</p>
                    <p>You spend half your life at work.</p>
                </contentElement>
                <div class="stigma-title">
                    <contentElement name="great-place-subtext" type="input">We’ll make it somewhere yOU’ll want to be.</contentElement>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="follow-pic">
    <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/follow-pic_1.jpg" height="467" width="467" alt=""/></div>
    <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/follow-pic_2.jpg" height="467" width="465" alt=""/></div>
    <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/follow-pic_3.jpg" height="467" width="468" alt=""/></div>
    <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/follow-pic_4.jpg" height="467" width="466" alt=""/></div>
</div>
<a class="follow-link" href="https://www.instagram.com/amsource_"><span class="icon-Instagram"></span> Follow us</a>

<section class="section_5 mar">
    <div class="fixed">
        <h3 class="title">Our Blog</h3>
        <ul class="blog-list">
            <script>load('blogs/our_blogs');</script>
        </ul>
    </div>
</section>

<?php if ($this->jobs) { ?>
<section class="section_4">
    <div class="fixed">
        <div class="roles-flex">
            <h3 class="title">
                <contentElement name="latest-roles" type="input"><span>LAtest</span><br> roles</contentElement>
            </h3>
        </div>

        <div class="roles-slider">
            <?php foreach ($this->jobs as $item) { ?>
                <div>
                    <div class="rs-item">
                        <div class="rs-pic">
                            <?php
                            $techArray = explodeString('|', trim($item->tech_stack, '|'));
                            foreach ($techArray as $tech)
                                echo '<img src="'._SITEDIR_.'data/tech_stack/' . $this->tech_list[ $tech ]->image . '" height="66" width="48" alt="' . $this->tech_list[ $tech ]->name . '" title="' . $this->tech_list[ $tech ]->name . '"/>';
                            ?>
                        </div>
                        <div class="rs-cont">
                            <h3 class="rs-title"><?= $item->title; ?></h3>
                            <p>
                                <?= implode(", ", array_map(function ($location) {
                                    return $location->location_name;
                                }, $item->locations)); ?>
                            </p>
                            <p>
                                <b><?= ucfirst($item->contract_type); ?></b>
                                <br><?= $item->salary_value; ?>
                            </p>
                            <p><?= reFilter($item->content_short); ?></p>
                        </div>
                        <a class="rs-more" href="{URL:job/<?= $item->slug; ?>}">Find out more</a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <a class="btn-yellow btn-center" href="{URL:jobs}" onclick="load('jobs')">
            <contentElement name="view-all-vacancies" type="input">View all vacancies</contentElement>
        </a>
    </div>
    <span class="pattern_23"><img src="<?= _SITEDIR_ ?>public/images/pattern_23.png" height="106" width="106" alt=""/></span>
</section>
<script>
    latestRolesSlider('.roles-slider');
</script>
<?php } ?>


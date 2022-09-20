<section class="head-slider-block">
    <div class="head-slider">
        <div>
            <div class="hc-slider-pic">
                <div class="hc-slider-img">
                    <img src="<?= getImageElement('first-image', _SITEDIR_ . 'public/images/header_bg6.jpg'); ?>" height="1082" width="1920" alt=""/>
                </div>
                <div class="fixed">
                    <div class="head-cont">
                        <h2 class="gen-title">
                            <contentElement name="first-slide" type="input"><span>GAME-<br>CHANGING</span><br> TALENT<br> SEARCH</contentElement>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="hc-slider-pic">
                <div class="hc-slider-img">
                    <img src="<?= getImageElement('second-image', _SITEDIR_ . 'public/images/header_bg7.jpg'); ?>" height="1082" width="1920" alt=""/>
                </div>
                <div class="fixed">
                    <div class="head-cont">
                        <h2 class="gen-title">
                            <contentElement name="second-slide" type="input"><span>BUILDING<br> TEAMS </span><br> SCALING <br> BUSINESS</contentElement>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="hc-slider-pic">
                <div class="hc-slider-img">
                    <img src="<?= getImageElement('third-image', _SITEDIR_ . 'public/images/header_bg8.jpg'); ?>" height="1082" width="1920" alt=""/>
                </div>
                <div class="fixed">
                    <div class="head-cont">
                        <h2 class="gen-title">
                            <contentElement name="third-slide" type="input"><span>SustAin<br>  YOuR </span><br>  Diversity</contentElement>
                        </h2>
                        <div class="gen-title-text">
                            <contentElement name="third-slide-text" type="input">Inject new ideas into<br> your organisation</contentElement>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="explore" href="#explore" onclick="scrollToEl('#explore|500');">Explore <span class="icon-arrow-down-circle"></span></a>
</section>
<script>
    $(document).ready(function($){
        $('.head-slider').slick({
            dots: true,
            fade: true,
            arrows: false,
            autoplay: true
        });

        $(".hc-slider-pic").each(function(){
            var src = $(this).find('.hc-slider-img img').attr('src');
            $(this).css('backgroundImage', "url(" + src + ")");
        });
    });
</script>

<section id="explore" class="section_6">
    <div class="fixed">
        <h3 class="title-big">
            <contentElement name="what-we-do" type="input"><span>What</span> we do</contentElement>
            <?php /* Example getFileContent
            <a href="<?= getFileElement('test-file', _SITEDIR_ . 'public/files/test.docx') ?>" download>Download File</a>
            */ ?>
        </h3>
        <div class="do-flex">
            <div class="df-text">
                <div>
                    <h3 class="df-title">
                        <?php /*
                            <imageElement data-aos="" name="seeking-image" src="<?= _SITEDIR_ ?>public/images/seeking-1.jpg" height="794" width="609" alt="Test img"/>
                            <img src="<?= getImageElement('image-name', _SITEDIR_ . 'public/images/bl-pic.jpg'); ?>" alt="">
                        */ ?>
                        <contentElement name="client-title" type="input">For clients</contentElement>
                    </h3>
                    <contentElement name="client-desc" type="textarea"><p>We partner with ambitious young tech firms, identifying and acquiring the talent to help them accelerate growth and dominate their space. It’s game-changing tech recruitment.</p></contentElement>
                </div>
                <div>
                    <h3 class="df-title">
                        <?php /*
                        <iframe width="760" height="400" src="<?= getVideoElement('youtube-test', 'https://www.youtube.com/embed/lM02vNMRRB0') ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        
                        example videoElement
                        <video width="750" height="500" controls="controls" poster="">
                            <source src="<?= getVideoElement('test-video', _SITEDIR_ . 'public/videos/test.mp4'); ?>">
                        </video>
                        <video width="750" height="500" controls="controls" poster="">
                            <source src="<?= getVideoElement('test-video-2', _SITEDIR_ . 'public/videos/test.mp4'); ?>">
                        </video>
                        /* example imageElement
                        <img src="<?= getImageElement('image-name2', _SITEDIR_ . 'public/images/bz-pic.jpg'); ?>" alt="">
                        */?>
                        <contentElement name="candidate-title" type="input">For candidates</contentElement>
                    </h3>
                    <contentElement name="candidate-desc"><p>A career is like a journey. It’s more rewarding when you travel with a guide who knows the territory, and who can hook you up with the most exciting opportunities in town.</p></contentElement>
                </div>
            </div>

            <?php if ($this->video) { ?>
                <div class="highlights-block">
                    <div class="hb-pic"><img data-aos="" src="<?= _SITEDIR_ ?>data/videos/<?= $this->video->image; ?>" height="414" width="620" alt=""/></div>
                    <span class="hb-logo">
                        <img src="<?= _SITEDIR_ ?>public/images/Amsource Logo.png" height="63" width="228" alt=""/>
                    </span>
                    <div class="hb-cont">
                        <div>
                            <div class="hb-name"><?= $this->video->text; ?></div>
                            <div class="hb-text">
                                <contentElement name="video-text" type="input">Watch our video</contentElement>
                            </div>
                        </div>
                        <a class="hb-icon" data-fancybox="" href="<?= $this->video->video; ?>"><span class="icon-play"></span></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="section-seeking">
    <div class="fixed">
        <div class="seeking-block">
            <div class="seeking-left">
                <imageElement name="seeking-image" class="test-com" data-aos="" src="<?= _SITEDIR_ ?>public/images/seeking-1.jpg" height="794" width="609" alt=""/>

                <a class="seeking-link" href="{URL:seeking-talent}">
                    <contentElement name="seeking-talent" type="input">Seeking<br> talent?</contentElement>
                </a>
            </div>
            <div class="seeking-right">
                <img data-aos="" src="<?= _SITEDIR_ ?>public/images/seeking-2.jpg" height="794" width="609" alt=""/>
                <a class="seeking-link" href="{URL:jobs}">
                    <contentElement name="seeking-opportunity" type="input">Seeking<br> opportunity?</contentElement>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section_4">
    <div class="fixed">
        <div class="roles-flex">
            <h3 class="title">
                <contentElement name="latest-roles" type="input"><span>LAtest</span><br> roles</contentElement>
            </h3>
            <div class="qs-flex">
                Quick Search
                <div class="qs-block">
                    <input class="qs-text-field" type="text" id="qs_field" onkeypress="">
                    <span class="icon-search pointer" onclick="startSearch('#qs_field');"></span>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Enter listener
                $('#qs_field').keydown(function(e){
                    if (e.keyCode === 13) {
                        startSearch('#qs_field');
                        e.preventDefault();
                    }
                });
            });
        </script>

        <div class="roles-slider">
            <script>$(document).ready(function() { load('jobs/latest_roles'); });</script>
        </div>
        <a class="btn-yellow btn-center" href="{URL:jobs}" onclick="load('jobs')">
            <contentElement name="view-all-vacancies" type="input">View all vacancies</contentElement>
        </a>
    </div>
    <span class="pattern_23"><img src="<?= _SITEDIR_ ?>public/images/pattern_23.png" height="106" width="106" alt=""/></span>
</section>

<section class="section-edison">
    <div class="fixed">
        <div class="edison-block">
            <div>
                <div class="ed-logo"><img src="<?= _SITEDIR_ ?>public/images/ed-logo.png" height="117" width="432" alt=""/></div>
                <div class="ed-flex">
                    <div class="ed-left">
                        <h3 class="ed-title">
                            <contentElement name="edison-text" type="input">Building prolific teams that disrupt & deliver.</contentElement>
                        </h3>
                        <p>Powered by <span class="logo-white"><img src="<?= _SITEDIR_ ?>public/images/logo-white.png" height="48" width="171" alt=""/></span></p>
                        <div class="ed-text">
                            <contentElement name="edison-text-2" type="input">Identify, attract and retain hard-to-find talent in a fiercely competitive marketplace.</contentElement>
                        </div>
                    </div>
                    <div class="ed-right">
                        <div class="ed-title">100%</div>
                        <p>Success rate. Guaranteed.</p>
                        <p><b>Powerful solution for:</b></p>
                        <ul>
                            <li>Start ups</li>
                            <li>Scale ups</li>
                            <li>Project teams</li>
                        </ul>
                    </div>
                </div>
                <a class="btn-blue" href="{URL:edison}">Find out more</a>
            </div>
        </div>
    </div>
</section>

<section class="section_10 mar">
    <div class="fixed">
        <div class="sectors-flex">
            <h3 class="title">
                <contentElement name="specialist-sectors" type="input"><span>SPECIALIST</span></br> SECTORS</contentElement>
            </h3>
            <div>
                <p>
                    <contentElement name="specialist-text" type="input">Our ability to unite talent with opportunity is founded upon deep industry knowledge.</contentElement>
                </p>
                <a class="btn-yellow" href="{URL:specialisms}">Find out more</a>
            </div>
        </div>
        <ul class="sectors-block">
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_1.png" height="60" width="62" alt=""/></div>
                <h3 class="sectors-title">FinTech</h3>
                <p>As the FinTech revolution gathers pace, firms’ ability to acquire the right skills has become a critical success factor.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_2.png" height="71" width="71" alt=""/></div>
                <h3 class="sectors-title">AI</h3>
                <p>Artificial Intelligence is driving innovation in all industries, creating ferocious competition for specialist talent.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_3.png" height="46" width="62" alt=""/></div>
                <h3 class="sectors-title">Analytics</h3>
                <p>If data is the new oil, analytical talent is essential to manage the exploration, extraction and refining processes.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_4.png" height="52" width="62" alt=""/></div>
                <h3 class="sectors-title">HealthTech</h3>
                <p>The UK is a major international hub for the HealthTech industry, and its growth trajectory gets steeper every day.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_5.png" height="62" width="62" alt=""/></div>
                <h3 class="sectors-title">Blockchain</h3>
                <p>Every disruptive technology has a tipping point, and as adoption becomes more widespread, Blockchain is going mainstream.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_6.png" height="62" width="63" alt=""/></div>
                <h3 class="sectors-title">SaaS</h3>
                <p>In today’s web-driven world, Software as a Service underpins global technology strategy in all industrial and commercial sectors.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_7.png" height="56" width="67" alt=""/></div>
                <h3 class="sectors-title">Betting/Gaming</h3>
                <p>It’s the relentless striving for competitive advantage that puts talent at the heart of every successful gaming brand.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_8.png" height="62" width="62" alt=""/></div>
                <h3 class="sectors-title">Consultancy</h3>
                <p>Acquiring top consultancy talent is about much more than just identifying technical capability – it’s a people thing.</p>
            </li>
            <li>
                <div class="sectors-pic"><img src="<?= _SITEDIR_ ?>public/images/sectors-pic_9.png" height="56" width="63" alt=""/></div>
                <h3 class="sectors-title">Software House</h3>
                <p>To build world-class software products, you first need to build a world-class team of engineers. We know where to find them.</p>
            </li>
        </ul>
    </div>

    <span class="pattern_26"><img src="<?= _SITEDIR_ ?>public/images/pattern_26.png" height="221" width="442" alt=""/></span>
    <span class="pattern_27"><img src="<?= _SITEDIR_ ?>public/images/pattern_27.png" height="221" width="442" alt=""/></span>
</section>

<section class="section_pic">
    <div class="fixed">
        <div class="sp-pic "><img data-aos="" src="<?= _SITEDIR_ ?>public/images/sp-pic.jpg" height="384" width="1240" alt=""/></div>
    </div>
</section>

<section class="section-community">
    <div class="fixed">
        <div class="sc-flex">
            <div class="sc-left">
                <h3 class="title">
                    <contentElement name="tech-community" type="input"><span>Tech</span><br> community</contentElement>
                </h3>
                <p>
                    <contentElement name="tech-community-text" type="input">We don’t just serve the tech community, we’re an integral part of it. Collaborating, shaping, influencing, facilitating.</contentElement>
                </p>
                <a class="btn-yellow" href="{URL:tech-community}">Find out more</a>
            </div>

            <?php if ($this->video_home_tech) { ?>
                <div class="highlights-block">
                    <div class="hb-pic "><img data-aos="" src="<?= _SITEDIR_ ?>data/videos/<?= $this->video_home_tech->image; ?>" height="414" width="620" alt=""/></div>
                    <span class="hb-logo"><img src="<?= _SITEDIR_ ?>public/images/Amsource Logo.png" height="63" width="228" alt=""/></span>
                    <div class="hb-cont">
                        <div>
                            <div class="hb-name"><?= $this->video_home_tech->text; ?></div>
                            <div class="hb-text">Watch our video</div>
                        </div>
                        <a class="hb-icon" data-fancybox="" href="<?= $this->video_home_tech->video; ?>"><span class="icon-play"></span></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="section_partner">
    <div class="fixed">
        <ul class="partner-list">
            <li><a href="https://www.devopsberlin.de" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/log_1.png" height="92" width="242" alt=""/></a></li>
            <li><a href="https://northinvest.co.uk/our-partners-amsource-technology" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/log_2.png" height="165" width="323" alt=""/></a></li>
            <li><a href="https://leedsdigitalfestival.org" target="_blank"><img src="<?= _SITEDIR_ ?>public/images/log_3.png" height="198" width="198" alt=""/></a></li>
        </ul>
    </div>
</section>

<section class="section_connect">
    <div class="fixed">
        <div class="connect-block">
            <div class=""><img data-aos="" src="<?= _SITEDIR_ ?>public/images/connect.jpg" height="667" width="1241" alt=""/></div>
            <div class="connect-cont">
                <div class="title"><span>Connect with</span><br> <?= SITE_NAME ?></div>
                <div class="connect-text">Whether you’re building a new team or building<br>your career, talk to one of our consultants.<br>And elevate your ambition.</div>
                <a class="btn-yellow btn-open-popup" onclick="load('page/arrange_call');">Arrange call back</a>
                <div class="connect-tel">Alternatively call us on <b>+44 0113 468 6700</b></div>
            </div>
        </div>
    </div>
</section>

<section class="section_5 mar">
    <div class="fixed">
        <h3 class="title">Our Blog</h3>
        <ul class="blog-list">
            <script>$(document).ready(function() { load('blogs/our_blogs'); });</script>
        </ul>
    </div>
</section>
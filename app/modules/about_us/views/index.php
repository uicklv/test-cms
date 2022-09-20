<section class="head-block" style="background-image: url('<?= _SITEDIR_ ?>public/images/header_bg4.jpg')">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">
                <contentElement name="about-us" type="input">About us</contentElement>
            </div>
            <h1 class="gen-title">
                <contentElement name="about-us-title" type="input"><span>What</span><br>  we do</contentElement>
            </h1>
        </div>
    </div>
    <a class="explore" href="#explore" onclick="scrollToEl('#explore|500');">Explore <span class="icon-arrow-down-circle"></span></a>
    <span class="pattern_9"><img src="<?= _SITEDIR_ ?>public/images/pattern_9.png" height="297" width="119" alt=""/></span>
</section>

<section id="explore" class="section_1 mar">
    <div class="fixed">
        <h3 class="title">
            <contentElement name="recruitment-rewired" type="input"><span>Recruitment</span><br>Rewired</contentElement>
        </h3>
        <div class="about-flex">
            <div class="af-left">
                <contentElement name="recruitment-rewired-desc"><p>Tech firms move faster than other businesses, so you need a talent partner who can keep up. That’s why we’ve re-engineered recruitment to deliver a game-changing solution that works for skills-hungry start-ups and high-growth early stagers.</p></contentElement>
                <p class="text-small">
                    <contentElement name="recruitment-rewired-desc-small" type="input">Partner with <?= SITE_NAME ?> and you can leverage our connectivity to reach hard-to-find talent, and attract those unicorn candidates who’ll help you deliver your goals. No matter how ambitious.</contentElement>
                </p>
            </div>

            <?php if ($this->video) { ?>
                <div class="af-video anim-wrap">
                    <img class="animated " data-aos="" src="<?= _SITEDIR_ ?>data/videos/<?= $this->video->image; ?>" height="318" width="545" alt=""/>
                    <a class="af-video-icon" data-fancybox="" href="<?= $this->video->video; ?>"><span class="icon-play"></span></a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="section_10">
    <div class="fixed">
        <div class="sectors-flex">
            <h3 class="title"><span>SPECIALIST</span></br> SECTORS</h3>
            <div>
                <p>Our ability to unite talent with opportunity is founded upon deep industry knowledge.</p>
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

<section class="section_connect">
    <div class="fixed">
        <div class="connect-block">
            <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>public/images/connect.jpg" height="667" width="1241" alt=""/></div>
            <div class="connect-cont">
                <div class="title"><span>Connect with</span> <br> <?= SITE_NAME ?></div>
                <div class="connect-text">Discover how <?= SITE_NAME ?> can help power up<br>your business with a high-performance team<br>packed with top talent.</div>
                <a class="btn-yellow" onclick="load('page/arrange_call');">Arrange call back</a>
                <div class="connect-tel">Alternatively call us on <b>+44 0113 468 6700</b></div>
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
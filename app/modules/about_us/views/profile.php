<section class="head-block mar">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">The People</div>
            <h1 class="gen-title"><span><?= $this->profile->firstname; ?></span><br>  <?= $this->profile->lastname; ?></h1>
            <div class="head-link">
                <div><?= $this->profile->job_title; ?></div>
                <div><a href="mailto:<?= $this->profile->email; ?>"><?= $this->profile->email; ?></a></div>
                <div><a href="tel:<?= $this->profile->tel; ?>"><?= $this->profile->tel; ?></a></div>
                <?php if ($this->profile->linkedin) { ?>
                    <div class="social-block"><a href="<?= $this->profile->linkedin; ?>"><span class="icon-LinkedIn"></span></a></div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<style>
    .marketing-block .mb-pic:before {
        background: none;
    }
</style>

<section class="section-grey">
    <div class="fixed">
        <div class="marketing-block">
            <div class="mb-pic">
                <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ ?>data/users/<?= $this->profile->image; ?>" height="580" width="464" alt=""/></div>
                <span class="mb-pic-pattern"></span>
            </div>
            <h3 class="title-small"><?= $this->profile->title; ?></h3>
            <?= reFilter($this->profile->description); ?>
        </div>

        <div class="ja-btn-block" style="margin-top: 60px;">
            <a class="btn-yellow" onclick="load('about_us/contact/<?= $this->profile->slug; ?>');">Contact <?= $this->profile->firstname; ?></a>
        </div>
    </div>
</section>

<?php if ($this->fun_images OR $this->profile->for_fun) { ?>
    <section class="section_11" style="padding: 50px 0;">
        <div class="fixed">
            <div class="fun-flex">
                <div class="fun-pic">
                    <!--style="max-height: 150px; max-width: 150px;"-->
                    <?php foreach ($this->fun_images as $item) { ?>
                        <div class="anim-wrap"><img class="animated " data-aos="" src="<?= _SITEDIR_ . 'data/fun/' . $item->image; ?>" height="225" width="225" alt=""/></div>
                    <?php } ?>
                </div>
                <div class="fun-cont">
                    <h3 class="title-small">Just fOr Fun</h3>
                    <?= reFilter($this->profile->for_fun); ?>
                    <!--<p><span>Dream dinner party guest</span> Paul Gascoigne</p>-->
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<section class="section_5 mar_2">
    <div class="fixed">
        <h3 class="title">Our Blog</h3>
        <ul class="blog-list">
            <script>load('blogs/our_blogs');</script>
        </ul>
    </div>
</section>
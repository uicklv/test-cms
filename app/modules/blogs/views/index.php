<section class="head-block mar">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">
                <contentElement name="our-blog" type="input">Our Blog</contentElement>
            </div>
            <h1 class="gen-title">
                <contentElement name="blog-title" type="input"><span>Industry</span><br> News and<br>Insights</contentElement>
            </h1>
        </div>
    </div>
    <span class="pattern_13"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_13.png" height="235" width="548"/></span>
</section>

<section class="section_blue mar">
    <div class="fixed">
        <div class="blog-zone">
            <div class="bl-category"><?= $this->sectors[$this->blogs[0]->sector]; ?></div>
            <div class="bz-pic anim-wrap"><img  class="animated " data-aos="" src="<?php echo _SITEDIR_; ?>data/blog/mini_<?= $this->blogs[0]->image; ?>" height="566" width="625" alt=""/></div>
            <h3 class="bz-title"><?= $this->blogs[0]->title; ?></h3>
            <div class="bz-text"><?= reFilter($this->blogs[0]->content_before); ?></div>
            <a class="bz-more" href="{URL:blog/<?= $this->blogs[0]->slug; ?>}">Find out more</a>
        </div>
    </div>
</section>

<section>
    <div class="fixed">
        <ul class="blog-list mar">
            <?php
            unset($this->blogs[0]); // remove first item
            foreach ($this->blogs as $blog) {
                ?>
                <li>
                    <div class="bl-category"><?= $this->sectors[$blog->sector]; ?></div>
                    <div class="bl-pic" style="background-image: url('<?= _SITEDIR_ ?>data/blog/mini_<?= $blog->image; ?>')"></div>
                    <div class="bl-name"><?= mb_substr($blog->title, 0, 50); ?>...</div>
                    <div class="bl-more">Find out more <span class="icon-arrow-right"></span></div>
                    <a class="bl-link" href="{URL:blog/<?= $blog->slug; ?>}"></a>
                </li>
                <?php
            }
            ?>
        </ul>

        <?php echo Pagination::printPagination('blogs'); ?>
<!--        --><?php //echo Pagination::ajax('blogs/ajax', ['a' => 5]); ?>
    </div>
</section>

<script>
    jQuery( document ).ready(function($){
        AOS.init({
            useClassNames: true,
            initClassName: false,
            animatedClassName: 'animated',
            once: true,
        });
    });
</script>
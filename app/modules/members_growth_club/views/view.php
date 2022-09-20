<section class="head-block article-page">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">Recent news</div>
            <h1 class="gen-title"><?= $this->blog->title; ?></h1>
        </div>
    </div>
</section>

<div class="fixed">
    <div class="article">
        <?= reFilter($this->blog->content_before); ?>
        <div class="article-pic anim-wrap"><img class="animated " data-aos="" src="<?php echo _SITEDIR_; ?>data/club_blog/<?= $this->blog->image; ?>" height="650" width="1240" alt=""/></div>
        <h2 class="title-article-2"><?= reFilter($this->blog->subtitle); ?></h2>
        <h3 class="title-article-3"><?= reFilter($this->blog->subtitle2); ?></h3>
        <?= reFilter($this->blog->content); ?>

        <div class="share-article">
            Share This Article
            <div class="social-block">
                <a href="{URL:/}"><span class="icon-Twitter"></span></a>
                <a href="{URL:/}"><span class="icon-Facebook"></span></a>
                <a href="{URL:/}"><span class="icon-Instagram"></span></a>
                <a href="{URL:/}"><span class="icon-LinkedIn"></span></a>
            </div>
        </div>
        <div class="nav-article">
            <?php
            if ($this->prev)
                echo '<a class="prev-article" href="{URL:club-blog/'.$this->prev->slug.'}" onclick="load(\'club-blog/'.$this->prev->slug.'\');"><span class="icon-chevron-left"></span> <b>Previous</b></a>';
            else
                echo '<div></div>';
            ?>
            <a class="btn-yellow" href="{URL:members-growth-club}" onclick="load('members-growth-club');">Back to index</a>
            <?php
            if ($this->next)
                echo '<a class="next-article" href="{URL:club-blog/'.$this->next->slug.'}" onclick="load(\'club-blog/'.$this->next->slug.'\');"><b>Next</b> <span class="icon-chevron-right"></span></a>';
            else
                echo '<div></div>';
            ?>
        </div>
    </div>
</div>

<?php /*
<section class="section_5 looking-inner">
    <div class="fixed">
        <h3 class="title">Our Blog</h3>
        <ul class="blog-list">
            <script>$(document).ready(function() { load('blogs/our_blogs'); });</script>
        </ul>
    </div>
</section>
*/ ?>
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
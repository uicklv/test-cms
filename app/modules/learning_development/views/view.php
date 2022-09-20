<style>
    .article-flex p {
        margin-bottom: 18px;
    }

    .article-flex ul li {
        margin-bottom: 4px;
    }
</style>
<div class="head-block" style="background-image: url('<?= _SITEDIR_ ?>data/learning_development/<?= $this->blog->image ?>')">
    <div class="fixed">
        <div class="head-cont head-cont-end" data-aos="zoom-in" data-aos-duration="1500">
            <div>
                <a class="start-search" href="{URL:resources/category/<?= $this->blog->category_name->id ?>}">Back to <?= $this->blog->category_name->name ?></a>
            </div>
        </div>
    </div>
</div>
<div class="fixed">
    <div class="article-block">
        <h3 class="section-title" data-aos="fade-up" data-aos-duration="1500"><?= $this->blog->category_name->name ?></h3>
        <h1 class="title-small" data-aos="zoom-in" data-aos-duration="1500"><?= $this->blog->title ?></h1>
        <div class="article-flex">
            <div class="ar-sidebar"  data-aos="fade-up" data-aos-duration="1500">
                <?php if ($this->next) { ?>
                    <div class="ar-link"><a href="{URL:learning-development-resource/<?= $this->next->slug ?>}">Next</a></div>
                <?php } ?>
                <?php if ($this->prev) { ?>
                    <div class="ar-link"><a href="{URL:learning-development-resource/<?= $this->prev->slug ?>}">Previous</a></div>
                <?php } ?>
            </div>
            <div class="ar-cont"  data-aos="fade-up" data-aos-duration="1500">
                <?= reFilter($this->blog->content) ?>
                <?php if ($this->blog->video) { ?>
                <div class="article-video" style="text-align: center;">
                    <!--                <video controls  poster="../app/public/images//video.jpg">-->
                    <!--                    <source src="../app/public/images/AdobeStock_184961207_Video_HD_Preview.mp4" type='video/mp4;'>-->
                    <!--                </video>-->
                    <?php /*
                    <a data-fancybox href="<?= $this->blog->video ?>"><img src="<?= _SITEDIR_ ?>data/learning_development/<?= $this->blog->video_poster ?>"></a>
                    */ ?>
                    <iframe width="100%" height="700" src="<?= $this->blog->video ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <?php } ?>
                <br>
                <br>
                <div class="sb-info">
                    <?php if ($this->blog->file) { ?>
                        <div>
                            <h3 class="sb-info-title">Download PDF Title</h3>
                            <a class="sb-download" href="<?= _SITEDIR_ ?>data/learning_development/<?= $this->blog->file ?>" download="file.<?= File::format($this->blog->file )?>"><span class="icon-download"></span></a>
                        </div>
                    <?php } ?>
                </div>
                <br>
                <label class="check-finished">
                    <input type="checkbox" onchange="load('learning_development/change_status', 'resource_id=<?=$this->blog->id?>', 'category_id=<?= $this->blog->category_name->id ?>');"
                    <?php if ($this->blog->completed) echo 'checked'; ?>>
                    <span class="cf-title">I have finished this resource</span>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="separator"></div>
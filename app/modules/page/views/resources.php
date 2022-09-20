<section class="section_5 looking-inner">
    <div class="fixed">
        <h3 class="title">Resources</h3>
        <ul class="blog-list">
            <?php
            foreach ($this->list as $item) {
                ?>
                <li>
                    <div class="bl-category"><?= $item->type; ?></div>
                    <div class="bl-pic" style="background-image: url('<?= _SITEDIR_ ?>data/resources/mini_<?= $item->image; ?>')"></div>
                    <div class="bl-name"><?= $item->title ?></div>
                    <div class="bl-more">Click for Download File <span class="icon-arrow-right"></span></div>
                    <a class="bl-link pointer" onclick="load('page/download_resource/<?= $item->id ?>')"></a>
                </li>
                <?php
            }
            ?>

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
        </ul>
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
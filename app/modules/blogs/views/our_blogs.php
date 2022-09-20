<?php
foreach ($this->blogs as $blog) {
    ?>
    <li>
        <div class="bl-category"><?= $this->sectors[$blog->sector]; ?></div>
        <div class="bl-pic" style="background-image: url('<?= _SITEDIR_ ?>data/blog/<?= $blog->image; ?>')"></div>
        <div class="bl-name"><?= mb_substr($blog->title, 0, 50); ?>...</div>
        <div class="bl-more">Find out more <span class="icon-arrow-right"></span></div>
        <a class="bl-link" href="{URL:blog/<?= $blog->slug; ?>}"></a>
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

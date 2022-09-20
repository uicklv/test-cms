<section class="section-inner" style="margin: 100px 0 100px 0">
    <style>
        .sb-text p {
            margin-bottom: 10px;
        }
    </style>

    <div class="fixed">
        <div class="gt-flex">
            <h1 class="gen-title" data-aos="zoom-in" data-aos-duration="1500">
                <p>Client Portal</p>
                <?php if (User::get('access') != 'limited'){ ?>
                    <a class="portal-link" href="{URL:portal/offers}"  data-aos="fade-left" data-aos-duration="1500">View Offers and Placed Candidates</a>
                <?php } ?>
            </h1>

        </div>

        <h3 class="title-small">Open Roles</h3>
        <ul class="candidates-list">
            <?php if (is_array($this->list) && count($this->list) > 0) foreach ($this->list as $item){ ?>
                <!--                class="active"-->
                <li class="candidate-block">
                    <h3><?= $item->title ?></h3>
                    <a onclick="load('portal/get_candidates/<?= $item->id ?>'); return false;" style="cursor: pointer;">Open Candidates</a>
                    <!--                <div class="candidate-block-links">-->
                    <div id="advert_<?= $item->id ?>" class="advert_rem">
                        <?php /**/ ?>
                        <a onclick="newTab('<?= SITE_URL ?>job/<?= $item->slug ?>')" style="cursor: pointer;">View Advert</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <div id="candidates_box">
        </div>
    </div>
</section>

<section class="separator"></section>
<script>
    $(".candidate-block").click(function() {

        $(".candidate-block").removeClass("active");
        $(this).addClass("active");
    });

    function newTab(link) {
        var myWindow = window.open(link, "", "width=700,height=1000");
    }

</script>
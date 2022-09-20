<section class="head-block mar head-block-small"><!-- style="background-image: url('<?= _SITEDIR_?>public/images/head-inner_bg5.jpg')"-->
    <div class="fixed">
        <div class="head-cont">
            <div>
                <div class="gen-title"><span>Email Confirmation</span></div>
            </div>
        </div>
    </div>
</section>


<section class="section-register" data-aos="fade-up">
    <div class="fixed" >
        <div class="account-reg mar">
            <div class="ar-right">
                <?php if ($this->error) { ?>
                <div class="title-small">Email confirmation error</div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
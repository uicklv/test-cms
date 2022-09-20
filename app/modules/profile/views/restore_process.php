<section class="head-block mar head-block-small"><!-- style="background-image: url('<?= _SITEDIR_?>public/images/head-inner_bg5.jpg')"-->
    <div class="fixed">
        <div class="head-cont">
            <div>
                <div class="gen-title"><span>Restore Password</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section-login" data-aos="fade-up">
    <div class="fixed">
        <div class="account-reg mar">
            <div class="ar-right">
                <?php if (isset($this->errors) && $this->errors) { ?>
                    <div class="alert alert-danger mb-4" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                        <?php echo is_array($this->errors) ? implode("<br/>", $this->errors) : $this->errors; ?>
                    </div>
                <?php } else { ?>
                <div class="title-small">Enter new password</div>
                <form class="first-search" id="restore_form">
                    <div class="first__inputs">
                        <div class="fs-flex">
                            <div class="fs-cell"><input type="password"  name="password" placeholder="Password" class="first__input"></div>
                        </div>
                        <div class="fs-flex">
                            <div class="fs-cell"><input type="password"  name="password2" placeholder="Confirm Password" class="first__input"></div>
                        </div>
                        <div class="fs-flex fs-flex-sub">
                            <button type="submit" class="more-link" onclick="load('restore-process?email=<?= get('email') ?>&hash=<?= get('hash') ?>', 'form:#restore_form'); return false;">Log In</button>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </div>

        </div>
    </div>
</section>
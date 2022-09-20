<span class="close-popup" onclick="closePopup();"><span class="icon-close"></span></span>

<h3 class="title-popup">Apply Now - <?= $this->job->title; ?></h3>
<form id="apply_form" class="popup-form">
    <div class="pc-inner">
        <div class="pc-field">
            <label class="pc-label">Full Name</label>
            <input class="pf-text-field" type="text" name="name" value="<?= trim(User::get('firstname', 'candidate') . ' ' . User::get('lastname', 'candidate')) ?>" placeholder="Type your full name">
        </div>
        <div class="pc-field">
            <label class="pc-label">Email</label>
            <input class="pf-text-field" type="text" name="email" value="<?= User::get('email', 'candidate') ?>" placeholder="Type your email">
        </div>
        <div class="pc-field">
            <label class="pc-label">Telephone Number</label>
            <input class="pf-text-field" type="text" name="tel" value="<?= User::get('tel', 'candidate') ?>" placeholder="Type your telephone">
        </div>
        <div class="pc-field">
            <label class="pc-label">LinkedIn</label>
            <input class="pf-text-field" type="text" name="linkedin" value="<?= User::get('linkedin', 'candidate') ?>" placeholder="Paste your LinkedIn profile">
        </div>

        <div class="pc-cv-field">
            <label class="pc-label pc-cv-name mb10">CV file: <span class="cv_file_name"><?php if (User::get('cv', 'candidate')) { echo 'cv.' . File::format(User::get('cv', 'candidate')); }?></span></label>
            <label class="custom-file-upload">
                <input type="file" name="cv_field" accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('about_us/upload/', 'field=#cv_field', 'preview=.cv_file_name');">
                <input type="hidden" name="cv_field" id="cv_field" value="<?= post('cv_field', false, User::get('cv', 'candidate')); ?>">
                <span class="pc-label"><i class="fa fa-upload" aria-hidden="true"></i> Upload your file here</span>
            </label>
        </div>

        <label class="checkBox">
            <input type="checkbox" name="check" value="yes">
            <span class="check-title pc-label">I have read and agree with the <a href="{URL:privacy-policy}">Privacy Policy</a></span>
        </label>

        <?php if (Request::getParam('recaptcha_status')) { ?>
                <?php /* V2
            <!-- comment me for turn off -->
            <div class="pc-field pc-captcha">
                <div class="g-recaptcha" data-sitekey="<?= Request::getParam('site_key') ?>"></div>
            </div>
 */ ?>
            <input type="hidden" id="g-recaptcha-response-apply-form" name="g-recaptcha-response">
        <?php } ?>

        <button class="pc-btn" type="submit" id="apply_form_submit" onclick="reCaptcha(); load('jobs/apply_now/<?= $this->job->slug; ?>', 'form:#apply_form'); return false;">Apply Now</button>
    </div>
</form>

<script>
    $("#site").addClass('popup-open');
</script>

<?php if (Request::getParam('recaptcha_status')) { ?>
<?php /* V2
    <script src='https://www.google.com/recaptcha/api.js'></script>
 */?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= Request::getParam('site_key') ?>"></script>
<script>
    function reCaptcha() {
        grecaptcha.ready(function () {
            // do request for recaptcha token
            // response is promise with passed token
            grecaptcha.execute(<?= Request::getParam('site_key') ?>, {action: 'apply_form_submit'})
                .then(function (token) {
                    // add token value to form
                    document.getElementById('g-recaptcha-response-apply-form').value = token;
                });
        });
    }
</script>
<?php } ?>
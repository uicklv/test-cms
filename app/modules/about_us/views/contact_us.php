<section class="head-block mar looking-inner">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title-name">Contact Us</div>
            <h1 class="gen-title"><span>Lets</span><br>  Talk</h1>
        </div>
    </div>
    <span class="pattern_13"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_13.png" height="235" width="548"/></span>
</section>

<section class="section_16" style="padding-bottom: 0;">
    <div class="fixed">
        <form id="contact_form" class="contact-form">
            <h3 class="title-small">YOur details</h3>
            <div class="cf-row">
                <label class="cf-label">Full name</label>
                <input class="cf-text-field" type="text" name="name" id="name" placeholder="Type name" value="<?= post('name', false); ?>">
            </div>
            <div class="cf-row">
                <label class="cf-label">Email</label>
                <input class="cf-text-field" type="text" name="email" id="email" placeholder="Type email" value="<?= post('email', false); ?>">
            </div>
            <div class="cf-row">
                <label class="cf-label">Contact Number</label>
                <input class="cf-text-field" type="text" name="tel" id="tel" placeholder="Type tel" value="<?= post('tel', false); ?>">
            </div>
            <div class="cf-row">
                <label class="cf-label">Further information</label>
                <input class="cf-text-field" type="text" name="message" id="message" placeholder="Type further information" value="<?= post('message', false); ?>">
            </div>
            <div class="cf-row cf-linked">
                <label class="cf-label">LinkedIn</label>
                <input class="cf-text-field" type="text" name="linkedin" id="linkedin" placeholder="Paste LinkedIn profile link" value="<?= post('linkedin', false); ?>">
            </div>
            <ul class="cf-list">
                <li>
                    <div class="cf-file-block">
                        <input type="hidden" name="job_spec" id="job_spec" value="<?= post('job_spec', false); ?>">
                        <input type="file" accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('about_us/upload/', 'field=#job_spec', 'preview=#job_spec_name');">
                        <div id="job_spec_name">Upload your job spec</div>
                        <div class="cf-file-icon"></div>
                    </div>
                </li>

                <li>
                    <div class="cf-file-block">
                        <input type="hidden" name="cv" id="cv" value="<?= post('cv', false); ?>">
                        <input type="file" accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('about_us/upload/', 'field=#cv', 'preview=#cv_name');">
                        <div id="cv_name">Attach your CV</div>
                        <div class="cf-file-icon"></div>
                    </div>
                </li>

                <li>
                    <div class="cf-file-block cf-linked-open">
                        <div class="cf-linked-icon"><span class="icon-linked"></span></div>
                        <div>Attach your Profile</div>
                        <div class="cf-file-icon"></div>
                    </div>
                </li>
            </ul>
            <label class="checkBox">
                <input type="checkbox" name="check" value="yes">
                <span class="check-title">Your privacy rights are important to us. When you click SUBMIT after filling out this form and providing us with your personal information, such as contact details or your CV, we will process your personal data in accordance with our  <a href="{URL:privacy-policy}" target="_blank">privacy policy</a>.</span>
            </label>
            <!--<div class="cf-policy">Your privacy rights are important to us. When you click SUBMIT after filling out this form and providing us with your personal information, such as contact details or your CV, we will process your personal data in accordance with our  <a href="{URL:/}">privacy policy.</a></div>-->

            <?php if (Request::getParam('recaptcha_status')) { ?>
                <input type="hidden" id="g-recaptcha-response-contact-form" name="g-recaptcha-response">
            <?php } ?>
            <button class="btn-yellow" id="contact_form_submit" onclick="reCaptcha(); load('contact-us', 'form:#contact_form'); return false;">Submit</button>
        </form>
    </div>
    <span class="pattern_24"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_24.png" height="302" width="182" alt=""/></span>
</section>

<section class="section_17">
    <div class="fixed">
        <ul class="address-list">
            <li>
                <div class="ad-cont">
                    <h3 class="title">Leeds</h3>
                    <?= SITE_NAME ?> Technology Limited<br>
                    The Leeming Building<br>
                    Vicar Lane<br>
                    Leeds<br>
                    LS2 7JF<br>
                    <a class="ad-directions" href="https://www.google.com/maps?q=<?= urlencode('Amsource Technology, The Leeming Building, Vicar Ln, Leeds LS2 7JF'); ?>" target="_blank">Get directions</a>
                    <div class="ad-tel"><a href="tel:+4401134686700">+44 0113 468 6700</a></div>
                </div>
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2356.565068819426!2d-1.5419746840459556!3d53.7972281485432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48795c1852dca3d5%3A0xf3780bde6f2c0075!2sAmsource%20Technology!5e0!3m2!1sru!2sua!4v1579670286416!5m2!1sen!2sus" width="100%" height="554" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                </div>
            </li>
            <li>
                <div class="ad-cont">
                    <h3 class="title">Berlin</h3>
                    <?= SITE_NAME ?> Technology Limited<br>
                    3rd Floor<br>
                    Pariser Platz  6a,<br>
                    10117<br>
                    Berlin<br>
                    <a class="ad-directions" href="https://www.google.com/maps?daddr=<?= urlencode('Pariser Platz 7, 10117 Berlin'); ?>" target="_blank">Get directions</a>
                    <div class="ad-tel"><a href="tel:+49303001493266">+49 30 300 149 3266</a></div>
                </div>
                <div class="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2427.9138275250307!2d13.375642115916564!3d52.51689854421132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a851c6fd6a0d57%3A0x9890e5ad18d111c6!2zUGFyaXNlciBQbGF0eiA2QSwgMTAxMTcgQmVybGluLCDQk9C10YDQvNCw0L3QuNGP!5e0!3m2!1sru!2sua!4v1579671407010!5m2!1sen!2sus" width="100%" height="615" frameborder="0" style="border:0;" allowfullscreen=""></iframe></div>
            </li>
        </ul>
    </div>
    <span class="pattern_25"><img src="<?php echo _SITEDIR_; ?>public/images/pattern_25.png" height="115" width="173" alt=""/></span>
</section>

<?php if (Request::getParam('recaptcha_status')) { ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= Request::getParam('site_key') ?>"></script>
    <script>
        function reCaptcha() {
            grecaptcha.ready(function () {
                // do request for recaptcha token
                // response is promise with passed token
                grecaptcha.execute(<?= Request::getParam('site_key') ?>, {action: 'contact_form_submit'})
                    .then(function (token) {
                        // add token value to form
                        document.getElementById('g-recaptcha-response-contact-form').value = token;
                    });
            });
        }
    </script>
<?php } ?>
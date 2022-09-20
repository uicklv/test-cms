<span class="close-popup" onclick="closePopup();"><span class="icon-close"></span></span>

<h3 class="title-popup">Contact - <?= $this->get->firstname; ?></h3>
<form id="apply_form" class="popup-form">
    <div class="pf-flex">
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Name</label>
                <input class="pf-text-field" type="text" name="name" placeholder="Type your name">
            </div>
        </div>
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Email</label>
                <input class="pf-text-field" type="text" name="email" placeholder="Type your email">
            </div>
        </div>
    </div>

    <div>
        <div class="pf-row">
            <label class="pf-label">Message</label>
            <textarea class="pf-text-field" name="message" style="min-height: 130px; max-height: 200px; padding-top: 12px; padding-bottom: 12px;"></textarea>
        </div>
    </div>

<!--    <label class="checkBox">-->
<!--        <input type="checkbox" name="check" value="yes">-->
<!--        <span class="check-title">I have read and agree with the <a href="{URL:privacy-policy}" style="color: #64C2C8;">Privacy Policy</a></span>-->
<!--    </label>-->

    <button class="btn-yellow" type="submit" onclick="load('about_us/contact/<?= $this->get->slug; ?>', 'form:#apply_form'); return false;">SEND</button>
</form>

<script>
    $("#site").addClass('popup-open');
</script>
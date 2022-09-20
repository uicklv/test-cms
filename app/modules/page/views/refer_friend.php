<span class="close-popup" onclick="closePopup();"><span class="icon-close"></span></span>

<h3 class="title-popup">Refer a friend</h3>
<form id="refer_form" class="popup-form refer_form">
    <div class="pf-flex">
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Your name</label>
                <input class="pf-text-field" type="text" name="your_name" placeholder="Type your name">
            </div>
            <div class="pf-row">
                <label class="pf-label">Your email</label>
                <input class="pf-text-field" type="text" name="your_email" placeholder="Type your email">
            </div>
            <div class="pf-row">
                <label class="pf-label">Your telephone number</label>
                <input class="pf-text-field" type="text" name="your_tel" placeholder="Type your number">
            </div>
        </div>
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Your friend's name</label>
                <input class="pf-text-field" type="text" name="friend_name" placeholder="Type friend name">
            </div>
            <div class="pf-row">
                <label class="pf-label">Your friend's email</label>
                <input class="pf-text-field" type="text" name="friend_email" placeholder="Type friend email">
            </div>
            <div class="pf-row">
                <label class="pf-label">Your friend's telephone number</label>
                <input class="pf-text-field" type="text" name="friend_tel" placeholder="Type friend number">
            </div>
        </div>
    </div>

    <label class="checkBox">
        <input type="checkbox" name="check" value="yes">
        <span class="check-title">I have read and agree with the <a href="{URL:privacy-policy}" style="color: #64C2C8;">Privacy Policy</a></span>
    </label>
<!--        <label class="checkBox">-->
<!--            <input type="checkbox" name="check" value="yes">-->
<!--            <span class="check-title">Please contact me regarding Amsource products and services *</span>-->
<!--        </label>-->
    <button class="btn-yellow" type="submit" onclick="load('page/refer_friend', 'form:#refer_form'); return false;">Send</button>
</form>

<script>
    $("#site").addClass('popup-open');
</script>
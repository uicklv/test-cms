<span class="close-popup" onclick="closePopup();"><span class="icon-close" onclick="closePopup();"></span></span>

<h3 class="title-popup">Request Feedback</h3>
<form id="apply_form" class="popup-form">
    <div class="pf-flex">
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Name</label>
                <input class="pf-text-field" type="text" name="name">
            </div>
            <div class="pf-row">
                <label class="pf-label">Company</label>
                <input class="pf-text-field" type="text" name="company">
            </div>
            <div class="pf-row">
                <label class="pf-label">Email</label>
                <input class="pf-text-field" type="text" name="email">
            </div>
        </div>
    </div>

    <label class="checkBox">
        <input type="checkbox" name="check" value="yes">
        <span class="check-title">I consent for The Talent Vault to contact me with further info regarding this candidate</span>
    </label>

    <button class="btn-yellow" type="submit" onclick="load('feedback/<?= $this->profile->id ?>', 'form:#apply_form', 'rating#rating-input', 'feedback#feedback'); return false;">SUBMIT</button>
</form>

<script>
    $("#site").addClass('popup-open');
</script>
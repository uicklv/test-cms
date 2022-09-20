<span class="close-popup" onclick="closePopup();"><span class="icon-close"></span></span>

<h3 class="title-popup">Upload CV</h3>
<form id="upload_cv_form" class="popup-form">
    <div class="pf-flex">
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Full Name</label>
                <input class="pf-text-field" type="text" name="name" placeholder="Type your full name">
            </div>
            <div class="pf-row">
                <label class="pf-label">Telephone Number</label>
                <input class="pf-text-field" type="text" name="tel" placeholder="Type your telephone">
            </div>
        </div>
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Email</label>
                <input class="pf-text-field" type="text" name="email" placeholder="Type your email">
            </div>
            <div class="pf-row">
                <label class="pf-label">LinkedIn</label>
                <input class="pf-text-field" type="text" name="linkedin" placeholder="Paste your LinkedIn profile">
            </div>
        </div>
    </div>

    <div>
        <div class="pf-row">
            <label class="pf-label">CV file <span class="cv_file_name" style="color: #64C2C8;"></span></label>
            <input class="pf-text-field" type="file" name="cv_field" style="border: none; padding: 0;"
                   accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('about_us/upload/', 'field=#cv_field', 'preview=.cv_file_name');">
            <input type="hidden" name="cv_field" id="cv_field" value="<?= post('cv_field', false); ?>">
        </div>
    </div>

    <label class="checkBox">
        <input type="checkbox" name="check" value="yes">
        <span class="check-title">I have read and agree with the <a href="{URL:privacy-policy}" style="color: #64C2C8;">Privacy Policy</a></span>
    </label>

    <button class="btn-yellow" type="submit" onclick="load('about_us/upload_cv', 'form:#upload_cv_form'); return false;">Upload CV</button>
</form>

<script>
    $("#site").addClass('popup-open');
</script>
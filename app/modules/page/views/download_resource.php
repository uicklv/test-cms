<span class="close-popup" onclick="closePopup();"><span class="icon-close"></span></span>

<h3 class="title-popup">Download resource</h3>
<form id="resource_form" class="popup-form">
    <div class="pc-inner">
        <div class="pc-field">
            <label class="pc-label">First Name</label>
            <input class="pf-text-field" type="text" name="firstname" placeholder="Type your first name">
        </div>
        <div class="pc-field">
            <label class="pc-label">Last Name</label>
            <input class="pf-text-field" type="text" name="lastname" placeholder="Type your last name">
        </div>
        <div class="pc-field">
            <label class="pc-label">Email</label>
            <input class="pf-text-field" type="text" name="email"  placeholder="Type your email">
        </div>

        <button class="pc-btn" type="submit" id="button-popup" onclick="">Download</button>
    </div>
</form>

<script>
    $("#site").addClass('popup-open');
</script>


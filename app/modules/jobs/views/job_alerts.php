<span class="close-popup" onclick="closePopup();"><span class="icon-close"></span></span>

<h3 class="title-popup">Job Alerts</h3>
<form id="apply_form" class="popup-form">
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
            <div class="pf-row">
                <label>Industries/Sectors</label>
                <div class="multiple_checkboxes_box">
                    <?php if (isset($this->sectors) && is_array($this->sectors) && count($this->sectors) > 0) { ?>
                        <?php foreach ($this->sectors as $item) { ?>
                            <label class="checked-label">
                                <input type="checkbox" class="check" name="sector_ids[]" value="<?= $item->id; ?>"
                                    <?= checkCheckboxValue(post('sector_ids'), $item->id); ?>
                                >
                                <span class="checkmark"><i class="fa fa-check"></i></span>
                                <div class="name"><?= $item->name; ?></div>
                            </label>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Email</label>
                <input class="pf-text-field" type="text" name="email" placeholder="Type your email">
            </div>
            <div class="pf-row">
                <label>Locations</label>
                <div class="multiple_checkboxes_box">
                    <?php if (isset($this->locations) && is_array($this->locations) && count($this->locations) > 0) { ?>
                        <?php foreach ($this->locations as $item) { ?>
                            <label class="checked-label">
                                <input type="checkbox" class="check" name="location_ids[]" value="<?= $item->id; ?>">
                                <span class="checkmark"><i class="fa fa-check"></i></span>
                                <div class="name"><?= $item->name; ?></div>
                            </label>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <label class="checkBox">
        <input type="checkbox" name="check" value="yes">
        <span class="check-title">I have read and agree with the <a href="{URL:privacy-policy}" style="color: #64C2C8;">Privacy Policy</a></span>
    </label>

    <a class="btn btn-center"  onclick="load('jobs/job_alerts', 'form:#apply_form'); return false;">APPLY NOW</a>
</form>

<script>
    $("#site").addClass('popup-open');
</script>
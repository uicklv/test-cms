<?php if (is_array($this->roles) && count($this->roles) > 0) { ?>
<label for="Job Role">Job Role<span class="pink">*</span></label>
<select name="job_role" id="job_role">
    <option value="">Please select job role</option>
    <?php foreach ($this->roles as $item) { ?>
        <option value="<?= $item->id ?>"><?= $item->name ?></option>
    <?php } ?>
</select>
<?php } ?>

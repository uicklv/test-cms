<style>
    .file-block span i {
        font-weight: 400;
        color: blue;
    }
</style>

<section class="head-block mar head-block-small"><!-- style="background-image: url('<?= _SITEDIR_?>public/images/head-inner_bg5.jpg')"-->
    <div class="fixed">
        <div class="head-cont">
            <div>
                <div class="gen-title"><span>MY ACCOUNT</span></div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="fixed">
        <div class="account-flex">
            <div class="ac-left">
                <?php /*
                <a class="btn btn-full" href="{URL:saved-jobs/<?= User::get('id') ?>}">SAVED JOBS</a>
                */ ?>
                <button class="btn btn-full btn-yellow" onclick="if (confirm('Are you sure you wish to remove your account? This will remove your personal details from our database and cannot be undone.'))
                        load('profile/delete_account')">DELETE MY ACCOUNT</button>
                <button class="btn btn-full btn-yellow" onclick="load('profile/logout'); return false;">LOG OUT</button>
            </div>
            <form class="ac-cont" id="form_profile">
                <div class="ac-section">
                    <h3 class="ac-title">Personal information</h3>
                    <div class="ac-flex">
                        <div class="ac-cell">
                            <label>Your first name</label>
                            <input class="text-field" type="text" name="firstname" value="<?= User::get('firstname', 'candidate') ?>">
                        </div>
                        <div class="ac-cell">
                            <label>Your last name</label>
                            <input class="text-field" type="text" name="lastname" value="<?= User::get('lastname', 'candidate') ?>">
                        </div>
                        <div class="ac-cell">
                            <label>Your email address</label>
                            <input class="text-field" type="text" name="email" value="<?= User::get('email', 'candidate') ?>">
                        </div>
                        <div class="ac-cell">
                            <label>Your phone number</label>
                            <input class="text-field" type="text" name="tel" value="<?= User::get('tel', 'candidate') ?>">
                        </div>
                    </div>
                </div>

                <div class="ac-section">
                    <h3 class="ac-title">Employment information</h3>
                    <div class="ac-flex">
                        <div class="ac-cell">
                            <label>Your location</label>
                            <input class="text-field" type="text" name="location" value="<?= User::get('location', 'candidate') ?>">
                        </div>
                        <div class="ac-cell">
                            <label>Your current job title</label>
                            <input class="text-field" type="text" name="job_title" value="<?= User::get('job_title', 'candidate') ?>">
                        </div>
                        <div class="ac-cell">
                            <label>Current employment status</label>
                            <input class="text-field" type="text" name="employment_status"  value="<?= User::get('employment_status', 'candidate') ?>">
                        </div>
                        <div class="ac-cell">
                            <label>Interview availability</label>
                            <input class="text-field" type="text" name="interview_availability" value="<?= User::get('interview_availability', 'candidate') ?>">
                        </div>
                    </div>
                </div>

                <div class="ac-section">
                    <h3 class="ac-title">Your cv</h3>
                    <div class="ac-flex">
                        <div class="ac-cell ac-cell-full">
                            <div class="down-link">
                                <?php if (User::get('cv', 'candidate')) { ?>
                                    <i class="fas fa-download"></i>
                                    <a href="<?= _SITEDIR_ . 'data/cvs/' . User::get('cv', 'candidate'); ?>"
                                        download="<?= makeSlug('CV_' . User::get('firstname', 'candidate') . ' ' . User::get('lastname', 'candidate')) . '.' . File::format(User::get('cv', 'candidate')); ?>">Download current CV</a>
                                <?php } ?>
                            </div>
                            <div class="file-block">
                                <input type="file" name="cv_field" accept=".doc, .docx, .txt, .pdf, .fotd" onchange="initFile(this); load('about_us/upload/', 'field=#cv', 'preview=#cv_name');">
                                <input type="hidden" name="cv" id="cv" value="<?= post('cv', false, User::get('cv', 'candidate')); ?>">
                                <span>Drag & Drop Here&nbsp;&nbsp;<i id="cv_name"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="ac-section">
                        <h3 class="ac-title">Change password</h3>
                        <div class="ac-flex">
                            <div class="ac-cell">
                                <label>Your new password</label>
                                <input class="text-field" type="password" name="password">
                            </div>
                            <div class="ac-cell">
                                <label>Confirm your new password</label>
                                <input class="text-field" type="password" name="password2">
                            </div>
                        </div>
                    </div>

                    <div class="ac-section">
                        <h3 class="ac-title">Job alerts</h3>
                        <div class="ac-flex">

                            <div class="ac-cell ac-cell-full">
                                <label>Sectors</label>
                                <div class="alerts-check-block">
                                    <?php if ($this->sectors) foreach ($this->sectors as $sector){ ?>
                                        <div>
                                            <label class="ac-check">
                                                <input type="checkbox" name="sectors[]" value="<?= $sector->id ?>"
                                                <?= checkCheckboxValue(post('sectors'), $sector->id, explode('||', trim(User::get('sectors', 'candidate'), '|'))) ?>>
                                                <span class="ac-check-title"><?= $sector->name ?></span>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <div class="ac-cell ac-cell-full">
                            <label>Desired locations</label>
                            <div class="alerts-check-block">
                                <?php if ($this->locations) foreach ($this->locations as $location){ ?>
                                <div>
                                    <label class="ac-check">
                                        <input type="checkbox" name="locations[]" value="<?= $location->id ?>"
                                            <?= checkCheckboxValue(post('locations'), $location->id, explode('||', trim(User::get('locations', 'candidate'), '|'))) ?>>
                                        <span class="ac-check-title"><?= $location->name ?></span>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="ac-row-last">
                        <input class="ac-sub" type="submit" value="UPDATE YOUR PROFILE" onclick="load('profile/profile', 'form:#form_profile'); return false;">
                    </div>
            </form>
        </div>
    </div>
</section>


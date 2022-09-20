<div class="head-block mar looking-inner">
    <div class="fixed">
        <section>

                <div class="banner-effect"></div>
                <div class="cust-container">
                    <div class="banner-cont">
                        <h1 class="gen-title main-title wow fadeInUp" style="margin-bottom: 30px">Salary Survey </h1>
                    </div>
                </div>
        </section>

        <section class="salary-survey">
            <div class="cust-container" id="result_box">
                <form id="salarysurvey" method="post" accept-charset="UTF-8" class="request">
                    <div class="all--check" style="display: block;">

                        <div class="job--details white">
                            <h2 class="title-small">Job Details</h2>

                            <div class="fs-flex">
                                <div class="fs-cell">
                                    <label for="industry_sector">Industry Sector <span class="pink">*</span></label>
                                    <select name="sector_id" id="sector_id">
                                        <option value="">Please select sector</option>
                                        <?php foreach ($this->sectors as $sector) { ?>
                                            <option value="<?= $sector->id ?>"><?= $sector->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="fs-cell">
                                    <label for="Job Type">Job Type<span class="pink">*</span></label>

                                    <select name="job_type" id="job_type">
                                        <option value="">Please select job type</option>
                                        <?php foreach ($this->job_types as $type) { ?>
                                            <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="fs-cell" id="roles_box">
                                    <label for="Job Role">Job Role<span class="pink">*</span></label>

                                    <select name="job_role" id="job_role">
                                        <option value="">Please select job role</option>
                                    </select>
                                </div>
                                <div class="fs-cell">
                                    <label for="Location">Location<span class="pink">*</span></label>

                                    <select name="location" id="location">
                                        <option value="">Please select location</option>
                                        <?php foreach ($this->locations as $location) { ?>
                                            <option value="<?= $location->id ?>"><?= $location->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="fs-cell">
                                    <label for="Current Basic Salary">Current Basic Salary<span class="pink">*</span></label>

                                    <i class="fa fa-info-circle tooltip tooltipstered" title="Annual salary, before the bonus (before tax)"></i>
                                    <input class="pound" disabled="disabled" name="base_salary_logo" type="text" value="£" />
                                    <input class="base--salary" name="base_salary" type="number" />
                                </div>
                                <div class="fs-cell">
                                    <label for="Experience">Experience<span class="pink">*</span></label>

                                    <i class="fa fa-info-circle tooltip tooltipstered" title="Number of years in marketing roles"></i>
                                    <select name="text_experience" id="text_experience">
                                        <option value="">Please select experience</option>
                                        <?php foreach ($this->experiences as $experience) { ?>
                                            <option value="<?= $experience->id ?>"><?= $experience->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>

                        <div class="job--details grey">
                            <h2 class="title-small">Your Details <span>(so we can send you future salary insights)</span></h2>
                            <div class="fs-flex">
                                <div class="fs-cell">
                                    <?php
                                    $fullName = User::get('firstname') . ' ' . User::get('lastname');
                                    ?>
                                    <label for="Full Name">Full Name<span class="pink">*</span></label>

                                    <input class="" name="name" type="text" value="<?= $fullName ?>" />
                                </div>
                                <div class="fs-cell">
                                    <label for="Email Address">Email Address<span class="pink">*</span></label>

                                    <input class="" name="email" type="text" value="<?=User::get('email') ?>" />
                                </div>
                                <div class="fs-cell">
                                    <label for="Current Company Name">Current Company Name <span class="optional">(Optional)</span></label>

                                    <input class="" name="current_company" type="text" />
                                </div>
                                <div class="fs-cell">
                                    <label for="What salary increase would you move for?">What Salary Increase Would You Move For?<span class="optional">(if any)</span></label>

                                    <select class="" name="salary_move">
                                        <option value="">Please select Salary Increase</option>
                                        <?php foreach ($this->salary_increases as $salary) { ?>
                                            <option value="<?= $salary->id ?>"><?= $salary->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>
                        </div>


                        <div class="job--details submit grey">
                            <div class="fs-flex fs-flex-sub">

                                <div class="value">
                                    <label class="checked__label">
                                        <input type="checkbox"  id="terms_and_conditions" name="check" value="1">
                                        <div class="value__name">I agree with the <a href="{URL:terms-conditions}" target="_blank" class="show--terms">terms and conditions</a></div>
                                    </label>
                                </div>
                                <button class="more-link" type="submit" onclick="load('salary-survey', 'form:#salarysurvey'); return false;" name="submit">See Search Results</button>
                            </div>
                        </div>

                        <br>
                        <br>
                </form>
            </div>
        </section>
    </div>
</div>
<script>
    $("#job_type").on("change", function (e) {
        load('get-role', 'job_type=' + this.value);
    });
</script>


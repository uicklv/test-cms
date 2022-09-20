<section class="head-block mar head-block-small">
    <div class="fixed">
        <div class="head-cont">
            <?php /*
            <div class="gen-title"><span><?= $this->profile->job_title ?></span></div>
            */ ?>
        </div>
    </div>
</section>


<div class="consultant-sec">
    <div class="fixed">
        <div class="sec-flex">
            <div class="left-sec">
                <div class="left-sec-head">
                    <div>Your Consultant</div>
                    <div class="img-sec">
                        <div style="background-image: url('<?= _SITEDIR_ ?>data/users/<?= $this->profile->consultant->image ?>') "></div>
                    </div>
                    <div> <?= $this->profile->consultant->firstname . ' ' . $this->profile->consultant->lastname; ?></div>
                </div>
                <ul>
                    <?php if ($this->profile->consultant->email) { ?>
                    <li>
                        <a href="mailto:<?= $this->profile->consultant->email?>">
                            <i class="far fa-envelope"></i><?= $this->profile->consultant->email?>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($this->profile->consultant->tel) { ?>
                    <li>
                        <a href="tel:<?= $this->profile->consultant->tel ?>">
                            <i class="fas fa-mobile-alt"></i><?= $this->profile->consultant->tel ?>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($this->profile->consultant->linkedin) { ?>
                    <li>
                        <a target="_blank" href="<?= $this->profile->consultant->linkedin ?>>">
                            <i class="fab fa-linkedin"></i>Linkedin
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <h3>Is this candidate of interest?</h3>
                <p>If so, drop us a line by clicking one of the below options.</p>
                <button data-toggle="modal" onclick="load('request-cv/<?= $this->profile->id; ?>'); return false;" data-target="#reveal_modal" class="btn-yellow btn-full">Request Full Profile &amp; CV </button>
                <button data-toggle="modal" onclick="load('request-interview/<?= $this->profile->id; ?>'); return false;" data-target="#interview_modal" class="btn-yellow btn-full">Request Interview</button>
                <button data-toggle="modal" onclick="load('request-info/<?= $this->profile->id; ?>'); return false;" data-target="#info_modal" class="btn-yellow btn-full">Request Further Info</button>

                <h2>Not quite the right fit?</h2>
                <p>Submit your ideal criteria here and you'll automatically be alerted when we register a new candidate that fits the bill.</p>
                <a onclick="load('candidate-alert/<?= $this->profile->id; ?>'); return false;" data-toggle="modal" data-target="#candidate_alert" class="btn-yellow btn-full">Register Candidate Alert</a>
            </div>


            <div class="right-sec">
                <div class="top-sec">
                    <h3 class="title-small"><?= $this->profile->job_title ?></h3>
                    <div class="quote">
                        <?= ($this->profile->quote ? reFilter($this->profile->quote) : '...') ?>
                        <?php if (($this->profile->video_type === 'file') && $this->profile->video_file) { ?>
                            <video style="width: 100%; margin: 30px auto 0 auto" controls>
                                <source src="<?= _SITEDIR_ ?>data/talent/anonymous_profiles/<?= $this->profile->video_file ?>">
                                Your browser does not support the video tag.
                            </video>
                            <div class="clearfix"></div>
                        <?php } ?>
                        <?php if (($this->profile->video_type === 'youtube') && $this->profile->video_link) { ?>
                            <iframe style="width: 100%;height: 480px; margin: 30px auto 0 auto" frameborder="0"
                                    src="<?= $this->profile->video_link ?>">
                            </iframe>
                            <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="info-sec">
                    <h2 class="sub-title">Key Info</h2>
                    <ul class="info-table">
                        <li>
                            <div>Job Title</div>
                            <div>
                                <?= $this->profile->job_title ?>
                            </div>
                        </li>
                        <li>
                            <div>Location</div>
                            <div>
                                <?= implode(", ", array_map(function ($location) {
                                        return $location->location_name;
                                    }, $this->profile->locations )
                                ); ?>
                            </div>
                        </li>
                        <li>
                            <div>Education</div>
                            <div>
                                <?= $this->profile->education ?>
                            </div>
                        </li>
                        <li>
                            <div>Top 3 Skills</div>
                            <div>
                                <?= str_replace(',', ', ',  $this->profile->keywords) ?>
                            </div>
                        </li>
                        <li>
                            <div>Will relocate?</div>
                            <div>
                                <?= $this->profile->relocate ?>
                            </div>
                        </li>
                        <?php if ($this->profile->radius) { ?>
                            <li>
                                <div>Will commute</div>
                                <div>
                                    <?= $this->profile->radius . " " . $this->profile->distance_type; ?>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($this->profile->contract) { ?>
                            <li>
                                <div>Contract Preference</div>
                                <div>
                                    <?php switch ($this->profile->contract) {
                                        case 'permanent':
                                            echo 'Permanent';
                                            break;
                                        case 'contract':
                                            echo 'Contract';
                                            break;
                                        case 'both':
                                            echo 'Contract or Permanent';
                                            break;
                                    }; ?>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($this->profile->availability) { ?>
                            <li>
                                <div>Availability (Notice Period)</div>
                                <div>
                                    <?= $this->profile->availability; ?>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($this->profile->min_annual_salary) { ?>
                            <li>
                                <div>Min Salary Req. (Perm Roles)</div>
                                <div>
                                    <?= numberFormatInStr($this->profile->annual_currency . $this->profile->min_annual_salary); ?>
                                    per annum
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($this->profile->min_daily_salary) { ?>
                            <li>
                                <div>Min Day Rate Req. (Contract Roles)</div>
                                <div>
                                    <?= numberFormatInStr($this->profile->daily_currency . $this->profile->min_daily_salary); ?>
                                    per day
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($this->profile->min_hourly_salary) { ?>
                            <li>
                                <div>Min Hour Rate Req. (Contract Roles)</div>
                                <div>
                                    <?= numberFormatInStr($this->profile->hourly_currency . $this->profile->min_hourly_salary); ?>
                                    per hour
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ( $this->profile->languages) { ?>
                            <li>
                                <div>Languages Spoken</div>
                                <div>
                                    <?= implode(", ", array_map(function ($language) {
                                        return $language->language_name;
                                    },  $this->profile->languages)) ?>
                                </div>
                            </li>
                        <?php } ?>
                        </li>
                    </ul>
                </div>
                <div class="key-skill-sec">
                    <h2 class="sub-title">Key Skills + Experience</h2>
                    <div class="content-scroll">
                        <?= reFilter($this->profile->skills); ?>
                    </div>
                </div>
                <div class="interest-sec">
                    <h2 class="sub-title">Is this candidate of interest?</h2>
                    <p>If so, drop us a line by clicking one of the below options.</p>
                    <div class="interest-sec-flex">
                        <button data-toggle="modal" onclick="load('request-cv/<?= $this->profile->id; ?>'); return false;" data-target="#reveal_modal" class="btn-yellow btn-inline">Request Full Profile &amp; CV </button>
                        <button data-toggle="modal" onclick="load('request-interview/<?= $this->profile->id; ?>'); return false;" data-target="#interview_modal" class="btn-yellow btn-inline">Request Interview </button>
                        <button data-toggle="modal" onclick="load('request-info/<?= $this->profile->id; ?>'); return false;" data-target="#info_modal" class="btn-yellow btn-inline">Request Further Info </button>
                    </div>
                </div>
                <div class="quite-sec">
                    <h2 class="sub-title">Not quite the right fit?</h2>
                    <p>Submit your ideal criteria here and you'll automatically be alerted when we register a new candidate that fits the bill.</p>
                    <a onclick="load('candidate-alert/<?= $this->profile->id; ?>'); return false;" data-toggle="modal" data-target="#candidate_alert" class="btn-yellow btn-inline">Register Candidate Alert</a>
                </div>
                <div class="terms-sec">
                    <div class="content-scroll">
                        <i>
                            All candidate introductions, whether written, via e-mail or oral, are strictly subject
                            to
                            <b><?= SITE_NAME ?></b> Terms of Business (unless agreed
                            otherwise).
                            For a copy of our Terms of
                            Business please reply to this email requesting our Terms of Business. By interviewing or
                            corresponding in any way with a candidate put forward by
                            <b><?= SITE_NAME ?></b> (whether introduced in
                            writing, or orally) you are accepting
                            <b><?= SITE_NAME ?></b>
                            Terms
                            of Business. No variation of these
                            terms are valid unless confirmed in writing, prior to the first candidate interview, by
                            an
                            authorised signatory of <b><?= SITE_NAME ?></b>. <a target="_blank"
                                                                        href="<?= _SITEDIR_ ?>data/talent/your_tc/<?= $this->tc->file ?>">Please
                                click here to download our standard
                                T's and C's.</a>
                        </i>
                    </div>
                </div>

                <br>
                <a href="{URL:talent/anonymous_profile}" class="btn-yellow btn-inline">‹ Back to Talent Search</a>
            </div>
        </div>
    </div>
</div>
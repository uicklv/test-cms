<?php if (is_array($this->candidates) && count($this->candidates) > 0) { ?>
    <?php foreach ($this->candidates as $item) { ?>
        <div class="specialty-block">
            <div class="sb-title">
                <?php
                if ($item->c_status == '1')
                    echo '<span id="span_status_' . $item->id . '" class="sn-status sn-new"></span>';
                elseif ($item->c_status == '2')
                    echo '<span id="span_status_' . $item->id . '" class="sn-status sn-process"></span>';
                elseif ($item->c_status == '3')
                    echo '<span id="span_status_' . $item->id . '" class="sn-status sn-rejected"></span>';
                ?>
                <?= $item->firstname . ' ' . $item->lastname ?>
            </div>

            <div class="sb-sub-block">
                <div class="sb-info">
                    <div>
                        <h4 class="sb-info-title">Name</h4>
                        <?= $item->firstname . ' ' . $item->lastname ?>
                    </div>
                    <div>
                        <h4 class="sb-info-title">Status</h4>
                        <div id="status_<?= $item->id ?>">
                            <?php
                            if ($item->c_status == '1')
                                echo 'New';
                            elseif ($item->c_status == '2')
                                echo 'In Process';
                            elseif ($item->c_status == '3')
                                echo 'Rejected';
                            ?>
                        </div>
                    </div>

                    <?php if ($item->location) { ?>
                        <div>
                            <h4 class="sb-info-title">Location</h4>
                            <?= $item->location ?>
                        </div>
                    <?php } ?>
                    <?php if ($item->notice_period) { ?>
                        <div>
                            <h4 class="sb-info-title">Notice Period</h4>
                            <?= $item->notice_period ?>
                        </div>
                    <?php } ?>
                    <?php if ($item->salary) { ?>
                        <?php if (User::get('access') != 'limited') { ?>
                            <div>
                                <h4 class="sb-info-title">Salary Expectations</h4>
                                <?= $item->salary ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($item->cv) { ?>
                        <div>
                            <h4 class="sb-info-title">CV Download</h4>
                            <a class="sb-download" href="<?= _SITEDIR_ ?>data/candidates/<?= $item->cv ?>"
                               download="<?= $item->firstname . '-' . $item->lastname . '.' . File::format($item->cv) ?>"><span
                                        class="icon-download"></span></a>
                        </div>
                        <div>
                            <h4 class="sb-info-title">Share CV</h4>
                            <a onclick="load('portal/share_cv/<?= $item->id ?>'); return false;" style="cursor: pointer;"><i
                                        class="fas fa-share-alt"></i></a>
                        </div>
                    <?php } ?>
                    <?php if (User::get('access') != 'limited') { ?>
                        <div>
                            <div class="sb-social">
                                <?php if ($item->linkedin) { ?>
                                    <a href="<?= $item->linkedin ?>" target="_blank"><span class="icon-linkedin1"></span></a>
                                <?php } ?>
                                <?php if ($item->git_hub) { ?>
                                    <a href="<?= $item->git_hub ?>" target="_blank"><span class="icon-github"></span></a>
                                <?php } ?>
                                <?php if ($item->stack_overflow) { ?>
                                    <a href="<?= $item->stack_overflow ?>" target="_blank"><span class="icon-stackoverflow"></span></a>
                                <?php } ?>
                                <?php if ($item->site) { ?>
                                    <a href="<?= $item->site ?>" target="_blank"><span class="icon-globe"></span></a>
                                <?php } ?>
                                <?php if ($item->link1) { ?>
                                    <a href="<?= $item->link1 ?>" target="_blank"><i class="fas fa-link"></i></a>
                                <?php } ?>
                                <?php if ($item->link2) { ?>
                                    <a href="<?= $item->link2 ?>" target="_blank"><i class="fas fa-link"></i></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="sb-text">
                    <?= reFilter($item->description) ?>
                </div>

                <ul class="sb-list">
                    <li>
                        <div class="sb-list-title">Ask a Question</div>
                        <form class="sb-form mar" id="question_form_<?= $item->id ?>">
                            <textarea class="sb-textarea" name="question" placeholder="What would you like to ask?"></textarea>
                            <div class="sb-row-sub">
                                <input class="sb-sub" onclick="load('portal/question/<?= $item->id ?>', 'form:#question_form_<?= $item->id ?>', 'vacancy=<?= $this->job->title ?>', 'vacancy_id=<?= $this->job->id ?>'); return false;" type="submit" value="Send">
                            </div>
                        </form>
                    </li>
                    <li>
                        <div class="sb-list-title">Arrange Interview</div>
                        <form class="sb-form" id="interview_form_<?= $item->id ?>">
                            <p>Type</p>
                            <div class="sb-row-flex">
                                <label class="sb-check">
                                    <input type="checkbox" name="type[]" value="Video Interview">
                                    <span class="sb-check-title">Video Interview</span>
                                </label>
                                <label class="sb-check">
                                    <input type="checkbox" name="type[]" value="Phone Interview">
                                    <span class="sb-check-title">Phone Interview</span>
                                </label>
                                <label class="sb-check">
                                    <input type="checkbox" name="type[]" value="Face to Face Interview">
                                    <span class="sb-check-title">Face to Face Interview</span>
                                </label>
                                <label class="sb-check">
                                    <input type="checkbox" name="type[]" value="Informal Coffee">
                                    <span class="sb-check-title">Informal Coffee</span>
                                </label>
                            </div>
                            <div class="sb-row-flex-nowrap">
                                <label>Your Availability</label>
                                <input class="sb-text-field" name="availability" type="text">
                            </div>
                            <p>What did you like about this candidate?</p>
                            <textarea class="sb-textarea" name="message" placeholder="Your Message"></textarea>
                            <div class="sb-row-sub">
                                <input class="sb-sub" onclick="load('portal/interview/<?= $item->id ?>', 'form:#interview_form_<?= $item->id ?>', 'vacancy=<?= $this->job->title ?>', 'vacancy_id=<?= $this->job->id ?>'); return false;" type="submit" value="Send">
                            </div>
                        </form>
                    </li>
                    <li>
                        <div class="sb-list-title">Arrange a Technical Challenge</div>
                        <form class="sb-form" id="challenge_form_<?= $item->id ?>">
                            <p>Is there any information I need to know about this that we haven’t already covered?</p>
                            <textarea class="sb-textarea" name="message" placeholder="Your Message"></textarea>
                            <div class="sb-row-sub">
                                <input class="sb-sub" onclick="load('portal/challenge/<?= $item->id ?>', 'form:#challenge_form_<?= $item->id ?>', 'vacancy=<?= $this->job->title ?>', 'vacancy_id=<?= $this->job->id ?>'); return false;" type="submit" value="Send">
                            </div>
                        </form>
                    </li>

                    <?php if ($item->c_status == '3') { ?>
                        <li>
                            <div class="sb-list-title" onclick="load('portal/change_status/<?= $item->id ?>', 'vacancy_id=<?= $this->job->id ?>'); return false;">Return to process</div>
                            <?php /*<a class="portal-link" onclick="load('page/change_status/<?= $item->id ?>'); return false;" style="cursor: pointer;">Return to process</a>*/ ?>
                        </li>
                    <?php } else { ?>
                        <li>
                            <div class="sb-list-title" id="reject_button_<?= $item->id ?>">Reject Candidate</div>
                            <form class="sb-form" id="reject_form_<?= $item->id ?>">
                                <p>Don’t give a candidate a reason to feel bad about your company, even if they are rejected.</p>
                                <p>Give them some constructive feedback on what was lacking during the process, it improves your reputation and this candidate may be right for you in the future.</p>
                                <textarea class="sb-textarea" name="message" placeholder="Your Message"></textarea>
                                <div class="sb-row-sub">
                                    <input class="sb-sub" onclick="load('portal/reject/<?= $item->id ?>', 'form:#reject_form_<?= $item->id ?>', 'vacancy=<?= $this->job->title ?>', 'vacancy_id=<?= $this->job->id ?>'); return false;" type="submit" value="Send">
                                </div>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <h3 class="section-title"> The list of candidates is empty</h3>
<?php } ?>


<script>
    $(".sb-title").click(function () {
        $(this).toggleClass('active')
        $(this).parent().find('.sb-sub-block').slideToggle(300);
    });

    $(".sb-list-title").click(function () {
        $(this).toggleClass('active');
        $(this).parent().find('.sb-form').slideToggle(300);
    });
    function changeStatus(id) {
        $('#span_status_' + id).removeClass('sn-new sn-process');
        $('#span_status_' + id).addClass('sn-rejected');
    }
</script>


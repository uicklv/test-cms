<?php /*
<div class="main">
    <div class="main-cont">
        <div class="general-block">
            <!-- Menu -->
            <div class="gb-btn-block">
                <a href="{URL:project}" class="gb-btn active">Project</a>
                <a href="{URL:team}" class="gb-btn">Team</a>
            </div>

            <!-- Project title -->
            <div class="gb-title-row">
                <div>Project</div>
                <ul class="gi-info">
                    <li>List</li>
                    <li>Task</li>
                </ul>
            </div>
        </div>


        <div class="scroll-gb">
            <div class="general-block">
                <?php
                $aosDelay = 50;
                foreach ($this->list as $item) {
                    $aosDelay += 50;
                    ?>
                    <div id="proj_<?= to32($item->project_id ); ?>" class="gb-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="<?= $aosDelay; ?>">
                        <a class="gi-left" href="{URL:p/<?= to32($item->project_id ); ?>}">
                            <div class="gi-pic bg_<?= mb_strtolower($item->name[0]); ?>"><?= mb_strtoupper($item->name[0]); ?></div>
                            <?= $item->name; ?>
                        </a>
                        <div class="gi-right">
                            <ul class="gi-info">
                                <li title="Lists"><span><?= $item->lists; ?></span></li>
                                <li title="Tasks"><span><?= $item->tasks; ?></span></li>
                            </ul>
                            <div class="performer-block">
                                <?php foreach ($item->team as $it) { ?>
                                    <div class="pb-pic"><img src="<?= getAvatar($it->user_id); ?>" alt="" title="<?= $it->name;?>"/></div>
                                <?php } ?>
                            </div>
                            <div class="gi-control-block">
                                <div class="gi-control">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <div class="cs-option-block">
                                    <?php if (projectRole($item->role, 20)) { ?>
                                        <a class="cs-option invite_user" onclick="load('project/invite_project/<?= to32($item->project_id ); ?>');">
                                            <span class="icon-invite"></span> Invite user
                                        </a>
                                    <?php } ?>
                                    <?php if (projectRole($item->role, 30)) { ?>
                                        <a class="cs-option project_settings" onclick="load('project/project_settings/<?= to32($item->project_id ); ?>');">
                                            <span class="icon-settings"></span> Project settings
                                        </a>
                                        <a class="cs-option remove_project" onclick="load('project/remove_project/<?= to32($item->project_id ); ?>');">
                                            <span class="icon-remove pr_3"></span> Remove project
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if (!$this->list) { ?>
                    <div class="center MOD_text_box mt16">Create you first project</div>
                <?php } ?>

                <script>
                    // Project options
                    $(document).on('click', '.gi-control', function(e) {
                        if ($(this).parent().parent().parent().hasClass('active')) {
                            // hide
                            $('.gb-item.active .cs-option-block').slideToggle();
                            $(".gb-item").removeClass('active');
                            console.log('has active');
                        } else {
                            // hide
                            $('.gb-item.active .cs-option-block').slideToggle();
                            $(".gb-item").removeClass('active');
                            // show
                            $(this).parent().parent().parent().toggleClass('active');
                            $(this).parent().find('.cs-option-block').slideToggle();
                        }
                        e.stopPropagation();
                    });

                    // Close project options
                    $(document).on('click', function(e) {
                        if (!$(e.target).closest(".cs-option-block").length) {
                            if ($(".gb-item").hasClass('active')) {
                                $('.gb-item.active .cs-option-block').slideToggle();
                                $(".gb-item").removeClass('active');
                                e.stopPropagation();
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>
*/ ?>

<div class="main-table">
    <div class="main-table">
        <div class="center MOD_text_box mt16">Today still no notifications for you</div>
    </div>
</div>

<section class="
head-inner head-inner_video">
    <div class="fullscreen-bg element-with-video-bg jquery-background-video-wrapper">
        <video id="myVideo" class="fullscreen-bg__video my-background-video jquery-background-video" autoplay loop muted playsinline poster="<?= _SITEDIR_ ?>public/images/head_bg.jpg">
            <source src="<?= _SITEDIR_ ?>public/images/xpertise-overlay.mp4" type="video/mp4"/>
        </video>
    </div>
    <div class="fixed">
        <div class="head-cont">

            <style>
                .w-dropdown-list a {
                    cursor: pointer;
                }
                .w-dropdown {
                    width: 100%;
                }

                .suggests_wrap {
                    position: relative;
                    z-index: 9999;
                }

                .suggests_result {
                    position: absolute;
                    top: 60px;
                    left: 0;
                    right: 0;
                    background-color: white;
                    width: 100%;
                    min-height: 0;
                    max-height: 300px;
                    /*margin-top: -10px;*/
                    border: 1px solid #2FAADF;
                    border-radius: 20px;
                    box-sizing: border-box;
                    overflow-y: auto;
                    z-index: 99999;
                }

                .suggests_result:empty {
                    display: none;
                }

                .suggests_result .pc-item {
                    padding: 0 20px;
                    line-height: 60px;
                    font-size: 20px;
                }

                .suggests_result .pc-item:hover {
                    background-color: #2FAADF;
                    color: white;
                    cursor: pointer;
                }

                .hide {
                    display: none;
                }
            </style>
            <script>
                function fillPostcode(el) {
                    var code = trim($(el).text());
                    $('#postcode').val(code);
                    $('.suggests_result').html('');
                }

                function closeSuggest() {
                    $('.suggests_result').html('');
                }

                function suggestPostcode(el) {
                    if (trim($(el).val())) {
                        load('jobs/postcode', 'postcode#postcode');
                        console.log('suggestPostcode');
                    }
                }
            </script>

            <h3 class="gen-title mar">Saved jobs</h3>
        </div>
    </div>
    <a class="send-CV pointer" onclick="load('about_us/apply_now');">Send us your CV</a>
</section>
<div class="fon_2">
    <div class="fixed">
        <div class="job-filter">
            <div class="jf-right">
                <div class="flex-end flex-end_mb">
                    <div class="jf-select mar">
                        <select id="orderby" name="orderby" class="select">
                            <option value="">Order By</option>
                            <option value="DESC">Salary High to Low</option>
                            <option value="ASC">Salary Low to High</option>
                            <option value="location">Location</option>
                            <option value="feature">Featured Jobs</option>
                            <option value="time"> Date Posted</option>
                        </select>
                    </div>
                </div>

                <div id="search_results_box">
                    <?php
                    if ($this->list) foreach ($this->list as $job) {
                        ?>
                        <div class="job-item">
                            <div id="add_<?= $job->id ?>" class="ji-saved">
                                <?php
                                if (!User::get('id')) { ?>
                                    <i class="fas fa-heart"  style="font-size: 20px; cursor:pointer;" onclick="load('page/login')"></i>
                                <?php } else {
                                    if (!$job->saved){ ?>
                                        <i class="fas fa-heart"  style="font-size: 20px; cursor:pointer;" onclick="load('jobs/add_favorite', 'user=<?= User::get("id") ?>' , 'job=<?= $job->id ?>');"></i>
                                        <?php
                                    } else { ?>
                                        <i class="fas fa-heart"  style="font-size: 20px; cursor:pointer;color: red;" onclick="load('jobs/add_favorite', 'user=<?= User::get("id") ?>' , 'job=<?= $job->id ?>');"></i>
                                    <?php }
                                }
                                ?>
                            </div>
                            <a class="ji-link" href="{URL:job/<?=$job->slug?>}"></a>
                            <a class="ji-apply ji-apply-blue pointer" onclick="load('jobs/apply_now/<?= $this->job->slug; ?>');">Apply Now</a>
                            <h3 class="ji-title">
                                <?= $job->title ?>
                            </h3>
                            <div class="ji-salary">
                                <?php
                                if ($job->salary_value == 1)
                                    echo '£0-£10,000';
                                if ($job->salary_value == 10000)
                                    echo '£10,000-£20,000';
                                if ($job->salary_value == 20000)
                                    echo '£20,000-£30,000';
                                if ($job->salary_value == 30000)
                                    echo '£30,000-£40,000';
                                if ($job->salary_value == 40000)
                                    echo '£40,000-£50,000';
                                ?>
                            </div>
                            <div class="ji-flex">
                                <div class="ji-row"><span><i class="fas fa-map-marker-alt"></i></span><?= $job->locations[0]->location_name ?></div>
                                <div class="ji-row"><span><i class="far fa-hourglass"></i></span><?= str_replace('_', ' ', $job->contract_type) ?></div>
                                <div class="ji-row"><span>#</span><?= $job->sectors[0]->sector_name ?></div>
                            </div>
                            <div><?= reFilter($job->content_short)?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="load_here" class="touch-block">
    <script>load('about_us/touch_us');</script>
</div>

<script>
    $(".select").selectmenu({
        change: function (event, data) {
            let value = $("#orderby").val();
            load('jobs/search',  'orderby=' + value);
        }
    });
</script>
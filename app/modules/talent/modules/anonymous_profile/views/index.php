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
        top: 42px;
        left: 0;
        right: 0;
        background-color: white;
        width: 100%;
        min-height: 0;
        max-height: 300px;
        /*margin-top: -10px;*/
        border: 1px solid #2FAADF;
        border-radius: 10px;
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
            load('find-postcode', 'postcode#postcode');
        }
    }
</script>
<section class="head-block mar head-block-small">
    <div class="fixed">
        <div class="head-cont">
            <div class="gen-title"><span>Talent Search</span></div>
            <br>
            <div class="gen-title-name">Browse and search some of our active candidates.</div>
        </div>
    </div>
</section>

<!-- SEARCH -->
<div class="skill-sec" data-select2-id="5">
    <div class="fixed" data-select2-id="4">

        <div class="skill-inner">
            <form onsubmit="return search();" id="search_form">
                <ul>

                    <li>
                        <h3>
                        Skills / Keywords
                        <i class="fa fa-info-circle form-tooltip"
                           title='To search for a specific phrase please use speech marks. E.G "PHP Developer"'></i>
                        </h3>
                        <div class="si-text-field">
                            <input type="text" id="keywords" name="keywords">
                        </div>
                    </li>
                    <li>
                        <h3>
                            Postcode/Zip
                            <i class="fa fa-info-circle form-tooltip"
                               title="Please start typing a postcode and select from the dropdown list"
                               aria-hidden="true"></i><span class="sr-only">Please start typing a postcode and select from the dropdown list</span>
                        </h3>
                        <div class="si-text-field">
                            <input type="text" name="postcode" id="postcode" onkeyup="suggestPostcode(this);">
                            <div class="suggests_result"></div>
                        </div>
                    </li>
                    <li class="form-advanced" style="display: none;">
                        <h3>
                           Radius
                        </h3>
                        <select id="radius" name="radius" class="select">
                            <option value=""></option>
                            <option value="5">5 miles</option>
                            <option value="10">10 miles</option>
                            <option value="20">20 miles</option>
                            <option value="30">30 miles</option>
                            <option value="50">50 miles</option>
                        </select>
                    </li>
                    <li class="form-advanced" style="display: none;">
                        <h3>
                            Languages Spoken
                        </h3>
                        <select id="languages-spoken" name="language" class="select">
                            <option value=""></option>
                            <?php if (is_array($this->languages) && count($this->languages) > 0) { ?>
                                <?php foreach ($this->languages as $item) { ?>
                                    <option value="<?= $item->id ?>"><?= $item->name?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </li>
                    <li class="form-advanced" style="display: none;">
                        <h3>
                            Maximum Salary / Day Rate <span>($)</span>
                            <i class="fa fa-info-circle form-tooltip"
                               title="Please type in the maximum Salary or Day Rate you are willing to pay to the nearest $. Eg - simply type 50000 if you are willing to pay up to $ 50000 per annum."
                               aria-hidden="true"></i><span class="sr-only">Please type in the maximum Salary or Day Rate you are willing to pay to the nearest $. Eg - simply type 50000 if you are willing to pay up to $ 50000 per annum.</span>
                        </h3>
                        <div class="si-text-field">
                            <input type="number" pattern="^[0-9]*$" class="search-input" name="salary" id="salary">
                        </div>

                    </li>
                    <li class="form-advanced" style="display: none;">
                        <h3>&nbsp;

                        </h3>
                        <select id="salary_term" tabindex="-1" name="salary_term" class="select">
                            <option value="annum">Per Annum</option>
                            <option value="day">Per Day</option>
                            <option value="hour">Per Hour</option>
                        </select>
                    </li>
                    <li>
                        <button type="submit"  class="btn-yellow" onclick="load('search-anon-profile', 'form:#search_form'); return false;">search candidates</button>
                        <a type="reset" href="{URL:talent/anonymous_profile}" class="btn-yellow">reset search</a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </form>
        </div>
        <a href="#" onclick="advanced_search_form();" class="advance-link" id="advanced-search">advanced search</a>
    </div>
</div>

<!-- RESULTS -->
<div class="develop-sec">
    <div class="fixed">
        <div class="develop-cont">
            <ul id="anonymous-profiles">
                <?php if (is_array($this->list) && count($this->list) > 0) { ?>
                    <?php foreach ($this->list as $item) { ?>
                        <li>
                            <div class="box-sec">
                                <h2 class="title-small"><?= $item->job_title ?></h2>
                                <ul>
                                    <li>
                                        <p class="bs-title">Salary / Rate Required:</p>
                                        <p class="bs-title-cont"><?= SalaryJoin($item->annual_currency, $item->min_annual_salary,
                                                $item->daily_currency, $item->min_daily_salary, $item->hourly_currency, $item->min_hourly_salary) ?></p>
                                    </li>
                                    <li>
                                        <p class="bs-title">Skills:</p>
                                        <p class="bs-title-cont"><?= str_replace(',', ', ', $item->keywords) ?></p>
                                    </li>
                                    <li>
                                        <p class="bs-title">Current Location:</p>
                                        <p class="bs-title-cont">
                                            <?= implode(", ", array_map(function ($location) {
                                                    return $location->location_name;
                                                }, $item->locations )
                                            ); ?>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="bs-title">Contract Preference:</p>
                                        <p class="bs-title-cont"><?= ucfirst($item->contract) ?></p>
                                    </li>
                                </ul>

                                <div class="box-bottom-line">
                                    <div class="bs-flex">
                                        <h2 class="bs-sub-head">Represented by<br><?= $item->consultant->firstname . ' ' .  $item->consultant->lastname?></h2>
                                        <div class="bs-pic">
                                            <div style="background-image: url(<?= _SITEDIR_ ?>data/users/<?= $item->consultant->image ?>)"></div>
                                        </div>
                                    </div>
                                    <a href="{URL:talent/anonymous_profile/<?= $item->id ?>}" class="btn-center btn-yellow">View Candidate</a>
                                </div>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<!-- INFO -->
<div class="candidate-sec">
    <div class="container">
        <div class="candidate-cont">
            <div class="row">
                <div class="col-md-6 col-12">
                    <h1 class="sub-head">Are you a hiring manager<br> looking for that perfect candidate?</h1>
                    <br>
                    <p>Then browse and search to view our active candidates.</p>
                </div>
                <div class="col-md-6 col-12">
                    <div class="cc-flex">
                        <h1 class="sub-head">Can't find that  perfect candidate?</h1>
                        <a href="#" data-toggle="modal"  onclick="load('candidate-alert'); return false;" class="btn-yellow btn-inline">register candidate alert</a>
                    </div>
                    <p>Simply sign up for "candidate alerts" here, and as soon as we find your ideal candidate profile, you'll immediately be alerted!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function advanced_search_form() {
        if ($('#advanced-search').text() == 'advanced search')
            $('#advanced-search').text('simple search');
        else
            $('#advanced-search').text('advanced search');
        $('.form-advanced').toggle();
    }
</script>

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
        top: 95px;
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
            load('panel/talents/open_profiles/postcode', 'postcode#postcode');
            console.log('suggestPostcode');
        }
    }
</script>

<span class="close-popup" onclick="closePopup();"><span class="icon-close" onclick="closePopup();"></span></span>

<h3 class="title-popup">Register Candidate Alert</h3>
<form id="apply_form" class="popup-form">
    <div class="pf-flex">
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Your Name</label>
                <input class="pf-text-field" type="text" name="name">
            </div>
            <div class="pf-row">
                <label class="pf-label">Company Name</label>
                <input class="pf-text-field" type="text" name="company_name">
            </div>
            <div class="pf-row" style="position: relative;">
                <label class="pf-label">Postcode/Zip</label>
                <input class="pf-text-field" type="text" name="postcode" id="postcode" onkeyup="suggestPostcode(this);" placeholder="Please start typing a postcode">
                <div class="suggests_result"></div>
            </div>
            <div class="pf-row">
                <select id="salary_term" tabindex="-1" name="salary_term" class="select">
                    <option value="annum">Per Annum</option>
                    <option value="day">Per Day</option>
                </select>
            </div>
        </div>
        <div class="pf-column">
            <div class="pf-row">
                <label class="pf-label">Email</label>
                <input class="pf-text-field" type="text" name="email" placeholder="Type your email">
            </div>
            <div class="pf-row">
                <label class="pf-label">Skill / Keywords</label>
                <input class="pf-text-field" type="text" name="skills_keywords" placeholder="Skill 1, Skill 2, ...">
            </div>
            <div class="pf-row">
                <label class="pf-label">Max Salary / Day Rate Required</label>
                <input class="pf-text-field" type="number" name="max_salary">
            </div>
        </div>
    </div>

    <label class="checkBox">
        <input type="checkbox" name="check" value="yes">
        <span class="check-title">I consent for The Talent Vault to contact me with further info regarding this candidate</span>
    </label>

    <button class="btn-yellow" type="submit" onclick="load('candidate-alert/<?= $this->profile->id; ?>', 'form:#apply_form'); return false;">SUBMIT</button>
</form>

<script>
    $("#site").addClass('popup-open');
</script>
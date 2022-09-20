
<div class="result-outer">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="top--info">
                    <p>The average base salary for your job role in <strong><span class="prominent pink"><?= $this->location->name; ?></span> is <span class="prominent pink">£<?= $this->average_salary; ?></span></strong></p>
                </div>
                <div class="top--info"><p>The MAX base salary is <span class="prominent pink"><strong>£<?= $this->maximum_salary; ?></strong></span></p>
                </div>
                <div class="top--info"><p>The MIN base salary is <span class="prominent pink"><strong>£<?= $this->minimum_salary; ?></strong></span></p>
                </div>

                <div class="top--info ">
                    <p>Please note, these results are based on our industry data and are only a guide. If you would like to discuss your salary expectations or anything else about your job search, speak to one of our experienced consultants:</p>
                    <div class="meet-team-center-btn">
                        <a href="{URL:team}" class="view-btn wow fadeInUp">Meet The Team</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

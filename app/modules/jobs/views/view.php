<style>
    .job-detail-content-cell span, .job-detail-content-cell p {
        color: white !important;
        font-size: inherit !important;
        font-family: inherit !important;
        background-color: inherit !important;
    }

    .job-detail-content-cell ul, .job-detail-content-cell li {
        font-size: inherit !important;
        font-family: inherit !important;
        background-color: inherit !important;
    }

    .job-detail-content-cell ul {
        padding-inline-start: 48px;
        list-style: disc;
    }

    .job-detail-content-cell strong {
        font-weight: bold;
    }

    .mod_h > h3 {
        margin: 28px 0 12px 0;
        font: 26px/140% 'BigCityGrotesquePro', sans-serif;
        color: #64C2C8;
    }
</style>

<div class="fixed">
    <div class="job-article">
        <div class="ja-cont">
            <h3 class="title"><?= $this->job->title; ?></h3>

            <div class="ja-data">
                <div><span>Location:</span><?= propertiesToString($this->job->locations) ?></div>
                <div><span>Date Posted:</span> <?= date("d/m/Y", $this->job->time); ?></div>
                <div><span>Salary:</span> <?= $this->job->salary_value; ?></div>
                <div><span>Expires:</span> <?= date("d/m/Y", $this->job->time_expire); ?></div>
                <div><span>Sector:</span> <?= propertiesToString($this->job->sectors) ?></div>
                <div><span>Role Type:</span> <?= ucfirst($this->job->contract_type); ?></div>
                <?php if ($this->job->package) { ?>
                    <div><span>Package:</span> <?= $this->job->package; ?></div>
                    <div><span></span></div>
                <?php } ?>
            </div>

            <div class="mod_h">
                <?php echo HTMLPurifier::clearTags(reFilter($this->job->content), true); ?>
            </div>


            <div class="ja-btn-block">
                <a class="btn-yellow" onclick="load('jobs/apply_now/<?= $this->job->slug; ?>');">Apply now</a>
                <a class="btn-blue" href="{URL:jobs}">Back to job lists</a>
            </div>

            <div class="ja-share">
                Share this job
                <div class="social-block">
                    <a href="https://www.linkedin.com/shareArticle?url=<?= SITE_URL ?>jobs/view/<?=$this->job->slug?>" target="_blank"><span class="icon-LinkedIn"></span></a>
                    <a href="https://twitter.com/share?url=<?= SITE_URL ?>jobs/view/<?=$this->job->slug?>" target="_blank"><span class="icon-Twitter"></span></a>
                    <a href="mailto:?&subject=&body=<?= SITE_URL ?>jobs/view/<?=$this->job->slug?>"><span class="icon-mail"></span></a>
                </div>
            </div>
        </div>

        <div class="ja-card">
            <div class="jac-pic"><img src="<?php echo _SITEDIR_; ?>data/users/<?= $this->consultant->image; ?>" height="283" width="307" alt=""/></div>
            <div class="jac-cont">
                <div class="jac-name"><span><?= $this->consultant->firstname . ' ' . $this->consultant->lastname; ?></span> <?php echo implode("<br>", array_map(function ($location) {
                        return $location->sector_name;
                    }, $this->job->sectors)); ?></div>
                <ul class="jac-link">
                    <li><a href="tel:<?= $this->consultant->tel; ?>"><span><?= $this->consultant->tel; ?></span></a></li>
                    <li><a href="mailto:<?= $this->consultant->email; ?>"><b>Email me here</b></a></li>
                </ul>
                <a class="btn-yellow" onclick="load('jobs/apply_now/<?= $this->job->slug; ?>');">Apply now</a>
                <a class="btn-yellow" onclick="load('jobs/apply_linkedin/<?= $this->job->slug; ?>');">Apply via Linkedin</a>
            </div>
        </div>
    </div>
</div>

<?php
$unixtime = $this->job->time;
$time = date("Y-m-d", $unixtime);

$ex_unixtime = $this->job->time_expire;
$ex_time = date("Y-m-d", $ex_unixtime);

$salary = str_replace('&pound;', '£', $this->job->salary_value);
?>

<script type="application/ld+json">
    {
      "@context" : "https://schema.org/",
      "@type" : "JobPosting",
      "title" : "<?= $this->job->title; ?>",
      "description" : "<?= htmlspecialchars_decode($this->job->content); ?>",
      "identifier": {
        "@type": "PropertyValue",
        "name": "<?= $this->job->title; ?>",
        "value": "<?= $this->job->ref; ?>"
      },
      "datePosted" : "<?= $time; ?>",
      "validThrough" : "<?= $ex_time; ?>",
      "employmentType": "<?= $this->job->contract_type; ?>",
      "hiringOrganization" : {
        "@type" : "Organization",
        "name" : "FRUITION",
        "sameAs" : "https://www.fruitionit.co.uk/",
        "logo" : "https://www.fruitionit.co.uk/app/public/images/fruition-logo.png"
      },
      "baseSalary": {
        "@type": "MonetaryAmount",
        "currency": "£",
        "value": {
          "@type": "QuantitativeValue",
          "value": "<?= htmlspecialchars_decode($salary); ?>"
        }
      },
      "jobLocation": {
        "@type": "Place",
        "name": "<?= $this->job->locations[0]->location_name ?>",
        "address": "United Kingdom, 2nd Floor, 1 York Place, Leeds LS1 2DR"
        }
    }
</script>
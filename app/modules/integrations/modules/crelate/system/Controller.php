<?php

class CrelateController extends Controller
{
    use Validator;

    public function indexAction()
    {
        header("Content-type:text/plain");

        //$json = get_contents('https://app.crelate.com/api/pub/v1/jobs/recent/?api_key=xzfjzco5kcgwmpy5jw88o4fkdw&modified=31104000&take=500&skip=0'); // 2592000
        $json = get_contents('https://app.crelate.com/api/pub/v1/jobPostings/?api_key=xzfjzco5kcgwmpy5jw88o4fkdw&take=500&skip=0'); // 2592000
        $json = json_decode($json);

        if ($json) {
//            Model::query("truncate `vacancies`");

//            $del = $db->prepare("truncate `vacancies`");
//            $del->execute();
//            $del = $del->fetch(PDO::FETCH_ASSOC);
        }


        //todo only dev mode!!!
        File::write(File::mkdir('data/integrations/crelate/') . 'data_' . date('Y_m_d_h_i') . '.txt', json_encode($json));

        $i = 0;
        foreach ($json->Results as $job) {
            print_data($job);

            $jobName = $job->Title;
            if (!$jobName)
                continue;

            // Check job exist
            $checkJob = Model::fetch(Model::select('vacancies', "`job_code` = '" . $job->JobCode . "' LIMIT 1"));
            if ($checkJob)
                continue;

            echo ++$i;
            echo " Job " . $job->JobCode . ", \n" . $jobName . "\n---\n";

            if ($job->City) {
                // Check location exist
                $checkLoc = Model::fetch(Model::select('locations', "`name` = '" . $job->City . "' LIMIT 1"));

                if ($checkLoc) {
                    $location_id = $checkLoc->id;
                } else {
                    Model::insert('locations', ['name' => $job->City]); // Insert row
                    $location_id = Model::insertID();
                }
            }

            if ($job->Tags->Default) {
                $tagsArray = array();
                foreach ($job->Tags->Default as $tag) {
                    $tag = trim($tag);
                    if ($tag == 'Other Area(s)') continue;
                    if ($tag == 'Jobs at Full Spectrum!') continue;
                    $tagsArray[$tag] = $tag;
                }

                if ($tagsArray) {
                    foreach ($tagsArray as $tag) {
                        // Check sector exist
                        $checkTag = Model::fetch(Model::select('sectors', "`name` = '" . $tag . "' LIMIT 1"));

                        // Create sector if not exist
                        if (!$checkTag) {
                            Model::insert('sectors', [
                                'name' => $tag,
//                                'parent_id' => 0
                            ]); // Insert row
                            $industry_sector_id = Model::insertID();
                        } else {
                            $industry_sector_id = $checkTag->id;
                        }
                    }
                }
            }


            // Insert vacancy
            $jobInfo = get_contents('https://app.crelate.com/api/pub/v1/jobs/'.$job->Id.'?api_key=xzfjzco5kcgwmpy5jw88o4fkdw');
            $jobInfo = json_decode($jobInfo);
            $postcode = $jobInfo->Address_Business_ZipCode;

            $data = array(
//                'location_id' => $job->City ? $location_id : NULL,
//                'industry_sector_id' => isset($industry_sector_id) ? $industry_sector_id : NULL,
//                'specialist_sector_id' => isset($specialist_sector_id) ? $specialist_sector_id : 0,
                'title' => $jobName,
                'ref' => $job->Id,
                'contract_type' => 'permanent',
//                'salary_type' => 'salary',
//                'salary_value' => $job->Compensation,
                'content' => $job->Description,
                'content_short' => mb_substr($job->Description, 0, 250),
                'consultant_id' => 0,
                'postcode' => $postcode,
                'slug' => makeSlug($jobName),
                'job_code' => $job->JobCode,
                'job_id' => $job->Id,
                'time_expire' => time() + 24*3600*180,
                'time' => time(),
            );

            $result = Model::insert('vacancies', $data); // Insert row
            $insertID = Model::insertID();

            if (!$result && $insertID) {
                // Sector
                if ($industry_sector_id) {
                    Model::insert('vacancies_sectors', [
                        'vacancy_id' => $insertID,
                        'sector_id' => $industry_sector_id
                    ]); // Insert row
                }

                // Location
                if ($location_id) {
                    Model::insert('vacancies_locations', [
                        'vacancy_id' => $insertID,
                        'location_id' => $location_id
                    ]); // Insert row
                }
            }
        }

        exit;
    }
}
/* End of file */
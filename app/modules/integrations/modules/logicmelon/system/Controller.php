<?php

class LogicmelonController extends Controller
{
    use Validator;

    public function indexAction()
    {
        header("Content-type:text/plain");

//        $data = urldecode(trim(file_get_contents('php://input')));
//        $data = file_get_contents(_SYSDIR_ . 'modules/integrations/modules/logicmelon/test.xml');
//        $data = get_contents('http://media.logicmelon.com/cacheuploads/mctech/LogicMelon.xml');
        $data = get_contents('http://media.logicmelon.com/cacheuploads/RobertsonBell/LogicMelon.xml');


        $jobIds = [];

        if ($data) {
            $data = str_replace(array("\r\n", "\n", "\r", "\t"), '', $data);
            $data = str_replace('&amp;', '&', $data);
            $data = str_replace('&nbsp;', ' ', $data);
            $jobsData = $this->getDataA2('<Jobs>', '</Jobs>', $data);

            if (!$jobsData)
                exit;

            //todo only dev mode!!!
            File::write(File::mkdir('data/integrations/logicmelon/') . 'data_' . date('Y_m_d_h_i') . '.txt', $jobsData);

            // Go by each job
            while ($job = $this->getDataA2('<Job>', '</Job>', $jobsData)) {
                $len = mb_strpos($jobsData, '</Job>');
                $jobsData = mb_substr($jobsData, $len + mb_strlen('</Job>'));


                $dataArray = array();
                $dataArray['id'] = $this->getDataA2('<JobID>', '</JobID>', $job);
                $dataArray['ref'] = filter(trim($this->getDataA2('<JobReference>', '</JobReference>', $job)));
                $dataArray['title'] = filter($this->getDataA2('<Jobtitle>', '</Jobtitle>', $job));
                $dataArray['time'] = strtotime($this->getDataA2('<PostDate>', '</PostDate>', $job));
                $dataArray['start_date'] = strtolower($this->getDataA2('<StartDate>', '</StartDate>', $job));
                $dataArray['contract_type'] = strtolower($this->getDataA2('<JobType>', '</JobType>', $job));
                $dataArray['job_hours'] = strtolower($this->getDataA2('<JobHours>', '</JobHours>', $job));
                $dataArray['sector'] = strtolower($this->getDataA2('<specialism>', '</specialism>', $job));
                $dataArray['salary_value'] = filter($this->getDataA2('<Salary>', '</Salary>', $job));
                $dataArray['salary_min'] = strtolower($this->getDataA2('<Salaryfrom>', '</Salaryfrom>', $job));
                $dataArray['salary_max'] = strtolower($this->getDataA2('<Salaryto>', '</Salaryto>', $job));
                $dataArray['location'] = filter($this->getDataA2('<JobLocation>', '</JobLocation>', $job));
                $dataArray['content'] = filter($this->getDataA2('<JobDescription>', '</JobDescription>', $job));
                $dataArray['slug'] = makeSlug($this->getDataA2('<Jobtitle>', '</Jobtitle>', $job));
//                $dataArray['specialism'] = strtolower($this->getDataA2('<Specialism>', '</Specialism>', $job));
//                if ($dataArray['specialism'] !== 'finance' && $dataArray['specialism'] !== 'procurement')
//                    $dataArray['specialism'] = 'other';

                $dataArray['contact_name'] = $this->getDataA2('<Contactname>', '</Contactname>', $job);
                $dataArray['contact_number'] = $this->getDataA2('<ContactNumber>', '</ContactNumber>', $job);
                $dataArray['contact_email'] = $this->getDataA2('<ContactEmail>', '</ContactEmail>', $job);
                $dataArray['last_date'] = strtotime($this->getDataA2('<LastApplicationDate>', '</LastApplicationDate>', $job));





                if ($dataArray['title']) {
                    $vacancy_id = NULL;

                    $chkVacExists = Model::fetch(Model::select('vacancies', "`ref` = '" . $dataArray['ref'] . "'  LIMIT 1"));


                    // Get consultant id
                    $chkConsultantExists = LogicmelonModel::getUserByEmail($dataArray['contact_email']);
                    $consultant_id = $chkConsultantExists->id;
                    if (!$consultant_id) $consultant_id = 0;

                    if (!$chkVacExists->id) {
                        /////////////////////////////////// Insert New record ///////////////////////////////////
                        $k = 0;
                        $chkVacSlug = Model::count('vacancies', "*", "`slug` = '" . $dataArray['slug'] . "'");
                        if ($chkVacSlug > 0) {
                            $k = $chkVacSlug;
                            $dataArray['slug'] = makeSlug($this->getDataA('<title><![CDATA[', ']]></title>', $job) . '-' . $dataArray['ref']);
                        }

                        $dataVac = array(
                            'title'             => $dataArray['title'],
                            'ref'               => $dataArray['ref'],
                            'contract_type'     => mb_strtolower($dataArray['contract_type']),
                            'contract_length'   => $dataArray['contract_length'],
                            //                        'salary_type'       => $dataArray['salary_type'],
                            //                        'postcode'          => $dataArray['postcode'],
                            'salary_value'      => $dataArray['salary_value'],
                            'content'           => $dataArray['content'],
                            'content_short'     => filter(mb_substr(strip_tags(reFilter($dataArray['content'])), 0, 250)),
//                            'consultant_id'     => $consultant_id,
                            'meta_title'        => $dataArray['title'],
                            'meta_keywords'     => $dataArray['title'],
                            'meta_desc'         => $dataArray['title'],
                            'client_email'      => $dataArray['contact_email'],
                            'slug'              => $dataArray['slug'],
                            'time_expire'       => time() + 24 * 3600 * 180,
                            'time'              => time()
                        );

                        $result   = Model::insert('vacancies', $dataVac); // Insert row
                        $insertID = Model::insertID();

                        if (!$result && $insertID) {

                            $jobIds[] = $insertID;
                            $vacancy_id = $insertID;

                            echo "Job Added Successfully (ID: " . $insertID . ") " . url('job', $dataArray['slug']) . "\n";
                        } else {
                            print_data('Some error');
                        }
                    }
                    else {
                        /////////////////////////////////// UPDATE JOB POSTING ///////////////////////////////////

                        $k = 0;
                        $chkVacSlug = Model::count('vacancies', "*", "`slug` = '" . $dataArray['slug'] . "' AND `id` != '$chkVacExists->id'");
                        //                    print_data('$chkVacSlug');
                        //                    print_data($chkVacSlug);

                        if ($chkVacSlug > 0) {
                            $k = $chkVacSlug;
                            $dataArray['slug'] = makeSlug($this->getDataA('<title><![CDATA[', ']]></title>', $job) . '-' . $dataArray['ref']);
                        }

                        $dataArr = array(
                            'title'             => $dataArray['title'],
                            'ref'               => $dataArray['ref'],
                            'contract_type'     => mb_strtolower($dataArray['contract_type']),
                            'contract_length'   => $dataArray['contract_length'],
                            //                        'salary_type'       => $dataArray['salary_type'],
                            //                        'postcode'          => $dataArray['postcode'],
                            'salary_value'      => $dataArray['salary_value'],
                            'content'           => $dataArray['content'],
                            'content_short'     => filter(mb_substr(strip_tags(reFilter($dataArray['content'])), 0, 250)),
//                            'consultant_id'     => $consultant_id,
                            'meta_title'        => $dataArray['title'],
                            'meta_keywords'     => $dataArray['title'],
                            'meta_desc'         => $dataArray['title'],
                            'client_email'      => $dataArray['contact_email'],
                            'slug'              => $dataArray['slug'],
                            'time_expire'       => time() + 24 * 3600 * 180,
                            'time'              => time(),
                            'deleted'           => 'no'
                        );

                        Model::update('vacancies', $dataArr, "`ref` = '" . $dataArray['ref'] . "'");

                        $jobIds[] = $chkVacExists->id;
                        $vacancy_id = $chkVacExists->id;
                        echo "Job Updated Successfully (ID: " . $vacancy_id . ") " . url('job', $dataArray['slug']) . "\n";
                    }


                    if ($vacancy_id) {
                        Model::delete('vacancies_locations', "`vacancy_id` = '$vacancy_id'");
                        $location_names = explode(",", $dataArray['location']);

                        // Add locations
                        foreach ($location_names as $locName) {
                            $chkLocationExists = Model::fetch(Model::select('locations', "`name` = '" . filter($locName) . "' LIMIT 0,1"));
                            $location_id = $chkLocationExists->id;

                            if (!$location_id) {
                                $resultLoc   = Model::insert('locations', ['name' => filter($locName)]); // Insert row
                                $insertIDLoc = Model::insertID();

                                if (!$resultLoc && $insertIDLoc)
                                    $location_id = $insertIDLoc;
                            }

                            if ($location_id) {
                                Model::insert('vacancies_locations', [
                                    'vacancy_id' => $vacancy_id,
                                    'location_id' => $location_id
                                ]); // Insert row
                            }
                        }


                        // Sectors
                        // Deleting of assigned sectors required to be out of validation, because in case of empty sectors values from remote API we need to remove all assigned sectors as well
                        Model::delete('vacancies_sectors', "`vacancy_id` = '$vacancy_id'");

                        if ($dataArray['sector']) {
                            $sector_names = explode(",", $dataArray['sector']);

                            if ($sector_names && is_array($sector_names) && count($sector_names) > 0) {
                                foreach ($sector_names as $sectorName) {
                                    $chkLocationExists = Model::fetch(Model::select('sectors', "`name` = '" . filter($sectorName) . "' LIMIT 0,1"));
                                    $sector_id = $chkLocationExists->id;

                                    if (!$sector_id) {
                                        $resultSec   = Model::insert('sectors', ['name' => filter($sectorName)]); // Insert row
                                        $insertIDSec = Model::insertID();

                                        if (!$resultSec && $insertIDSec)
                                            $sector_id = $insertIDSec;
                                    }

                                    if ($sector_id) {
                                        Model::insert('vacancies_sectors', [
                                            'vacancy_id' => $vacancy_id,
                                            'sector_id' => $sector_id
                                        ]); // Insert row
                                    }
                                }
                            }
                        }

                        //            $consultant_name = explode(" ", $dataArray['consultant']);
                        //            if (count($consultant_name) > 1) {
                        //                $c_firstname = trim($consultant_name[0]);
                        //                $c_lastname = trim($consultant_name[1]);
                        //                $chkconsultantExists = $db->prepare("SELECT user_id FROM `team` WHERE `firstname` =:firstname and `lastname` =:lastname LIMIT 0,1");
                        //                $chkconsultantExists->execute(array('firstname' => $c_firstname, 'lastname' => $c_lastname));
                        //                $chkconsultantExists = $chkconsultantExists->fetch();
                        //                $consultant_id = $chkConsultantExists->id;
                        //
                        ////                if ($consultant_id == 0) {
                        ////                    $createeVac = $db->prepare("INSERT INTO `team` SET  `firstname` =:firstname, `lastname` =:lastname");
                        ////                    $createeVac->execute(array('firstname' => $c_firstname, 'lastname' => $c_lastname));
                        ////                    $consultant_id = $db->lastInsertId();
                        ////                }
                        //                if ($consultant_id != '') {
                        //                    $updateVac = $db->prepare("UPDATE `vacancies` SET  `consultant_id` = :consultant_id  WHERE `vacancy_id`= :vacancy_id");
                        //                    $updateVac->execute(array('consultant_id' => $consultant_id, 'vacancy_id' => $vacancy_id));
                        //                }
                        //            }

                    }
                }

            }
        } else {
            echo "No data provided" . "\n";
        }


        // Removing not existed vacancies
        Model::import('panel/vacancies');
        $allVacancies  = VacanciesModel::getAll(false, false, false, 'no');

        foreach ($allVacancies as $vacancy) {
            if (!in_array($vacancy->id, $jobIds))
                Model::update('vacancies', ['deleted' => 'yes'], "`id` = '$vacancy->id'");
        }

        exit;
    }

    public function deleteAction()
    {
        $data = urldecode(trim(file_get_contents('php://input')));
//        $data = file_get_contents(_SYSDIR_ . "testdelete.xml");

        $dataArray = array();

        if (isset($data)){
            $jobData = $this->getDataA2('<delete>','</delete>',$data);

            $dataArray['id'] = trim($this->getDataA2('<id>','</id>',$jobData));
            $dataArray['username'] = $this->getDataA2('<username>','</username>',$jobData);
            $dataArray['password'] = $this->getDataA2('<password>','</password>',$jobData);

            if (isset($dataArray['id'])) {
                $chkVacExists = Model::fetch(Model::select('vacancies', "`ref` = '" . $dataArray['id'] . "'  LIMIT 0,1"));
                $vacancy_id = $chkVacExists->id;

                if ($vacancy_id != '') {
                    Model::update('vacancies', ['time_expire' => time() - 180*24*3600], "`id` = '" . $chkVacExists->id . "'");
                    echo "DELETE - Job Deleted Successfully";
                } else {
                    echo "ERROR - Job Not Found";
                }
            }
        }



        exit;
    }

    public function getDataA($strStart, $strEnd, $text)
    {
        for ($i = 0; $i <= strlen($text); $i++) {
            if (substr($text, $i, strlen($strStart)) == $strStart) {
                $st = $i;
                $k = $i;
                while (substr($text, $k, strlen($strEnd)) != $strEnd) {
                    $k++;
                }
                $en = $k + strlen($strEnd);
                $start = $st + strlen($strStart);
                $tmpstr = substr($text, $start, $k - $start);

            }
        }
        return $tmpstr;
    }

    public function getDataA2($strStart, $strEnd, $text)
    {
        $text = preg_replace("/\r\n|\n|\r/", " ", $text);
        $strStart = addslashes($strStart);
        $strEnd = addslashes($strEnd);

        $strStart = str_replace("/", "\\/", $strStart);
        $strEnd = str_replace("/", "\\/", $strEnd);

        $strStart = str_replace("(", "\(", $strStart);
        $strEnd = str_replace("(", "\(", $strEnd);

        $strStart = str_replace(")", "\)", $strStart);
        $strEnd = str_replace(")", "\)", $strEnd);

        $pattern = "/$strStart(.*?)$strEnd/i";
        preg_match($pattern, $text, $matches);

        return $matches[1];
    }
}

/* End of file */
<?php

class IbiduController extends Controller
{
    use Validator;

    public function indexAction()
    {
        header("Content-type:text/plain");

        $data = urldecode(trim(file_get_contents('php://input')));
//        $data = file_get_contents(_SYSDIR_ . "test.xml");

        if ($data) {
            $data = str_replace(array("\r\n", "\n", "\r", "\t"), '', $data);
            $data = str_replace('&amp;', '&', $data);
            $data = str_replace('&nbsp;', ' ', $data);

            //todo only dev mode!!!
            File::write(File::mkdir('data/integrations/idibu/') . 'data_' . date('Y_m_d_h_i') . '.txt', $data);

            $jobData = getdataA2('<job>', '</job>', $data);

            $dataArray = array();
            $dataArray['job_id'] = getdataA2('<JobID>', '</JobID>', $jobData);
            $dataArray['ref'] = filter(trim(getdataA('<JobReference><![CDATA[', ']]></JobReference>', $jobData)));
            $dataArray['title'] = filter(getdataA('<JobTitle><![CDATA[', ']]></JobTitle>', $jobData));
            $dataArray['contract_type'] = strtolower(getdataA2('<JobType>', '</JobType>', $jobData));

            $dataArray['DatePosted'] = strtolower(getdataA2('<DatePosted>', '</DatePosted>', $jobData));
            $dataArray['ExpiryDate'] = strtolower(getdataA2('<ExpiryDate>', '</ExpiryDate>', $jobData));
            $dataArray['SalaryCurrency'] = strtolower(getdataA2('<SalaryCurrency>', '</SalaryCurrency>', $jobData));
            $dataArray['salary_type'] = strtolower(getdataA2('<SalaryPer>', '</SalaryPer>', $jobData));
            $dataArray['SalaryMaximum'] = strtolower(getdataA2('<SalaryMaximum>', '</SalaryMaximum>', $jobData));
            $dataArray['SalaryMinimum'] = strtolower(getdataA2('<SalaryMinimum>', '</SalaryMinimum>', $jobData));

            $dataArray['salary'] = filter(getdataA('<SalaryText><![CDATA[', ']]></SalaryText>', $jobData));
            $dataArray['sector'] = filter(getdataA('<Sector><![CDATA[', ']]></Sector>', $jobData));

            $dataArray['region'] = filter(getdataA('<JobRegion><![CDATA[', ']]></JobRegion>', $jobData));
            $dataArray['location'] = filter(getdataA('<JobLocation><![CDATA[', ']]></JobLocation>', $jobData));

            $dataArray['postcode'] = strtolower(getdataA2('<JobPostcode>', '</JobPostcode>', $jobData));
            $dataArray['LatLong'] = strtolower(getdataA2('<LatLong>', '</LatLong>', $jobData));
            $dataArray['specialism'] = strtolower(getdataA2('<Specialism>', '</Specialism>', $jobData));
            $dataArray['description'] = filter(getdataA('<JobDescription><![CDATA[', ']]></JobDescription>', $jobData));

            $dataArray['ConsultantName'] = getdataA2('<ConsultantName>', '</ConsultantName>', $jobData);
            $dataArray['ConsultantJobTitle'] = getdataA2('<ConsultantJobTitle>', '</ConsultantJobTitle>', $jobData);
            $dataArray['ConsultantTelephone'] = getdataA2('<ConsultantTelephone>', '</ConsultantTelephone>', $jobData);
            $dataArray['consultant_email'] = getdataA2('<ConsultantEmail>', '</ConsultantEmail>', $jobData);
            $dataArray['application_email'] = getdataA2('<ApplicationEmail>', '</ApplicationEmail>', $jobData);
            $dataArray['slug'] = makeSlug($dataArray['title']);

            $dataArray['sector'] = str_replace("   ", " + ", $dataArray['sector']);


            if ($pos = mb_strpos($dataArray['salary'], 'Benefits:')) {
                $dataArray['package'] = mb_substr($dataArray['salary'], $pos);
                $dataArray['salary'] = mb_substr($dataArray['salary'], 0, $pos - 2);
            }

//            print_data('sector:');
//            print_data($dataArray['sector']);
//            print_data($dataArray['description']);
//            print_data(str_replace("+", "%2B", $dataArray['sector']));
//            print_data(str_replace("%2B", "+", $dataArray['sector']));
//            $q = getdataA('<Sector><![CDATA[', ']]></Sector>', $jobData);
//            print_data(urldecode($dataArray['sector']));
//            print_data(urlencode($dataArray['sector']));
//            print_data($dataArray);
//            $dataArray['username'] = getdataA2('<username>', '</username>', $jobData);
//            $dataArray['password'] = getdataA2('<password>', '</password>', $jobData);
//            $dataArray['slug'] = makeSlug(getdataA('<title><![CDATA[', ']]></title>', $jobData));
//            $dataArray['contract_length'] = filter(getdataA('<contract_length><![CDATA[', ']]></contract_length>', $jobData));
//            $dataArray['salary_type'] = getdataA2('<salary_type>', '</salary_type>', $jobData);
//            $dataArray['salary'] = filter(getdataA(' <salary><![CDATA[', ']]></salary>', $jobData));
//            $dataArray['location'] = filter(getdataA('<location><![CDATA[', ']]></location>', $jobData));
//            $dataArray['postcode'] = getdataA2('<postcode>', '</postcode>', $jobData);
//            $dataArray['application_url'] = getdataA('<application_url><![CDATA[', ']]></application_url>', $jobData);
//            $dataArray['sector'] = filter(getdataA('<sector><![CDATA[', ']]></sector>', $jobData));
//            $dataArray['application_email'] = getdataA2('<application_email>', '</application_email>', $jobData);
//            $dataArray['consultant'] = filter(getdataA('<consultant><![CDATA[', ']]></consultant>', $jobData));
//            print_data($dataArray);
//            echo '<table cellpadding="5" cellpadding="0" border="1">';
//            foreach ($dataArray  as $field_title=>$field_value){
//                echo "<tr><td><b>".$field_title.":</b></td><td>".$field_value."</td></tr>";
//            }
//            echo '</table>';

            if ($dataArray['title']) {
                $vacancy_id = NULL;

                $chkVacExists = Model::fetch(Model::select('vacancies', "`ref` = '" . $dataArray['ref'] . "'  LIMIT 1"));

                // Get consultant id
                $chkConsultantExists = IbiduModel::getUserByEmail($dataArray['consultant_email']);
                $consultant_id = $chkConsultantExists->id;
                if (!$consultant_id) $consultant_id = 0;


                if (!$chkVacExists->id) {
                    /////////////////////////////////// Insert New record ///////////////////////////////////
                    $k = 0;
                    $chkVacSlug = Model::count('vacancies', "*", "`slug` = '" . $dataArray['slug'] . "'");

                    if ($chkVacSlug > 0) {
                        $k = $chkVacSlug;
                        $dataArray['slug'] = makeSlug($dataArray['title'] . '-' . $dataArray['ref']);
                    }

                    $dataVac = array(
                        'title'             => $dataArray['title'],
                        'ref'               => $dataArray['ref'],
                        'contract_type'     => mb_strtolower($dataArray['contract_type']),
                        'contract_length'   => $dataArray['contract_length'],
//                        'salary_type'       => $dataArray['salary_type'],
                        'postcode'          => $dataArray['postcode'],
                        'salary_value'      => $dataArray['salary'],
                        'content'           => $dataArray['description'],
                        'content_short'     => mb_substr($dataArray['description'], 0, 250),
                        'consultant_id'     => $consultant_id,
                        'package'           => $dataArray['package'],
                        'meta_title'        => $dataArray['title'],
                        'meta_keywords'     => $dataArray['title'],
                        'meta_desc'         => $dataArray['title'],
                        'client_email'      => $dataArray['application_email'],
                        'location'          => $dataArray['location'],
                        'slug'              => $dataArray['slug'],
                        'time_expire'       => convertStringTimeToInt($dataArray['ExpiryDate']),
                        'time'              => convertStringTimeToInt($dataArray['DatePosted'])
                    );

                    $result   = Model::insert('vacancies', $dataVac); // Insert row
                    $insertID = Model::insertID();

                    if (!$result && $insertID) {
                        $vacancy_id = $insertID;

                        if ($dataArray['sector'] == 'Home Management' OR $dataArray['sector'] == 'Nursing + Care')
                            echo "Job Added Successfully (ID: " . $insertID . ") " . url('job', $dataArray['slug']) . "\n";
                        else
                            echo "Job Added Successfully (ID: " . $insertID . ") " . url('health-job', $dataArray['slug']) . "\n";
                    } else {
                        print_data('Some error');
                    }
                }
                else {
                    /////////////////////////////////// UPDATE JOB POSTING ///////////////////////////////////

                    $k = 0;
                    $chkVacSlug = Model::count('vacancies', "*", "`slug` = '" . $dataArray['slug'] . "' AND `id` != '$chkVacExists->id'");

                    if ($chkVacSlug > 0) {
                        $k = $chkVacSlug;
                        $dataArray['slug'] = makeSlug($dataArray['title'] . '-' . $dataArray['ref']);
                    }

                    $dataArr = array(
                        'title'             => $dataArray['title'],
                        'ref'               => $dataArray['ref'],
                        'contract_type'     => mb_strtolower($dataArray['contract_type']),
                        'contract_length'   => $dataArray['contract_length'],
//                        'salary_type'       => $dataArray['salary_type'],
                        'postcode'          => $dataArray['postcode'],
                        'salary_value'      => $dataArray['salary'],
                        'content'           => $dataArray['description'],
                        'package'           => $dataArray['package'],
                        'content_short'     => mb_substr($dataArray['description'], 0, 250),
                        'consultant_id'     => $consultant_id,
                        'location'          => $dataArray['location'],
                        'meta_title'        => $dataArray['title'],
                        'meta_keywords'     => $dataArray['title'],
                        'meta_desc'         => $dataArray['title'],
                        'client_email'      => $dataArray['application_email'],
                        'slug'              => $dataArray['slug']
                    );

                    Model::update('vacancies', $dataArr, "`ref` = '" . $dataArray['ref'] . "'");

                    $vacancy_id = $chkVacExists->id;

                    if ($dataArray['sector'] == 'Home Management' OR $dataArray['sector'] == 'Nursing + Care')
                        echo "Job Updated Successfully (ID: " . $vacancy_id . ") " . url('job', $dataArray['slug']) . "\n";
                    else
                        echo "Job Updated Successfully (ID: " . $vacancy_id . ") " . url('health-job', $dataArray['slug']) . "\n";
                }


                if ($vacancy_id) {
                    Model::delete('vacancies_locations', "`vacancy_id` = '$vacancy_id'");
                    $location_names = explode(",", $dataArray['region']);

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
        } else {
            echo "No data provided" . "\n";
        }

        exit;
    }

    public function deleteAction()
    {
        $data = urldecode(trim(file_get_contents('php://input')));
//        $data = file_get_contents(_SYSDIR_ . "testdelete.xml");

        $dataArray = array();

        if (isset($data)){
            $jobData = getdataA2('<delete>','</delete>',$data);

            $dataArray['id'] = trim(getdataA2('<id>','</id>',$jobData));
            $dataArray['username'] = getdataA2('<username>','</username>',$jobData);
            $dataArray['password'] = getdataA2('<password>','</password>',$jobData);

            /*
            echo '<table cellpadding="5" cellpadding="0" border="1">';
            foreach ($dataArray  as $field_title=>$field_value){
                echo "<tr><td><b>".$field_title.":</b></td><td>".$field_value."</td></tr>";
            }
            echo '</table>';
            */

            if (isset($dataArray['id'])) {
                $chkVacExists = Model::fetch(Model::select('vacancies', "`ref` = '" . $dataArray['id'] . "'  LIMIT 0,1"));
                $vacancy_id = $chkVacExists->id;
//                print_data('$chkVacExists');
//                print_data($chkVacExists);

                if ($vacancy_id != '') {
                    Model::update('vacancies', ['time_expire' => time() - 180*24*3600], "`id` = '" . $chkVacExists->id . "'");
//                    $deleteJob = $db->prepare("UPDATE `vacancies` SET `date` = FROM_UNIXTIME(:expire_date) WHERE `vacancy_id`=:vacancy_id ");
//                    $deleteJob->execute(array('expire_date' => time() - 180*24*3600, 'vacancy_id' => $vacancy_id));

//			$deleteJob = $db->prepare("DELETE FROM `vacancies` WHERE `vacancy_id`=:vacancy_id ");
//			$deleteJob->execute(array('vacancy_id' => $vacancy_id));
//
//			$delete_vl = $db->prepare("DELETE FROM `vacancies_locations` WHERE `vacancy_id`= :vacancy_id");
//			$delete_vl->execute(array('vacancy_id' => $vacancy_id));
//
//			$delete_vs = $db->prepare("DELETE FROM `vacancies_sectors` WHERE `vacancy_id`=:vacancy_id");
//			$delete_vs->execute(array('vacancy_id' => $vacancy_id));

                    echo "DELETE - Job Deleted Successfully";
                } else {
                    echo "ERROR - Job Not Found";
                }
            }
        }

        exit;

//        $data['views'] = '++';
//        Model::update('vacancies', $data, "`id` = '" . $this->view->job->id . "'");
//        $this->view->consultant = IbiduModel::getUser($this->view->job->consultant_id);
    }
}
/* End of file */
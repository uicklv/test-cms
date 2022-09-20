<?php

class BroadbeanController extends Controller
{
    use Validator;

    /**
     * URL for requests = https://www.squareoneresources.com/broadbean
     * ALL applications must go to email from integration field !!!
     * It must have CV file attached and the 'reply to' email address must be candidate's email address and name !!!
     */

    public function indexAction()
    {
        header("Content-type:text/plain");

        /*
         * for local need use data from file test.xml
         */
        $data = urldecode(trim(file_get_contents('php://input')));
        $data = File::read(_SYSDIR_ . 'modules/integrations/modules/broadbean/test.xml');

        //$data = iconv('ISO-8859-1', 'UTF-8', $data); // if encoding needed
        $dataArray = $this->prepare_data($data);

        if (!$dataArray) {
            echo "No data provided";
            exit;
        }

        //Log  data
        $this->logger($data, $dataArray);

        //Delete vacancy if command = delete
        if ($dataArray['command'] === 'delete') {
            $this->deleteVacancy($dataArray);
        }
        // Get consultant id
        $consultant_id = $this->getConsultant($dataArray['consultant_email']);

        $dataVac = [
            'title'             => $dataArray['title'],
            'ref'               => $dataArray['ref'],
            'contract_type'     => mb_strtolower($dataArray['contract_type']),
            'salary_value'      => $dataArray['salary_value'],
            'content'           => $dataArray['content'],
            'content_short'     => filter(processDesc($dataArray['content'], 250, true, false)),
            'consultant_id'     => $consultant_id,
            'app_email'         => $dataArray['application_email'],
            'meta_title'        => $dataArray['title'],
            'meta_keywords'     => $dataArray['title'],
            'meta_desc'         => $dataArray['title'],
            'time_expire'       => strtotime($dataArray['time_expire']),
            'time'              => time()
        ];

        //insert or update vacancy
        $vacancy_id = $this->insertOrUpdateVacancy($dataVac);

        if ($vacancy_id) {
            //Location
            $this->updateLocation($vacancy_id, $dataArray['location']);
            //Sector
            $this->updateSector($vacancy_id, $dataArray['sector']);
        }
        exit;
    }

    /**
     * transformation data from xml format to array
     * @param $data
     * @return array|false
     */
    private function prepare_data($data)
    {
        if (!$data)
            return false;

        $data = str_replace(["\r\n", "\n", "\r", "\t"], '', $data);
        $data = str_replace('&amp;', '&', $data);
        $data = str_replace('&nbsp;', ' ', $data);
        $jobData = $this->getdataA2('<job>', '</job>', $data);

        $dataArray = [];
        $dataArray['ref'] = filter(trim($this->getdataA2('<job_reference>', '</job_reference>', $jobData)));
        $dataArray['title'] = filter($this->getdataA2('<job_title>', '</job_title>', $jobData));
        $dataArray['content'] = filter($this->getdataA('<job_description><![CDATA[', ']]></job_description>', $jobData));
        $dataArray['application_email'] = $this->getdataA2('<application>', '</application>', $jobData);
        $dataArray['location'] = filter($this->getdataA2('<job_location>', '</job_location>', $jobData));
        $dataArray['contract_type'] = strtolower($this->getdataA2('<job_listing_type>', '</job_listing_type>', $jobData));
        $dataArray['command'] = filter($this->getdataA2('<command>', '</command>', $jobData));
        $dataArray['tel'] = filter($this->getdataA2('<company_contact_phone>', '</company_contact_phone>', $jobData));
        $dataArray['sector'] = filter($this->getdataA2('<job_industry>', '</job_industry>', $jobData));
        $dataArray['featured'] = filter($this->getdataA2('<featured>', '</featured>', $jobData));
        $dataArray['username'] = filter($this->getdataA2('<username>', '</username>', $jobData));
        $dataArray['company_logo'] = filter($this->getdataA2('<company_logo>', '</company_logo>', $jobData));
        $dataArray['salary_value'] = filter($this->getdataA2('<job_salary>', '</job_salary>', $jobData));
        $dataArray['company_name'] = filter($this->getdataA2('<company_name>', '</company_name>', $jobData));
        $dataArray['time_expire'] = filter($this->getdataA2('<job_expires>', '</job_expires>', $jobData));
        $dataArray['password'] = filter($this->getdataA2('<password>', '</password>', $jobData));
        $dataArray['job_author'] = filter($this->getdataA2('<job_author>', '</job_author>', $jobData));
        $dataArray['company_contact_name'] = filter($this->getdataA2('<company_contact_name>', '</company_contact_name>', $jobData));
        $dataArray['package'] = filter($this->getdataA2('<job_benefits>', '</job_benefits>', $jobData));
        $dataArray['slug'] = makeSlug($dataArray['title']);


        return $dataArray;
    }

    /**
     * input data logging
     * @param $defaultData
     * @param $preparedData
     * @return int
     */
    private function logger($defaultData, $preparedData)
    {
        $logIndex = array_key_exists('ref', $preparedData) ? $preparedData['ref'] : '';

        return File::write(File::mkdir('data/integrations/broadbean/') . $logIndex . '_data_' . date('Y_m_d_h_i')
            . '_' . $preparedData['ref'] . '.txt', $defaultData);
    }

    /**
     * get data from xml tag with ![CDATA]
     * @param $strStart
     * @param $strEnd
     * @param $text
     * @return false|string
     */
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

    /**
     * get data from xml tag
     * @param $strStart
     * @param $strEnd
     * @param $text
     * @return mixed
     */
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

    /**
     * get consultant id by email
     * @param $email
     * @return int
     */
    private function getConsultant($email)
    {
        $consultant_id = 0;
        $consultant = BroadbeanModel::getUserByEmail($email);
        if ($consultant)
            $consultant_id = $consultant->id;

        return $consultant_id;
    }

    /**
     * insert or update vacancy
     * @param $dataVac
     * @return false|int|string
     */
    private function insertOrUpdateVacancy($dataVac)
    {
        //check if vacancy exists
        $chkVacExists = Model::fetch(Model::select('vacancies', "`ref` = '" . $dataVac['ref'] . "' AND `deleted` = 'no' LIMIT 1"));

        $vacancy_id = false;

        if (!$chkVacExists->id) {
            //insert new vacancy
            $dataVac['slug'] = Model::createIdentifier('vacancies', makeSlug($dataVac['title']));

            $result   = Model::insert('vacancies', $dataVac); // Insert row
            $insertID = Model::insertID();

            if (!$result && $insertID) {
                $vacancy_id = $insertID;
                echo "Job Added Successfully (ID: " . $insertID . ") " . url('job', $dataVac['slug']) . "\n";
            } else {
                echo "Insert error";
            }

        } else {
            //update existing vacancy
            $dataVac['slug'] = Model::createIdentifier('vacancies', makeSlug($dataVac['title']), 'slug', $chkVacExists->id);

            $result = Model::update('vacancies', $dataVac, "`ref` = '" . $dataVac['ref'] . "'");

            if ($result) {
                $vacancy_id = $chkVacExists->id;
                echo "Job Updated Successfully (ID: " . $vacancy_id . ") " . url('job', $dataVac['slug']) . "\n";
            } else {
                echo "Update error";
            }
        }

        return $vacancy_id;
    }

    /**
     * update vacancy location
     * @param $vacancy_id
     * @param $location
     */
    private function updateLocation($vacancy_id, $location)
    {
        Model::delete('vacancies_locations', "`vacancy_id` = '$vacancy_id'");
        $location_names = explode(",", $location);

        // Add locations
        if ($location_names) {
            foreach ($location_names as $locName) {
                $chkLocationExists = Model::fetch(Model::select('locations', "`name` = '" . filter($locName) . "' LIMIT 0,1"));
                $location_id = $chkLocationExists->id;

                if (!$location_id) {
                    $resultLoc = Model::insert('locations', ['name' => filter($locName)]); // Insert row
                    $insertIDLoc = Model::insertID();

                    if (!$resultLoc && $insertIDLoc)
                        $location_id = $insertIDLoc;
                }

                if ($location_id) {
                    Model::insert('vacancies_locations', [
                        'vacancy_id' => $vacancy_id,
                        'location_id' => $location_id
                    ]);
                }
            }
        }
    }

    /**
     * update vacancy sectors
     * @param $vacancy_id
     * @param $sector
     */
    private function updateSector($vacancy_id, $sector)
    {
        Model::delete('vacancies_sectors', "`vacancy_id` = '$vacancy_id'");

        if ($sector) {
            $sector_names = explode(",", $sector);

            if ($sector_names) {
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
                        ]);
                    }
                }
            }
        }
    }

    /**
     * delete vacancy if command = delete
     * @param $data
     */
    private function deleteVacancy($data)
    {
        $chkVacExists = Model::fetch(Model::select('vacancies', "`ref` = '" . $data['ref'] . "'  LIMIT 1"));
        if (!$chkVacExists || $chkVacExists->deleted === 'yes') {
            echo "Job not found (ID: " . $data['ref'] . ")";
        } else {
            $result = Model::update('vacancies', ["deleted" => 'yes'], "`ref` = '" . $data['ref'] . "'");

            if ($result) {
                echo "Job Delete Successfully (ID: " . $data['ref'] . ") " . url('job', $data['slug']) . "\n";
            } else {
                echo "Update error";
            }
        }

        exit;
    }
}
/* End of file */
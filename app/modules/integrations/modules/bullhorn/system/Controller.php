<?php

class BullhornController extends Controller
{
    protected $bullhorn_username        = "emma.bache"; // operations.reports
    protected $bullhorn_password        = "M4xWellB0nd!!";
    protected $bullhorn_client_id       = "836d72d5-308f-43c4-9ffb-580aa248605a";
    protected $bullhorn_client_secret   = "4zeIo3XPKy0xdErdnSx6iEWq";

    private $log_messages               = array();
    protected $bullhorn_api             = false; //

    // todo: set Redirect URL to http(s)://(site.com)/bullhorn/auth

    public function access()
    {
        incFile('modules/bullhorn/system/inc/Api.php');
        $this->bullhorn_api = new Bullhorn_API(array(
            "client_id"     => $this->bullhorn_client_id,
            "client_secret" => $this->bullhorn_client_secret,
            "redirect_uri"  => url('bullhorn/auth'),
            "username"      => $this->bullhorn_username,
            "password"      => $this->bullhorn_password
        ));

        $access_token = BullhornModel::get_row(array(), 'id', 'DESC');
        if (!$access_token || ($access_token && (int)$access_token->created + (int)$access_token->expires < time()) ) {
            $auto_auth = $this->bullhorn_api->auto_auth();

            if ($auto_auth) {
                print_data('in access token');
                $access_token = BullhornModel::get_row(array(), 'id', 'DESC');
            } else {
                echo "BullHorn Integration need to be reauthorized. Click <a href='" . url('bullhorn/auth') . "'>HERE</a> to continue";
            }
        }

        return $access_token;
    }

    public function indexAction()
    {
        $this->access();
        exit;
    }

    public function authAction()
    {
        //$this->access();
        incFile('modules/bullhorn/system/inc/Api.php');
        $this->bullhorn_api = new Bullhorn_API(array(
            "client_id"     => $this->bullhorn_client_id,
            "client_secret" => $this->bullhorn_client_secret,
            "redirect_uri"  => url('bullhorn/auth'),
            "username"      => $this->bullhorn_username,
            "password"      => $this->bullhorn_password
        ));


        Model::insert('bh_logs', ['message' => 'AUTH:: ' . json_encode($_GET), 'time' => time()]);

        if (!get('code')) {
            Model::insert('bh_logs', ['message' => 'redirect with NO code', 'time' => time()]);
            redirect($this->bullhorn_api->get_oauth_link());
        } else if (get('code')) {
            $token = $this->bullhorn_api->get_access_token(get('code'));
            $http_code = $this->bullhorn_api->get_last_http_code();

            if ($http_code == 200) {
                $data = array(
                    'access_token'  => $token->access_token,
                    'refresh_token' => $token->refresh_token,
                    'expires'       => $token->expires_in,
                    'note'          => 'auth',
                    'created'       => time()
                );

                $result   = Model::insert('bullhorn_integration', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Model::insert('bh_logs', ['message' => 'redirect to sync_candidates', 'time' => time()]);
                    redirect(url('bullhorn/sync_candidates'));
                }
            }
        }
        exit;
    }

    private function refresh_token($access_token)
    {
        //$this->access();
        incFile('modules/bullhorn/system/inc/Api.php');
        $this->bullhorn_api = new Bullhorn_API(array(
            "client_id"     => $this->bullhorn_client_id,
            "client_secret" => $this->bullhorn_client_secret,
            "redirect_uri"  => url('bullhorn/auth'),
            "username"      => $this->bullhorn_username,
            "password"      => $this->bullhorn_password
        ));

        $token = $this->bullhorn_api->get_refresh_token($access_token->refresh_token);
        print_data('REFRESHED token <<<<<');
        //print_data($token);

        if (isset($token->access_token)) {
            $data = array(
                'access_token'  => $token->access_token,
                'refresh_token' => $token->refresh_token,
                'expires'       => $token->expires_in,
                'note'          => 'refresh',
                'created'       => time()
            );

            $result   = Model::insert('bullhorn_integration', $data); // Insert row
            $insertID = Model::insertID();

            print_data('Token refreshed');
        } else {
            print_data('Token cannot be refreshed. Closing application');
        }
    }

    public function syncAction()
    {
        print_data('Starting data synchronization...');

        $candidates = BullhornModel::getAll('users', [
            'bh_candidate_id' => 0,
            'bh_notes' => 'Integration access revoked',
            'role' => 'user',
        ]);

        $applications = BullhornModel::getAll('cv_library', array( // TODO ! CV Library must have application_id and bh_notes columns
            'application_id' => 0,
            'bh_notes' => 'Integration access revoked'
        ));


        if (($applications && count($applications) > 0)) {
            $access_token = BullhornModel::get_row(array(), 'created', 'DESC');

            if ($access_token) {
                print_data("Token loaded, expires in " . (((int)$access_token->created + (int)$access_token->expires) - time()) . " seconds");

                print_data('Token expired');
                $this->refresh_token($access_token);
                $access_token = BullhornModel::get_row(array(), 'created', 'DESC');

                $bh_token = $this->bullhorn_api->get_login($access_token->access_token);
                if (isset($bh_token->errorCode) && $bh_token->errorCode == 400) {
                    print_data($bh_token->errorMessage);
                    $this->refresh_token($access_token);
                    $access_token = BullhornModel::get_row(array(), 'created', 'DESC');
                    $bh_token = $this->bullhorn_api->get_login($access_token->access_token);

                }

                if (isset($bh_token->BhRestToken) && isset($bh_token->restUrl)) {

                    // Candidates
                    if ($candidates && count($candidates) > 0) {
                        print_data("Found " . count($candidates) . ' candidates with failed synchronization');

                        foreach ($candidates as $candidate) {
                            $bh_candidate_id = NULL;
                            $file_id = NULL;
                            $search_candidate = $this->bullhorn_api->get_json($bh_token->restUrl . "search/Candidate" . "?" . http_build_query(array("query" => "email:" . $candidate->email, "fields" => "id, name")), NULL, array("BHRestToken: " . $bh_token->BhRestToken));

                            if ($search_candidate && isset($search_candidate->total) && $search_candidate->total > 0) {
                                $candidates = array_values($search_candidate->data);
                                $bh_candidate_id = $candidates[0]->id;
                            } else {
                                $bh_data = array(
                                    "firstName" => $candidate->firstname, // Field name
                                    "lastName" => $candidate->lastname, // Surname
                                    "name" => $candidate->firstname . " " . $candidate->lastname,
                                    "email" => $candidate->email, // Personal Email
                                    "mobile" => $candidate->tel, // Mobile Phone
                                    "source" => "Website",
                                    "status" => "Available",
                                    "isDeleted" => FALSE,
                                );

                                $bh_candidate = $this->bullhorn_api->get_json($bh_token->restUrl . "entity/Candidate", json_encode($bh_data), array("Content-Type: application/json", "BHRestToken: " . $bh_token->BhRestToken), "PUT"); // Create candidate for each submission

                                if ($bh_candidate && $bh_candidate->changedEntityId) {
                                    $bh_candidate_id = $bh_candidate->changedEntityId;
                                }
                            }

                            if ($bh_candidate_id !== NULL) {
                                if (BullhornModel::update_row('users', array(
                                    'id' => $candidate->id
                                ), array(
                                    'bh_candidate_id' => $bh_candidate_id,
                                    'bh_notes' => NULL
                                ))) {
                                    print_data("Candidate #" . $candidate->id . ' profile uploaded.');
                                } else {
                                    print_data("Candidate #" . $candidate->id . ' profile upload FAILED.');
                                }
                            }
                        }
                    }


                    if ($applications && count($applications) > 0) {
                        print_data("Found " . count($applications) . ' applications with failed synchronization');

                        foreach ($applications as $application) {
                            $bh_candidate_id = NULL;
                            $application_id = NULL;

                            if ($application->bh_candidate_id) {
                                $bh_candidate_id = $application->bh_candidate_id;
                            } else {
                                if ($application->email) {
                                    $candidate = BullhornModel::getAll('users',array(
                                        'email' => $application->email
                                    ));
                                    if ($candidate && $candidate[0]->bh_candidate_id) {

                                        $bh_candidate_id = $candidate[0]->bh_candidate_id;
                                        $get_candidate = $this->bullhorn_api->get_json(
                                            $bh_token->restUrl . "entity/Candidate/" . $bh_candidate_id . "?fields=id,email",
                                            NULL,
                                            array("BHRestToken: " . $bh_token->BhRestToken)
                                        );

                                        if ($get_candidate && isset($get_candidate->data) && isset($get_candidate->data->id) && $get_candidate->data->id) {
                                            $bh_candidate_id = $get_candidate->data->id;
                                        } else {
                                            $bh_candidate_id = NULL;
                                        }
                                    }
                                }

                                if (!$bh_candidate_id) {
                                    $bh_data = array(
                                        "name" => $application->name,
                                        "email" => $application->email, // Personal Email
                                        "mobile" => $application->tel, // Mobile Phone
                                        "source" => "Website",
                                        "status" => "Available",
                                        "isDeleted" => FALSE,
                                    );

                                    $name_parts = explode(" ", $application->name);
                                    if ($name_parts && is_array($name_parts) && count($name_parts) == 2) {
                                        list($firstname, $lastname) = $name_parts;
                                        $bh_data['firstName'] = $firstname;
                                        $bh_data['lastName'] = $lastname;
                                    }

                                    if ($application->filename) {
                                        $cv_path = _SYSDIR_ . 'data/cvs/' . $application->cv;

                                        if (file_exists($cv_path))
                                            $bh_data['description'] = $this->parseFile($cv_path);
                                    }

                                    $search_candidate = $this->bullhorn_api->get_json($bh_token->restUrl . "search/Candidate" . "?" . http_build_query(array("query" => "email:" . $application->email, "fields" => "id")), NULL, array("BHRestToken: " . $bh_token->BhRestToken));

                                    if ($search_candidate && isset($search_candidate->total) && $search_candidate->total > 0) {
                                        $search_candidates = array_values($search_candidate->data);
                                        $bh_candidate_id = $search_candidates[0]->id;
                                    } else {
                                        $get_candidate = $this->bullhorn_api->get_json($bh_token->restUrl . "entity/Candidate", json_encode($bh_data), array("Content-Type: application/json", "BHRestToken: " . $bh_token->BhRestToken), "PUT"); // Create candidate for each submission
                                        if ($get_candidate && $get_candidate->changedEntityId) {
                                            $bh_candidate_id = $get_candidate->changedEntityId;
                                        }
                                    }
                                }
                            }

                            if ($bh_candidate_id) {
                                if ($application->cv) {
                                    //Added CV to Candidate
                                   $file_id =  $this->putFile($application->name, _SYSDIR_ . 'data/cvs/' . $application->cv,
                                       $bh_token, $bh_candidate_id);

                                   print_data($file_id);
                                }

                                if ($application->vacancy_id) {
                                    $vacancy = BullhornModel::getAll('vacancies', array(
                                        'id' => $application->vacancy_id
                                    ), false, false, 1);

                                    //Added job Submission
                                    if ($vacancy && $vacancy[0]->ref) {
                                        $data = array(
                                            "candidate" => array(
                                                "id" => $bh_candidate_id
                                            ),
                                            "jobOrder" => array(
                                                "id" => $vacancy[0]->ref
                                            ),
                                            "source" => "web"
                                        );

                                        $bh_application = $this->bullhorn_api->get_json(
                                            $bh_token->restUrl . "entity/JobSubmission", json_encode($data),
                                            array("Content-Type: application/json", "BHRestToken: " . $bh_token->BhRestToken),
                                            "PUT"
                                        ); // Connect candidate with Job Order
                                        if ($bh_application && $bh_application->changedEntityId) {
                                            $application_id = $bh_application->changedEntityId;
                                        } else {
                                            print_data("Application #" . $application->id . ' error: ' . var_export($bh_application, TRUE));
                                        }
                                    }
                                }
                            }

                            if ($bh_candidate_id || $application_id) {
                                // todo check it
                                if (BullhornModel::update_row('cv_library',
                                    array('id' => $application->id),
                                    array(
                                        'bh_candidate_id' => $bh_candidate_id,
                                        'application_id' => $application_id,
                                        'bh_notes' => NULL,
                                    )))
                                {
                                    print_data("Application #" . $application->id . ' uploaded.');
                                } else {
                                    print_data("Application #" . $application->id . ' upload FAILED.');
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '<a href="' . url('bullhorn/cronjob') . '">Continue</a>';
        exit;
    }

    public function cronjobAction()
    {
        ini_set('default_socket_timeout', -1);
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);
        ini_set('mysql.connect_timeout', -1);
        ini_set('mysql.connect_timeout', 600);
        ini_set('default_socket_timeout', 600);
        set_time_limit(600);

        print_data("Starting synchronization...");

        $access_token = BullhornModel::get_row(array(), 'created', 'DESC');

        if ($access_token) {
            print_data("Token loaded, expires in " . (((int)$access_token->created + (int)$access_token->expires) - time()) . " seconds");
//            if (((int)$access_token->created + (int)$access_token->expires) < (int)time()) {
            print_data('Token expired');
            $this->refresh_token($access_token);
            $access_token = BullhornModel::get_row(array(), 'created', 'DESC');
//            }

            $bh_token = $this->bullhorn_api->get_login($access_token->access_token);
            if (isset($bh_token->errorCode) && $bh_token->errorCode == 400) {
                print_data($bh_token->errorMessage);
                $this->refresh_token($access_token);
                $access_token = BullhornModel::get_row(array(), 'created', 'DESC');
                $bh_token = $this->bullhorn_api->get_login($access_token->access_token);
            }

            if (isset($bh_token->BhRestToken) && isset($bh_token->restUrl)) {

                Model::delete('bullhorn_integration', "`id` NOT IN (SELECT `id` FROM (SELECT `id` FROM `bullhorn_integration` ORDER BY `id` DESC LIMIT 10) `b`)");
                print_data('API credentials received');
                print_data('Loading vacancies');

                $vacancies = array();
                $is_all_loaded = FALSE;
                $bh_count = 45;
                $bh_start = 0;

                while (!$is_all_loaded) {
                    //CustomText6 = isPublic
                    $params = array(
                        "BhRestToken" => $bh_token->BhRestToken,
                        "orderBy" => "id",
                        "count" => $bh_count,
                        "start" => $bh_start,
                        "fields" => "id,title,employmentType,salary,payRate,isPublic,owner,clientContact,address,dateAdded,customText6,customText5,customFloat1,publicDescription", // TODO ! In production instead of asterisk use comma-separated list of required fields, otherwise one-to-many fields will be ignored!
                        "where" => "isOpen=true and isDeleted=false and customText6='1'" // TODO ! SQL-like query to filter not needed data! CHECK BEFORE RUN!
                    );


                    $bh_vacancies = $this->bullhorn_api->get_json($bh_token->restUrl . "query/JobOrder" . "?" . http_build_query($params));
                    if (isset($bh_vacancies->data) && is_array($bh_vacancies->data) && count($bh_vacancies->data) > 0) {
                        print_data("Loaded " . count($bh_vacancies->data) . ", processing");
                        $vacancies = array_merge($vacancies, $bh_vacancies->data);
                        $bh_start += $bh_count;
                    } else {
                        $is_all_loaded = TRUE;
                    }
                }

                //todo only dev mode!!!
                File::write(File::mkdir('data/integrations/bullhorn/') . 'data_' . date('Y_m_d_h_i') . '.txt', json_encode($vacancies));
                print_data($vacancies);

                if (count($vacancies) > 0) {
                    print_data("Loaded " . count($vacancies) . " vacancies in total, saving to database");

                    Model::import('panel/vacancies');
                    Model::import('panel/team');
                    Model::import('panel/vacancies/locations');
                    Model::import('panel/vacancies/sectors');
                    $remote_vacancies_ids = array();

                    foreach ($vacancies as $vacancy) {
//                        if (intval($vacancy->isPublic) != 1) continue;

                        $ref = $vacancy->id;
                        $remote_vacancies_ids[] = $ref;

                        $consultant_id = 1;
                        if (isset($vacancy->owner) && isset($vacancy->owner->firstName) && $vacancy->owner->firstName && isset($vacancy->owner->lastName) && $vacancy->owner->lastName) {
                            $consultant = Model::getRow('users', [
                                'firstname' => $vacancy->owner->firstName,
                                'lastname' => $vacancy->owner->lastName
                            ]);

                            if ($consultant)
                                $consultant_id = $consultant->id;
                            //create new consultant
//                            else {
//                                $consultant = Model::insert('users', [
//                                    'firstname' => $vacancy->owner->firstName,
//                                    'lastname' => $vacancy->owner->lastName,
//                                    'password' => md5('password'),
//                                    'role'     => 'moder'
//                                ]);
//                            }
                        }
                        //checking vacancy exist
                        $vacCount = Model::count('vacancies', '*', "`ref` = '$ref'");
                        if ($vacCount) {
                            $db_vacancy = Model::getRow('vacancies', array('ref' => $ref));
                            $vacancy_id = $db_vacancy->id;
                        }

                        $contract_type = 'permanent';

                        switch ($vacancy->employmentType) { // TODO ! Append this list if required
                            case 'Contract':
                            case 'Contract To Hire':
                                $contract_type = 'fixed';
                                break;
                        }

                        $salary_type = 'salary';
                        $salary_value = numberFormatInStr($vacancy->customFloat1);
//                            if ($vacancy->salary > 0)
//                                $salary_value = number_format($vacancy->salary);
//                            elseif ($vacancy->salary == 0 && $vacancy->payRate)
//                                $salary_value = number_format($vacancy->payRate);
//                            else
//                                $salary_value = 'Negotiable';
//                            switch ($vacancy->salaryUnit) {  // TODO ! Append this list if required
//                                case 'Per Day':
//                                    $salary_type = 'daily';
//                                    if ($vacancy->salary > 0 || ($vacancy->salary == 0 && $vacancy->payRate))
//                                        $salary_value .= " Per Day";
//                                    break;
//                                case 'Per Hour':
//                                    $salary_type = 'hourly';
//                                    if ($vacancy->salary > 0 || ($vacancy->salary == 0 && $vacancy->payRate))
//                                        $salary_value .= " Per Hour";
//                                    break;
//                                case 'Salary':
//                                    $salary_type = 'salary';
//                                    if ($vacancy->salary > 0 || ($vacancy->salary == 0 && $vacancy->payRate))
//                                        $salary_value .= " Per Annum";
//                                    break;
//                            }

                        //check vacancy slug
                        $slug = makeSlug($vacancy->title);
                        if ($vacCount)
                            $where = "`id` != '" . $vacancy_id . "' AND `slug` = '$slug'";
                        else
                            $where = "`slug` = '$slug'";

                        $check_slug = Model::count('vacancies', '*', $where);
                        if ($check_slug > 0)
                            $slug = makeSlug($vacancy->title . '-' . $vacancy->id);


                        $vacancy_data = array(
                            'consultant_id' => $consultant_id,
                            'title' => filter($vacancy->title),
                            'ref' => $ref,
                            'contract_type' => $contract_type, //?
//                                'salary_type' => $salary_type, //?
                            'salary_value' => $salary_value, // todo ???
//                                'salary' => $salary_value,
                            'content_short' => filter(mb_substr(strip_tags($vacancy->publicDescription), 0, 250)),
                            'content' => filter($vacancy->publicDescription),
                            'client_name' => filter($vacancy->clientContact->firstName . ' ' . $vacancy->clientContact->lastName), //?
                            'client_email' => NULL, //?
                            'postcode' => $vacancy->address->zip ? $vacancy->address->zip : NULL,
                            'meta_title' => filter($vacancy->title),
                            'meta_desc' => filter($vacancy->title),
                            'time' => $vacancy->dateAdded / 1000,
                            'time_expire' => 0,
                            'slug' => $slug,
                        ); // todo::: check fields and those types

                        if ($vacCount) {
                            $updateResult = Model::updateRow('vacancies', $vacancy_data, ['id' => $vacancy_id]);
                        } else {
                            $insertResult = Model::insert('vacancies', $vacancy_data);
                            $vacancy_id = Model::insertID();
                        }

                        if ($updateResult || (!$insertResult && $vacancy_id)) {
//                                // Sectors
//                                Model::delete(
//                                    'vacancies_sectors',
//                                    "`vacancy_id` = '" . ($vacancy_id) . "'"
//                                );
//
//                                if ($vacancy->categories->total > 0) {
//                                    foreach ($vacancy->categories->data as $category) { // TODO ! Check categories!
//                                        if (Model::countRows('sectors', ['name' => trim($category->name)])) {
//                                            $sector = Model::getRow('sectors', ['name' => trim($category->name)]);
//                                            Model::insert('vacancies_sectors', ['vacancy_id' => $vacancy_id, 'sector_id' => $sector->id]);
//                                        } else {
//                                            $res = Model::insert('sectors', ['name' => trim($category->name)]); // Insert row
//                                            $insertID = Model::insertID();
//
//                                            if (!$res && $insertID) {
//                                                Model::insert('vacancies_sectors', ['vacancy_id' => $vacancy_id, 'sector_id' => $insertID]);
//                                            }
//                                        }
//                                    }
//                                }

                            // Locations
                            Model::delete(
                                'vacancies_locations',
                                "`vacancy_id` = '" . ($vacancy_id) . "'"
                            );
//                                $this->vacancies_model->locations_delete(array('vacancy_id' => $db_vacancy->vacancy_id));
                            if ($vacancy->customText5) {
                                $location_name = trim($vacancy->customText5);

                                if ($location_name) {
                                    if (Model::countRows('locations', array('name' => $location_name))) {
                                        $location = Model::getRow('locations', ['name' => $location_name]);
                                        Model::insert('vacancies_locations', ['vacancy_id' => $vacancy_id, 'location_id' => $location->id]); // Insert row
                                    } else {
                                        $res = Model::insert('locations', ['name' => $location_name]); // Insert row
                                        $insertID = Model::insertID();
                                        if (!$res && $insertID) {
                                            Model::insert('vacancies_locations', ['vacancy_id' => $vacancy_id, 'location_id' => $insertID]); // Insert row
                                        }
                                    }
                                }
                            }
                        } else {
                            print_data("Database error on updating vacancy " . $ref);
                        }

                    }


                    $count_delete = Model::count(
                        'vacancies',
                        '*',
                        "`ref` NOT IN ('" . implode("','", $remote_vacancies_ids) . "') AND `deleted` = 'no' AND `no_remove` = 'no'"
                    );

                    if ($count_delete > 0) {
                        print_data("Found " . $count_delete . " old vacancies, removing...");
//                        echo "Found " . $count_delete . " old vacancies, removing..." . "\n";

                        Model::update(
                            'vacancies',
                            ['deleted' => 'yes'],
                            "`vacancies`.`ref` NOT IN ('" . implode("','", $remote_vacancies_ids) . "') AND `deleted` = 'no' AND `no_remove` = 'no'"
                        );
                    }

                    print_data('Finished');
                } elseif (isset($vacancies) && is_array($vacancies) && count($vacancies) === 0) {
                    print_data("Loaded 0 vacancies, nothing to save");
                } else {
                    print_data("Loading vacancies error. " . var_export($vacancies, TRUE) . " Closing application");
                }
            } else {
                print_data("Loging error. " . var_export($bh_token, TRUE) . " Closing application");
            }
        }

        exit;
    }

    /**
     * @param $fileName
     * @param $filePath
     * @param $bh_token
     * @param $entityId
     * @param string $entityType default Candidate or JobOrder for Job
     * @return string
     */
    private function putFile($fileName, $filePath, $bh_token, $entityId, $entityType = 'Candidate')
    {
        if (file_exists($filePath)) {
            $path_parts = pathinfo($filePath);

            $bh_data = array(
                "externalID" => "CV",
                "fileContent" => base64_encode(file_get_contents($filePath)),
                "fileType" => "SAMPLE",
                "name" => $fileName . " CV." . $path_parts['extension'],
                "contentType" => mime_content_type($filePath),
                "description" => $fileName . " CV",
                "type" => "CV"
            );
            $file_submission = $this->bullhorn_api->get_json(
                $bh_token->restUrl . "file/" . $entityType . "/" . $entityId,
                json_encode($bh_data),
                array("Content-Type: application/json", "BHRestToken: " . $bh_token->BhRestToken),
                "PUT"
            );
            if ($file_submission && isset($file_submission->fileId))
                return 'Added File with ID:' . $file_submission->fileId;
            else
                return 'Error';
        } else
            return 'No File';
    }

    private function parseFile($cv_path)
    {
        $path_parts = pathinfo($cv_path);

        if (strtolower($path_parts['extension'] == 'docx')) {
            $content = '';
            $zip = zip_open($cv_path);
            if (!$zip) return false;

            while ($zip_entry = zip_read($zip)) {
                $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                zip_entry_close($zip_entry);
            }

            zip_close($zip);

            $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
            $content = str_replace('</w:r></w:p>', "\r\n", $content);
            $outtext = strip_tags($content);

            return $outtext;

        } else if (strtolower($path_parts['extension'] == 'doc')) {
            $fileHandle = fopen($cv_path, "r");
            $line = @fread($fileHandle, filesize($cv_path));
            $lines = explode(chr(0x0D), $line);
            $outtext = "";
            foreach ($lines as $thisline) {
                $pos = strpos($thisline, chr(0x00));
                if (($pos !== FALSE) || (strlen($thisline) == 0)) {
                } else {
                    $outtext .= $thisline . " ";
                }
            }
            $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
            return $outtext;
        } else if (strtolower($path_parts['extension'] == 'pdf')) {

            if (file_exists($cv_path)) {
                try {
                    require_once(_SYSDIR_ . 'system/lib/PdfParser/vendor/autoload.php');
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($cv_path);
                    if ($pdf) {
                        $outtext = $pdf->getText();
                    } else {
                        $outtext = '';
                    }

                    return $outtext;
                } catch (Throwable $e) {
                    echo "Captured Throwable: " . $e->getMessage() . PHP_EOL;
                }
            } else {
                echo 'no file<br>';
            }
        }

    }
}
/* End of file */
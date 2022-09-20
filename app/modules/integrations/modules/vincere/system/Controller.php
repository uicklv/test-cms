<?php

class VincereController extends Controller
{
    protected $vincere_api_key      = "c363e175ec23ed665ff8aec5081a97db"; // API key - MANDATORY
    protected $vincere_client_id    = "81d448da-12c1-4e04-a4e1-484224b2e07f"; // API Client ID - MANDATORY
    protected $vincere_domain       = "amsourcetechnology"; // Domain name without .vincere.io - MANDATORY
    protected $vincere_login        = "andrew@amsourcetechnology.com"; // OPTIONAL
    protected $vincere_password     = "Amsource_123"; // OPTIONAL

    // redirect_uri = https://bolddev7.co.uk/energize/vincere

    public function indexAction()
    {
        if (!get('code')) {
            $params = array(
                "client_id" => $this->vincere_client_id,
                "state" => substr(md5(time()), 0, 10),
                "redirect_uri" => url('vincere'),
                "response_type" => "code"
            );

            redirect("https://id.vincere.io/oauth2/authorize?" . http_build_query($params));
        } else {
            $params = array(
                "client_id" => $this->vincere_client_id,
                "code" => get('code'),
                "grant_type" => "authorization_code"
            );

            $access_token = get_contents('https://id.vincere.io/oauth2/token', NULL, $params);
            if ($access_token) {
                $json = json_decode($access_token);
                if ($json) {

                    $result = Model::insert('vincere_integration', array(
                        "access_token" => $json->access_token,
                        "refresh_token" => $json->refresh_token,
                        "id_token" => $json->id_token,
                        "expires_in" => $json->expires_in,
                        "time" => time()
                    ));
                    $insertID = Model::insertID();

                    if (!$result && $insertID)
                        echo "Website authorized, you can close this window";
                }
            }
            exit;
        }
    }

    public function cronjobAction()
    {
        header("Content-type:text/plain");

        $access_token = VincereModel::get([], "time", "DESC");

        if ($access_token && (time() > ($access_token->expires_in + $access_token->time))) {
            echo "Access to Remote API expired (" . date("Y-m-d H:i:s", ($access_token->expires_in + $access_token->time)) . "), trying to refresh token." . "\n";
            $params = array(
                "client_id" => $this->vincere_client_id,
                "grant_type" => "refresh_token",
                "refresh_token" => $access_token->refresh_token
            );

            $access_token_raw = get_contents("https://id.vincere.io/oauth2/token", NULL, $params);

            if ($access_token_raw) {
                $access_token_json = json_decode($access_token_raw);
                if ($access_token_json) {
                    echo "New access token received." . "\n";

                    $result = Model::insert('vincere_integration', array(
                        "access_token" => $access_token_json->access_token,
                        "refresh_token" => isset($access_token_json->refresh_token) && $access_token_json->refresh_token ? $access_token_json->refresh_token : $access_token->refresh_token,
                        "id_token" => $access_token_json->id_token,
                        "expires_in" => $access_token_json->expires_in,
                        "time" => time()
                    ));
                    $insertID = Model::insertID();

                    if (!$result && $insertID)
                        $access_token = VincereModel::get(array(), "time", "DESC");
                }
            }
        }

        if ($access_token && (time() < ($access_token->expires_in + $access_token->time))) {
            echo "Loading vacancies" . "\n";
            $vacancies = array();

            // INSTALLATION: fileds must be adjusted if required
            $matrix_vars = array(
                "fl" => implode(",", array(
                    "id",
                    "job_title",
                    //"fe",
                    "sfe",
                    "industry",
                    "public_description",
                    "job_summary",
                    "open_date",
                    "closed_date",
                    "created_date",
                    "pay_rate",
                    "formatted_pay_rate",
                    "salary_from",
                    "formatted_salary_from",
                    "salary_to",
                    "formatted_salary_to",
                    "contract_length",
                    "location",
                    "job_type",
                    "employment_type",
                    //"published_date",
                    //"description",
                    "contact",
                    "salary_type",
                    "pay_interval",
                    "monthly_pay_rate",
                    "formatted_monthly_pay_rate",
                    "monthly_salary_from",
                    "formatted_monthly_salary_from",
                    "monthly_salary_to",
                    "formatted_monthly_salary_to",
                    "currency",
                    "company",
                    "owners"
                )),
                "sort" => urlencode("closed_date desc")
            );
            $start = 0;
            $total = NULL;
            $attempts = 0;
            $limit = 100;

            do {
                //echo "Loading vacancies " . $start . ".." . ($start + $limit) . "\n";
                $vacancies_raw = get_contents(
                    "https://" . $this->vincere_domain . ".vincere.io/api/v2/job/search/"
                        . implode(
                            ";",
                            array_map(
                                function ($key, $value) {
                                    return $key . "=" . $value;
                                },
                                array_keys($matrix_vars),
                                $matrix_vars
                            )
                        ),
                    array( // Installation: important part here!
//                        "q" => "private_job:0#open_date:[" . date("Y-m-d", strtotime("- 30 day")) . " TO *]#",  // This row filtering only open jobs that was open within last 30 days
//                        "q" => "private_job:0#closed_date:[" . date("Y-m-d") . " TO *]#",                                 // This row filtering only open jobs that have closing date in future.
                        // One of option from above must be used depends on client's way of maintaining vacancies!
                        "start" => $start,
                        "limit" => $limit,
                    ),
                    NULL,
                    array(
                        "x-api-key:" . $this->vincere_api_key,
                        "id-token:" . $access_token->id_token,
                    )
                );


                if ($vacancies_raw) {
                    //todo only dev mode!!!
                    File::write(File::mkdir('data/integrations/vincere/') . 'data_' . date('Y_m_d_h_i') . '.txt', $vacancies_raw);

                    $vacancies_json = json_decode($vacancies_raw);

                    print_data($vacancies_json);
                    exit;

                    if ($vacancies_json && isset($vacancies_json->result) && isset($vacancies_json->result->total)) {
                        $total = $vacancies_json->result->total;
                        $vacancies = array_merge($vacancies, $vacancies_json->result->items);
                        $start += $limit;
                    } else {
                        $attempts++;
                    }
                } else {
                    $attempts++;
                }
            } while ($attempts < 5 && ($total === NULL || ($start < $total)));

            if (count($vacancies) > 0) {
                echo "Loaded " . count($vacancies) . " open vacancies in total" . "\n";

                Model::import('panel/vacancies');
                Model::import('panel/team');
                Model::import('panel/vacancies/locations');
                Model::import('panel/vacancies/sectors');
                $remote_vacancies_ids = array();

                foreach ($vacancies as $vacancy) {
                    if (!isset($vacancy->id) || !isset($vacancy->job_title))
                        continue;

                    $ref = $vacancy->id;
                    $remote_vacancies_ids[] = $ref;

//                    print_data($vacancy);
//                    print_data("--- --- ---");
//                    exit;

                    $consultant_id = 0;
                    // Way 1 to assign consultant - check contact object
                    /*
                    if (isset($vacancy->contact) && isset($vacancy->contact->firstName) && $vacancy->contact->firstName && isset($vacancy->contact->lastName) && $vacancy->contact->lastName) {
                        $consultant = TeamModel::get(array(
                            'firstname' => filter($vacancy->contact->firstName),
                            'lastname' => filter($vacancy->contact->lastName)
                        ));

                        if ($consultant) {
                            $consultant_id = $consultant->id;
                        } else {
                            // Create user
                            $userData = [
                                'email' => filter($vacancy->contact->email),
                                'role' => 'user',
                                'firstname' => filter($vacancy->contact->firstName),
                                'lastname' => filter($vacancy->contact->lastName),
                                'slug' => filter(makeSlug($vacancy->contact->firstName . ' ' . $vacancy->contact->lastName)),
                                'reg_time' => time(),
                                'last_time' => time()
                            ];
                            $res = Model::insert('users', $userData); // Insert row
                            $insertID = Model::insertID();

                            if (!$res && $insertID)
                                $consultant_id = $insertID;
                            //echo "Vacancy # " . $vacancy->id . " " . $vacancy->job_title . " no consultant found in local team. Consultant name: " . $vacancy->contact->firstName . " " . $vacancy->contact->lastName . "\n";
                        }
                    } else {
                        //echo "Vacancy # " . $vacancy->id . " " . $vacancy->job_title . " no consultant found in local team. Consultant name missing" . "\n";
                    }
                    */


                    // Way 2 to assign consultant - check owner object
                    if (isset($vacancy->owners) && is_array($vacancy->owners) && count($vacancy->owners) > 0) {
                        foreach ($vacancy->owners as $owner) {
                            list($owner_firstname, $owner_lastname) = explode(" ", $owner->name, 2);
                            $consultant = TeamModel::get(array(
                                'firstname' => filter($owner_firstname),
                                'lastname' => filter($owner_lastname)
                            ));

                            if ($consultant) {
                                $consultant_id = $consultant->id;
                                break;
                            } else {
                                // Create user
                                $userData = [
                                    'email' => filter($owner->email),
                                    'role' => 'user',
                                    'firstname' => filter($owner_firstname),
                                    'lastname' => filter($owner_lastname),
                                    'slug' => filter(makeSlug($owner_firstname . ' ' . $owner_lastname)),
                                    'reg_time' => time(),
                                    'last_time' => time()
                                ];
                                $res = Model::insert('users', $userData); // Insert row
                                $insertID = Model::insertID();

                                if (!$res && $insertID)
                                    $consultant_id = $insertID;
                                break;
                            }
                        }
                    }
                    /**/

                    // Installation: Proper way from above must be applied to assign consultant. Most of systems not allow publishing vacancies without consultants!
                    if (!$consultant_id) {
                        echo "Vacancy #" . $vacancy->id . " " . $vacancy->job_title . " have no valid owner: " . (isset($vacancy->owners) ? var_export($vacancy->owners, TRUE) : "No data") . "\n";
                    }

                    $contract_type = 'permanent';
                    if (isset($vacancy->job_type)) {
                        switch ($vacancy->job_type) { // Installation: Raw API data must be checked for more options
                            case 'CONTRACT':
                                $contract_type = 'contract';
                                break;
                            case 'PERMANENT':
                                $contract_type = 'permanent';
                                break;
                        }
                    }

                    $salary_type = 'salary';
                    $salary_value = 'Negotiable';
                    if (isset($vacancy->salary_type)) {
                        switch ($vacancy->salary_type) { // Installation: Raw API data must be checked for more options
                            case "DAILY":
                                $salary_type = 'daily';
                                break;
                            case "HOURLY":
                                $salary_type = 'hourly';
                                break;
                            case "ANNUAL":
                            default:
                                $salary_type = 'salary';
                                if ((isset($vacancy->salary_from) && floatval($vacancy->salary_from) > 0) || (isset($vacancy->salary_to) && floatval($vacancy->salary_to) > 0)) {
                                    $salary_parts = array();

                                    if (isset($vacancy->salary_from) && floatval($vacancy->salary_from) > 0) {
                                        $salary_parts[] = $vacancy->salary_from;
                                    }

                                    if (isset($vacancy->salary_to) && floatval($vacancy->salary_to) > 0) {
                                        $salary_parts[] = $vacancy->salary_to;
                                    }

                                    if (count($salary_parts) > 0)
                                        $salary_value = implode(" - ", $salary_parts);
                                } elseif (isset($vacancy->pay_rate) && $vacancy->pay_rate) {
                                    $salary_value = $vacancy->pay_rate;
                                }
                        }
                    }


                    $slug = makeSlug($vacancy->job_title);

                    $vacancy_data = array(
                        'title'         => filter($vacancy->job_title),
                        'ref'           => $ref,
                        'contract_type' => $contract_type,
//                        'contract_length' => isset($vacancy->contract_length) ? $vacancy->contract_length : NULL,
//                        'salary_type' => $salary_type,
                        'salary_value'  => filter($salary_value),
                        'content'       => isset($vacancy->public_description) ? filter($vacancy->public_description) : '',
                        'content_short' => isset($vacancy->public_description) ? filter(mb_substr($vacancy->public_description, 0, 250)) : '',
                        'consultant_id' => $consultant_id,
//                        'client_name' => isset($vacancy->contact) ? $vacancy->contact->firstName . ' ' . $vacancy->contact->lastName : NULL,
//                        'client_email' => isset($vacancy->contact) && isset($vacancy->contact->email) ? $vacancy->contact->email : NULL,
//                        'postcode' => isset($vacancy->location) && isset($vacancy->location->post_code) && $vacancy->location->post_code ? $vacancy->location->post_code : NULL,
                        'meta_title'    => filter($vacancy->job_title),
                        'meta_desc'     => filter($vacancy->job_title),
                        'time'          => strtotime($vacancy->open_date),
                        'deleted'       => 'no',
                        'slug'          => filter($slug)
                    );



                    if (Model::count('vacancies', '*', "`ref` = '$ref'")) {
                        $db_vacancy = Model::getRow('vacancies', array('ref' => $ref));

                        $check_slug = Model::count('vacancies', '*', "`vacancy_id` != '" . ($db_vacancy->id) . "' AND `slug` => '$slug'");
                        if ($check_slug > 0)
                            $vacancy_data['slug'] = makeSlug($vacancy->job_title) . '-' . $vacancy->id;

                        if ($db_vacancy->expiry_reason) {
                            echo "Vacancy ID " . $db_vacancy->id . " ignored. Expired: " . $db_vacancy->expiry_reason . "\n";
                            continue;
                        }

                        if (Model::updateRow('vacancies', $vacancy_data, array('vacancy_id' => $db_vacancy->id))) {
                            // Locations
                            $actual_locations = array();
                            $actual_sectors = array();
                            if (isset($vacancy->location->city) && $vacancy->location->city) {
                                $location_name = trim($vacancy->location->city);

                                if ( Model::countRows('locations', array('name' => $location_name)) ) {
                                    $location = Model::getRow('locations', ['name' => $location_name]);
                                    $actual_locations[] = $location->id;

                                    if ( Model::countRows('vacancies_locations', ['vacancy_id' => $db_vacancy->id, 'location_id' => $location->id]) ) {
                                        Model::insert('vacancies_locations', ['vacancy_id' => $db_vacancy->id, 'location_id' => $location->id]);
                                    }
                                } else {
                                    $res = Model::insert('locations', ['name' => $location_name]); // Insert row
                                    $insertID = Model::insertID();

                                    if (!$res && $insertID) {
                                        $actual_locations[] = $insertID;
                                        Model::insert('vacancies_locations', ['vacancy_id' => $db_vacancy->id, 'location_id' => $insertID]);
                                    }
                                }
                            }

                            // TODO::: ---
//                            print_data($vacancy->industry);

                            // Industry sectors
                            if (isset($vacancy->industry) && is_array($vacancy->industry) && count($vacancy->industry)) {
                                foreach ($vacancy->industry as $industry) {
                                    $sector_name = trim($industry->description);

                                    if ( Model::countRows('sectors', ['name' => $sector_name]) ) {
                                        $sector = Model::getRow('sectors', ['name' => $sector_name]);
                                        $actual_sectors[] = $sector->id;

                                        if ( !Model::countRows('vacancies_sectors', ['vacancy_id' => $db_vacancy->id, 'sector_id' => $sector->id]) ) {
                                            Model::insert('vacancies_sectors', ['vacancy_id' => $db_vacancy->id, 'sector_id' => $sector->id]);
                                        }
                                    } else {
                                        $res = Model::insert('sectors', ['name' => $sector_name]); // Insert row
                                        $insertID = Model::insertID();

                                        if (!$res && $insertID) {
                                            $actual_locations[] = $insertID;
                                            Model::insert('vacancies_sectors', ['vacancy_id' => $db_vacancy->id, 'sector_id' => $insertID]);
                                        }
                                    }
                                }
                            }

                            Model::delete(
                                'vacancies_locations',
                                "`vacancy_id` = '" . ($db_vacancy->id) . "' AND NOT FIND_IN_SET(`location_id`, '" . implode("', '", $actual_locations) . "')"
                            );
                            Model::delete(
                                'vacancies_sectors',
                                "`vacancy_id` = '" . ($db_vacancy->id) . "' AND NOT FIND_IN_SET(`sector_id`, '" . implode("', '", $actual_sectors) . "')"
                            );
                        } else {
                            echo "Database error on updating vacancy " . $ref . "\n";
                        }
                    } else {
                        $check_slug = Model::countRows('vacancies', array('slug' => $slug));
                        if ($check_slug > 0) {
                            $vacancy_data['slug'] = makeSlug($vacancy->job_title) . '-' . $vacancy->id;
                        }

                        $res = Model::insert('vacancies', $vacancy_data); // Insert row
                        $insertID = Model::insertID();

                        if (!$res && $insertID) {
                            $vacancy_id = $insertID;

                            if (isset($vacancy->location->city) && $vacancy->location->city) {
                                $location_name = trim($vacancy->location->city);

                                if ( Model::countRows('locations', array('name' => $location_name)) ) {
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

                            if (isset($vacancy->industry) && is_array($vacancy->industry) && count($vacancy->industry)) {
                                foreach ($vacancy->industry as $industry) {
                                    $sector_name = trim($industry->description);

                                    if (Model::countRows('sectors', ['name' => $sector_name])) {
                                        $sector = Model::getRow('sectors', ['name' => $sector_name]);
                                        Model::insert('vacancies_sectors', ['vacancy_id' => $vacancy_id, 'sector_id' => $sector->id]);
                                    } else {
                                        $res = Model::insert('sectors', ['name' => $sector_name]); // Insert row
                                        $insertID = Model::insertID();

                                        if (!$res && $insertID) {
                                            Model::insert('vacancies_sectors', ['vacancy_id' => $vacancy_id, 'sector_id' => $insertID]);
                                        }
                                    }
                                }
                            }
                        } else {
                            echo "Database error on adding vacancy " . $ref . "\n";
                        }
                    }
                }

                $count_delete = Model::count(
                    'vacancies',
                    "`vacancies`.`ref` NOT IN ('" . implode("','", $remote_vacancies_ids) . "')"
                );

                if ($count_delete > 0) {
                    echo "Found " . $count_delete . " old vacancies, removing..." . "\n";

                    Model::update(
                        'vacancies',
                        ['deleted' => 'yes'],
                        "`vacancies`.`ref` NOT IN ('" . implode("','", $remote_vacancies_ids) . "')"
                    );
                }

                echo "Finished" . "\n";
            }
        } else {
            echo "Access to Remote API expired (" . date("Y-m-d H:i:s", ($access_token->expires_in + $access_token->time)) . "), please refresh authorization." . "\n";
        }
        exit;
    }
}
/* End of file */
<?php

class JobsController extends Controller
{
    use Validator;

    public function indexAction()
    {
        $data = $this->handlerSession();

        $keywords = $this->view->keywords = $data['keywords'] ?: post('keywords');
        $type     = $this->view->type = $data['type'] ?: post('type');
        $sector   = $this->view->sector = $data['sector'] ?: post('sector');
        $location = $this->view->location = $data['location'] ?: post('location');

        $this->view->list = JobsModel::search($keywords, $type, $sector, $location, 30);
        $this->view->sectors = JobsModel::getSectors();
        $this->view->locations = JobsModel::getAllWithVacancy();

        // Tech stack icons
        Model::import('panel/vacancies/tech_stack');
        $this->view->tech_list = Tech_stackModel::getArrayWithID();

        Request::setTitle('Search Jobs');
        Request::setKeywords('');
        Request::setDescription('');
    }

    public function viewAction()
    {
        $slug = Request::getUri(0);
        $this->view->job = JobsModel::get($slug);

        if (!$this->view->job || (!User::get('id') && $this->view->job->posted == 'no'))
            redirect(url('jobs'));

        $this->view->consultant = JobsModel::getUser($this->view->job->consultant_id);

        // Set stat
        if (setViewStat('vacancy_', $this->view->job->id, 'vacancies_analytics')) {
            $data_views['views'] = '++';
            Model::update('vacancies', $data_views, "`id` = '" . $this->view->job->id . "'");
        }

        Request::setTitle($this->view->job->meta_title);
        Request::setDescription($this->view->job->meta_desc);
        Request::setKeywords($this->view->job->meta_keywords);
    }

    public function searchAction()
    {
        Request::ajaxPart();

        $keywords = setSession('jobs_keywords', post('keywords'));
        $type = setSession('jobs_type', post('type'));
        $sector = setSession('jobs_sector_id', post('sector'));
        $location = setSession('jobs_location_id', post('location'));

        // Tech stack icons
        Model::import('panel/vacancies/tech_stack');
        $this->view->tech_list = Tech_stackModel::getArrayWithID();

        $this->view->list = JobsModel::search($keywords, $type, $sector, $location, 30);

        Request::addResponse('html', '#search_results_box', $this->getView());
    }

    public function apply_nowAction()
    {
        Request::ajaxPart();

        $slug = Request::getUri(0);
        $this->view->job = JobsModel::get($slug);

        if (!$this->view->job)
            redirect(url('jobs'));

        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email', 'Email', 'required|trim|email');
            $this->validatePost('tel', 'Contact Number', 'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('linkedin', 'LinkedIn', 'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('message', 'Further information', 'trim|min_length[1]|max_length[100]');
            $this->validatePost('cv_field', 'CV', 'required|trim|min_length[0]|max_length[100]|is_file');
            $this->validatePost('check', 'Agree', 'required|trim|min_length[0]|max_length[100]');

            if (Request::getParam('recaptcha_status'))
                $this->validatePost('g-recaptcha-response', 'reCAPTCHA', 'required');

            if ($this->isValid()) {
                if (Request::getParam('recaptcha_status')) {
                    // check if reCaptcha has been validated by Google
//                    V2
//                    $responseCaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . Request::getParam('recaptcha_key') . '&response=' . post('g-recaptcha-response')));
//                    if (!$responseCaptcha->success)
//                        Request::returnError('reCAPTCHA Error');

                    $checkCaptcha = checkCaptcha(Request::getParam('recaptcha_key'), post('g-recaptcha-response'));
                    if (!$checkCaptcha)
                        Request::returnError('reCaptcha Error');
                }


                if (strpos(post('cv_field'), '/app/data/cvs/') !== false) {
                    $cv = str_replace('/app/data/cvs/', '', post('cv_field'));
                } else {
                    $cv = post('cv_field');
                    if (!File::copy('data/tmp/' . post('cv_field'), 'data/cvs/' . post('cv_field'))) {
                        Request::returnError('CV file error');
                        //print_data(error_get_last());
                    }
                }

                $data = array(
                    'vacancy_id' => $this->view->job->id,
                    'candidate_id' => User::get('id', 'candidate') ?: 0,
                    'name' => post('name'),
                    'email' => post('email'),
                    'tel' => post('tel'),
                    'linkedin' => post('linkedin'),
                    'message' => post('message'),
                    'cv' => $cv,
                    'time' => time()
                );

                $consultant = JobsModel::getUser($this->view->job->consultant_id);
                $this->view->data = $data;

                //for Broadbean integration
                $adminEmail = $consultant->email;
                if ($this->view->job->app_email)
                    $adminEmail = $this->view->job->app_email;

                // Mail to client/consultant
                $mail = new Mail;
                $mail->initDefault('Vacancy Application', $this->getView('modules/jobs/views/email_templates/apply_now.php'));
                $mail->AddAddress($adminEmail);
                $mail->AddReplyTo(post('email'), post('name'));
                $mail->AddAttachment(_SYSDIR_ . 'data/cvs/' . $data['cv'], $data['name'] . '.' . File::format($data['cv']));
                $mail->sendEmail('apply_vacancy', $data['vacancy_id']);

                // Mail to candidate
                $mail2 = new Mail;
                $mail2->initDefault('Vacancy Application Received', $this->getView('modules/jobs/views/email_templates/apply_now_thanks.php'));
                $mail2->AddAddress($data['email']);
                $mail2->sendEmail('apply_thanks', $data['vacancy_id']);

                $result = Model::insert('cv_library', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('html', '#apply_form', '<h3 class="title-small">Thank you!</h3>');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    // Alerts

    public function unsubscribeAction()
    {
        $token = Request::getUri(0);
        $this->view->subs = JobsModel::getCandidateAlertByToken($token);

        if (!$this->view->subs) {
            redirect(url('/'));
        }

        Model::delete('candidate_alerts',"`token` = '$token'");
    }

    public function get_alertsAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('email',    'Email',     'required|trim|email');

            $keywords = post('keywords');
            $sector   = post('sector');
            $location = post('location');
            $type     = post('type');
            $email    = post('email');

            if (!$type && !$keywords && !$sector && !$location) {
                $this->addError('job_filters', 'Please enter your job alert criteria and your e-mail');
            }

            if ($this->isValid()) {
                $this->view->data = $data = [
                    'email'      => $email,
                    'keywords'   => $keywords,
                    'location'   => $location,
                    'type'       => $type,
                    'sector'     => $sector,
                    'token'      => generateRandomString(32),
                    'time'       => time(),
                ];

                $checkAlert = Model::fetch(Model::select('candidate_alerts', " `email` = '$email'"));

                if (!$checkAlert) {
                    Model::insert('candidate_alerts', $data);
                } else {
                    Model::update('candidate_alerts', $data, " `email` = '$email'");
                }

                // Mail to candidate
                $mail1 = new Mail;
                $mail1->initDefault('Job alerts', $this->getView('modules/jobs/views/email_templates/job_alerts_thanks.php'));
                $mail1->AddAddress($data['email']);
                $mail1->sendEmail('job_alerts_thanks');

                Request::addResponse('html', '#alert_block', '<h3 class="title">Thank you!</h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax()) {
                    Request::returnErrors($this->validationErrors);
                }
            }
        }
    }

    public function send_job_alertsAction()
    {
        //get all alerts
        $alerts = Model::fetchAll(Model::select('candidate_alerts'));

        //get all users emails
        $emails = [];

        foreach ($alerts as $alert) {
            $emails[] = $alert->email;
        }

        //get all sent alerts
        $sentAlerts = [];

        if ($emails) {
            $sentAlerts = Model::fetchAll(Model::select('sent_alerts', " `email` IN ('" . implode("','", $emails) . "')"));
        }

        //create array with job ids for each email
        $jobIds = [];

        if ($sentAlerts) {
            foreach ($sentAlerts as $alert) {
                $jobIds[$alert->email][] = $alert->job_id;
            }
        }

        //get all jobs
        $jobs = JobsModel::search();
        $alertsForSend = [];

        //filtering jobs for alerts
        foreach ($alerts as $alert) {
            $alertsForSend[$alert->email] = [];

            foreach ($jobs as $k => $job) {
                $pushJob = false;

                //continue if this job sent before for this user
                if ($jobIds[$alert->email]) {
                    if (in_array($job->id, $jobIds[$alert->email])) continue;
                }

                //keywords
                if ($alert->keywords) {
                    if (mb_strpos($job->title, $alert->keywords) &&
                        mb_strpos($job->ref, $alert->keywords) &&
                        mb_strpos($job->content, $alert->keywords)) {
                        $pushJob = true;
                    }
                }

                //by sector
                if ($alert->sector) {
                    $sector = $alert->sector;
                    $jobSectors = array_column($job->sectors, 'id');

                    if (in_array($sector, $jobSectors)) {
                        $pushJob = true;
                    }

                }


                //by location
                if ($alert->location) {
                    $location = $alert->location;
                    $jobLocations = array_column($job->locations, 'id');

                    //if city
                    if (in_array($location, $jobLocations)) {
                        $pushJob = true;
                    }

                }

                if ($pushJob) {
                    $alertsForSend[$alert->email][] = $job;
                }
            }
        }

        $alertsForSend = array_filter($alertsForSend);

        if (count($alertsForSend) > 0) {
            //send emails
            foreach ($alertsForSend as $k => $alert) {
                $this->view->jobs = $alert;
                $this->view->token = JobsModel::getCandidateAlertByEmail($k)->token;
                // Mail to candidate
                $mail = new Mail;
                $mail->initDefault('Job Alerts', $this->getView('modules/jobs/views/email_templates/job_alerts.php'));
                $mail->AddAddress($k);
                $mail->sendEmail('contact_thanks');

                foreach ($alert as $job) {
                    Model::insert('sent_alerts', ['job_id' => $job->id, 'email' => $k, 'time' => time()]);
                }
            }
        }

        exit;
    }

    // End Alerts

    public function latest_rolesAction()
    {
        Request::ajaxPart();

        // Tech stack icons
        Model::import('panel/vacancies/tech_stack');
        $this->view->tech_list = Tech_stackModel::getArrayWithID();

        $where = false;
        if (post('internal')) {
            $where = "`internal` = '1'";
            $this->view->internal = true;
        }

        $this->view->list = JobsModel::search(false, false, false, false, 9, $where);

        Request::addResponse('html', '.roles-slider', $this->getView());
        Request::addResponse('func', 'latestRolesSlider', '.roles-slider');
    }

    // Addition functionality

    public function apply_linkedinAction()
    {
        $slug = Request::getUri(0);
        $this->view->job = JobsModel::get($slug);

        if (!$this->view->job)
            redirect(url('jobs'));

        include _SYSDIR_ . 'system/lib/hybrid/src/autoload.php';

        $config = [
            'callback' => 'http://cms-admin.loc/jobs/apply_linkedin',
            'keys' => [
                'id' => '78lnf9s47fe4vm',
                'secret' => '60RWPq8HfJzDA2wS'
            ],
            'scope' => 'r_liteprofile r_emailaddress',
        ];

        $adapter = new Hybridauth\Provider\LinkedIn($config);

        $adapter->authenticate();
        $userProfile = $adapter->getUserProfile();

        if ($userProfile) {

            $data = array(
                'vacancy_id' => $this->view->job->id,
                'name' => $userProfile->firstName . ' ' . $userProfile->lastName,
                'email' => $userProfile->email,
                'linkedin' => $userProfile->url,  //todo check url field
                'time' => time()
            );

            $consultant = JobsModel::getUser($this->view->job->consultant_id);
            $this->view->data = $data;

            // Mail to client/consultant
            $mail = new Mail;
            $mail->initDefault('Vacancy Application', $this->getView('modules/jobs/views/email_templates/apply_now.php'));
            $mail->AddAddress($consultant->email);
            $mail->AddAttachment(_SYSDIR_ . 'data/cvs/' . $data['cv'], $data['name'] . '.' . File::format($data['cv']));
            $mail->sendEmail('vacancy', $data['vacancy_id']);

            $mail_client = new Mail;
            $mail_client->initDefault('Vacancy Application', $this->getView('modules/jobs/views/email_templates/apply_now_thanks.php'));
            $mail_client->AddAddress($data['email']);
            $mail_client->sendEmail('vacancy_thanks', $data['vacancy_id']);

            //todo make the cv field optional
            $result = Model::insert('cv_library', $data); // Insert row
            $insertID = Model::insertID();

            if (!$result && $insertID) {
                //todo make alert
                Request::addResponse('redirect', false, url('job', $this->view->job->slug));
                Request::endAjax();
            } else {
                Request::returnError('Database error');
            }
        }

        Request::addResponse('redirect', false, url('job', $this->view->job->slug));
    }

    public function job_alertsAction()
    {
        Request::ajaxPart();

        Model::import('panel/vacancies/locations');
        Model::import('panel/vacancies/sectors');

        $this->view->locations = LocationsModel::getAll();
        $this->view->sectors = SectorsModel::getAll();

        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email', 'Email', 'required|trim|email');
            $this->validatePost('tel', 'Contact Number', 'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('location_ids', 'Locations', 'required|is_array');
            $this->validatePost('sector_ids', 'Sectors', 'required|is_array');
            $this->validatePost('check', 'Agree', 'required|trim|min_length[0]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'name' => post('name'),
                    'email' => post('email'),
                    'tel' => post('tel'),
                    'location' => '|' . implode('||', post('location_ids')) . '|',
                    'sectors' => '|' . implode('||', post('sector_ids')) . '|',
                    'time' => time()
                );

                $this->view->locationsForAlerts = [];
                $this->view->sectorsForAlerts = [];

                foreach (post('location_ids') as $id) {
                    $location = LocationsModel::get($id);
                    $this->view->locationsForAlerts[] = $location->name;
                }

                foreach (post('sector_ids') as $id) {
                    $sector = SectorsModel::get($id);
                    $this->view->sectorsForAlerts[] = $sector->name;
                }

                // Mail to client/consultant
                $mail = new Mail;
                $mail->initDefault('Job Alerts', $this->getView('modules/jobs/views/email_templates/job_alerts.php'));
                $mail->AddAddress(Request::getParam('admin_mail'));
                $mail->sendEmail('job alerts form');

                $result = Model::insert('subscribers', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('html', '#apply_form', '<h3 class="title-small">Thank you! </h3>');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function expire_alertsAction()
    {
        $vacancies = Model::fetchAll(Model::select('vacancies', " `time_expire` < '" . (time() - 3600 * 24) . "' 
        AND `time_expire` != 0 AND `expire_alert` = 'no' AND `deleted` = 'no'"));

        $consultans = Model::fetchAll(Model::select('users', " `role` IN ('moder','office_admin','multi_office_admin','admin')"));

        foreach ($consultans as $consultant) {

            $expiredVacancies = [];
            $vacanciesIds = [];
            foreach ($vacancies as $vacancy) {
                if ($consultant->id == $vacancy->consultant_id) {
                    $expiredVacancies[] = $vacancy;
                    $vacanciesIds[] = $vacancy->id;
                }

            }

            if (is_array($expiredVacancies) && count($expiredVacancies) > 0) {
                $this->view->vacancies = $expiredVacancies;
                // Send email to admin
                require_once(_SYSDIR_ . 'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress($consultant->email); //ADMIN_MAIL

                $mail->Subject = 'Vacancies Expired';
                $mail->Body = $this->getView('modules/jobs/views/email_templates/expire.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';

                $mail->Send();

                Model::update('vacancies', ['expire_alert' => 'yes'], " `id` IN(" . implode(',', $vacanciesIds) . ")");
            }
        }
        exit;
    }

    public function add_shortlistAction()
    {
        Request::ajaxPart();

        Model::import('panel/vacancies');
        $favorite = VacanciesModel::getFavorite(User::get('id'), post('job'));

        if (!$favorite) {
            $data = [
                'user_id' => User::get('id'),
                'job_id' => post('job'),
                'time' => time(),
            ];

            Model::insert('shortlist_jobs', $data);

            if (post('type') == 'view') {
                Request::addResponse('html', '#shortlist', "<a  
                  onclick='load(`jobs/add_shortlist`, `user=" . User::get('id') . "`, `job=" . post('job') . "`)'>Remove from Short-list</a>");
            } else {
                Request::addResponse('html', '#add_' . post('job'), "<a class='trashcan'  
                  onclick='load(`jobs/add_shortlist`, `user=" . User::get('id') . "`, `job=" . post('job') . "`)'><i class='fas fa-trash'>remove</i></a>");
            }
        } else {
            Model::delete('shortlist_jobs', "`user_id` = '" . User::get('id') . "' AND `job_id` = '" . post('job') . "'");

            if (post('type') == 'view') {
                Request::addResponse('html', '#shortlist', "<a  
                  onclick='load(`jobs/add_shortlist`, `user=" . User::get('id') . "`, `job=" . post('job') . "`)'>Short-list Job</a>");
            } else {
                Request::addResponse('html', '#add_' . post('job'), "<a class='plus'  
                  onclick='load(`jobs/add_shortlist`, `user=" . User::get('id') . "`, `job=" . post('job') . "`)'>add</a>");
            }

        }


    }

    public function saved_jobsAction()
    {
        $id = Request::getUri(0);
        $this->view->list = JobsModel::getFavorites($id);
//        if (!$this->view->list)
//            redirect(url('page/profile'));

        Request::setTitle('Saved Jobs');
    }

    /**
     * Search page with counters at the filters
     */
    public function index_2Action()
    {
        $keywords = post('keywords');
        $type = post('type');
        $sector = post('sector');
        $location = post('location');

        $this->view->list = JobsModel::search($keywords, $type, $sector, $location, 30);
        $this->view->sectors = JobsModel::getSectors();
        $this->view->locations = JobsModel::getLocations();


        // sectors counter
        foreach ($this->view->sectors as $item) {
            $item->counter = 0;
            foreach ($this->view->list as $job) {
                if (is_array($job->sectors) && count($job->sectors) > 0) {
                    foreach ($job->sectors as $s) {
                        if ($s->sector_id === $item->id)
                            $item->counter++;
                    }
                }
            }
        }

        // locations counter
        foreach ($this->view->locations as $item) {
            $item->counter = 0;
            foreach ($this->view->list as $job) {
                if (is_array($job->locations) && count($job->locations) > 0) {
                    foreach ($job->locations as $s) {
                        if ($s->location_id === $item->id)
                            $item->counter++;
                    }
                }
            }
        }

        //type counter
        $types = ['contract', 'permanent'];
        $this->view->types = [];
        foreach ($types as $item) {
            $this->view->types[$item] = 0;
            foreach ($this->view->list as $job) {
                if ($job->contract_type === $item)
                    $this->view->types[$item]++;
            }
        }


        // Tech stack icons
        Model::import('panel/vacancies/tech_stack');
        $this->view->tech_list = Tech_stackModel::getArrayWithID();

        Request::setTitle('Search Jobs');
        Request::setKeywords('');
        Request::setDescription('');
    }

    /**
     * Search request with counters at the filters
     */
    public function search_2Action()
    {
        Request::ajaxPart();

        $keywords = post('keywords');
        $type = post('type');
        $sector = post('sector');
        $location = post('location');

        // Tech stack icons
        Model::import('panel/vacancies/tech_stack');
        $this->view->tech_list = Tech_stackModel::getArrayWithID();
        $this->view->sectors = JobsModel::getSectors();
        $this->view->locations = JobsModel::getLocations();

        $this->view->list = JobsModel::search($keywords, $type, $sector, $location, 30);

        // sectors counter
        foreach ($this->view->sectors as $item) {
            $item->counter = 0;
            foreach ($this->view->list as $job) {
                if (is_array($job->sectors) && count($job->sectors) > 0) {
                    foreach ($job->sectors as $s) {
                        if ($s->sector_id === $item->id)
                            $item->counter++;
                    }
                }
            }
        }

        // locations counter
        foreach ($this->view->locations as $item) {
            $item->counter = 0;
            foreach ($this->view->list as $job) {
                if (is_array($job->locations) && count($job->locations) > 0) {
                    foreach ($job->locations as $l) {
                        if ($l->location_id === $item->id)
                            $item->counter++;
                    }
                }
            }
        }

        //type counter
        $types = ['contract', 'permanent'];
        $this->view->types = [];
        foreach ($types as $item) {
            $this->view->types[$item] = 0;
            foreach ($this->view->list as $job) {
                if ($job->contract_type === $item)
                    $this->view->types[$item]++;
            }
        }

        Request::addResponse('html', '#search_results_box', $this->getView());
    }

    public function expire_vacanciesAction()
    {
        $this->view->list = JobsModel::getExpiringVacancies();
    }

    private function handlerSession()
    {
        $data = [];
        if (strpos(getReferrerURI(), 'job') !== false) {
            $data['keywords'] = getSession('jobs_keywords') ?: '';
            $data['type']     = getSession('jobs_type') ?: '';
            $data['sector']   = getSession('jobs_sector_id') ?: '';
            $data['location'] = getSession('jobs_location_id') ?: '';
        } else {
            unset($_SESSION['jobs_keywords']);
            unset($_SESSION['jobs_type']);
            unset($_SESSION['jobs_sector_id']);
            unset($_SESSION['jobs_location_id']);
        }
        return $data;
    }
}
/* End of file */

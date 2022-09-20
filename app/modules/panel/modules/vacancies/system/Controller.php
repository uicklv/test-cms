<?php
class VacanciesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $id = intval(Request::getUri(0));
        if ($id) {
            Model::import('panel/microsites');
            $this->view->microsite = MicrositesModel::get($id);
        } else
            $id = false;

        $this->view->list = VacanciesModel::getAll($id, false, " AND (`time_expire` > '" . (time() - 180) . "' OR `time_expire` = 0)");

        Request::setTitle('Manage Vacancies');
    }

    public function addAction()
    {

        if ($this->startValidation()) {
            $this->validatePost('title',        'Job Title',            'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'title'         => post('title'),
                    'slug'          => Model::createIdentifier('vacancies', makeSlug(post('title'))),
                    'time_expire'   => time() + 24 * 3600 * 180,
                    'time'          => time(),
                );

                $result   = Model::insert('vacancies', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'vacancy#' . $insertID, 'time' => time()]);

                    Request::addResponse('redirect', false, url('panel', 'vacancies', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Vacancy');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = VacanciesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/vacancies'));

        Model::import('panel/vacancies/sectors');
        Model::import('panel/vacancies/locations');
        Model::import('panel/vacancies/tech_stack');
        Model::import('panel/team');
        Model::import('panel/microsites');
        Model::import('panel/candidates_portal');

        $this->view->sectors    = SectorsModel::getAll();
        $this->view->locations  = LocationsModel::getAll();
        $this->view->team       = TeamModel::getUsersWhere("`role` = 'customer'");
        $this->view->consultants = TeamModel::getUsersWhere("`role` IN ('moder', 'admin')");
        $this->view->microsites = MicrositesModel::getAll();
        $this->view->tech       = Tech_stackModel::getAll();
        $this->view->candidates = Candidates_portalModel::search(false, 6);
        $this->view->vacancy_candidates = VacanciesModel::getVacancyCandidates($this->view->edit->id);


        if ($this->startValidation()) {
            $this->validatePost('title',        'Job Title',            'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('ref',          'Job Ref',              'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('sector_ids',   'Industries/Sectors',   'is_array');
            $this->validatePost('location_ids', 'Locations',            'is_array');
            $this->validatePost('salary_value', 'Salary',               'trim|min_length[1]|max_length[100]');
            $this->validatePost('contract_type','Contract Type',        'trim|min_length[1]|max_length[50]');
            $this->validatePost('time',         'Date Posted',          'trim|min_length[1]|max_length[50]');
            $this->validatePost('time_expire',  'Date Expires',         'trim|min_length[1]|max_length[50]');
            $this->validatePost('package',      'Package',              'trim|min_length[1]|max_length[250]');
            $this->validatePost('postcode',     'Postcode',             'trim|min_length[1]|max_length[250]');
            $this->validatePost('content',      'Description',          'required|trim|min_length[1]');
            $this->validatePost('content_short','Short Description',    'required|trim|min_length[1]|max_length[250]');
            $this->validatePost('consultant_id','Consultant',           'required|trim|min_length[1]');
            $this->validatePost('microsite_id', 'Microsite',            'trim|min[0]');
            $this->validatePost('tech_stack',   'Tech Stack',           'required|trim|min_length[1]');
            $this->validatePost('meta_title',   'Meta Title',           'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_keywords','Meta Keywords',        'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_desc',    'Meta Description',     'trim|min_length[0]|max_length[200]');
            $this->validatePost('slug',         'Slug',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('image',        'Image',                'trim|min_length[1]|max_length[100]');

            // Times comparing/checking
            $intTime   = convertStringTimeToInt(post('time'));
            $checkTime = date("d/m/Y", $intTime);
            $intTimeExpire   = convertStringTimeToInt(post('time_expire'));
            $checkTimeExpire = date("d/m/Y", $intTimeExpire);

            if ($checkTime != post('time')) {
                $this->addError('time', 'Wrong Date Posted');
            } else if ($checkTimeExpire != post('time_expire')) {
                $this->addError('time_expire', 'Wrong Date Expires');
            }

            if ($this->isValid()) {
                $data = array(
                    'title'         => post('title'),
                    'test'         => post('test'),
                    'ref'           => Model::createIdentifier('vacancies', post('ref'), 'ref', $this->view->edit->id),
                    'salary_value'  => post('salary_value'),
                    'contract_type' => post('contract_type'),
                    'package'       => post('package'),
                    'postcode'      => post('postcode'),
                    'internal'      => intval(post('internal', 'int')),
                    'content'       => post('content'),
//                    'client_email'  => post('client_email'),
                    'content_short' => post('content_short'),
                    'consultant_id' => post('consultant_id'),
                    'microsite_id'  => post('microsite_id', true, 0),
                    'tech_stack'    => post('tech_stack'),
                    'meta_title'    => post('meta_title'),
                    'meta_keywords' => post('meta_keywords'),
                    'meta_desc'     => post('meta_desc'),
                    'slug'          => Model::createIdentifier('vacancies', post('slug'), 'slug', $this->view->edit->id),
                    'posted'        => post('posted') == 'yes' ? 'yes' : 'no',
                    'time_expire'   => $intTimeExpire,
                    'time'          => $intTime,
                    'image'         => post('image'),
                );

/*
                $oldslug = $slug = makeSlug(post('title'));
                $i = 2;
                Model::import('jobs');
                while ($vac = JobsModel::getNotThis($slug, $id)) {
                    $slug = $oldslug . '-' . $i;
                    $i ++;
                }
                $data['slug'] = $slug;

                $oldref = $ref = post('ref');
                $i = 2;
                Model::import('jobs');
                while ($vacancy = JobsModel::getNotThis($ref, $id)) {
                    $ref = $oldref . '-' . $i;
                    $i ++;
                }

                $data['ref'] = $ref;
*/

                // Copy and remove image
                if ($data['image']) {
                    if ($this->view->edit->image !== $data['image']) {
                        if (File::copy('data/tmp/' . $data['image'], 'data/vacancy/' . $data['image'])) {
                            File::remove('data/vacancy/' . $this->view->edit->image);
                        } else
                            print_data(error_get_last());
                    }
                }


                $result = Model::update('vacancies', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    // Log
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'edit', 'entity' => 'vacancy#' . $id, 'time' => time()]);

                    // Remove and after insert sectors
                    VacanciesModel::removeSectors($id);
                    if (is_array(post('sector_ids')) && count(post('sector_ids')) > 0) {
                        foreach (post('sector_ids') as $sector_id) {
                            Model::insert('vacancies_sectors', array(
                                'vacancy_id' => $id,
                                'sector_id' => $sector_id
                            ));
                        }
                    }

                    // Remove and after insert locations
                    VacanciesModel::removeLocations($id);
                    if (is_array(post('location_ids')) && count(post('location_ids')) > 0) {
                        foreach (post('location_ids') as $location_id) {
                            Model::insert('vacancies_locations', array(
                                'vacancy_id' => $id,
                                'location_id' => $location_id
                            ));
                        }
                    }

                    // Remove and after insert customers
                    VacanciesModel::removeCustomers($id);
                    if (is_array(post('customer_id')) && count(post('customer_id')) > 0) {
                        foreach (post('customer_id') as $customer_id) {
                            Model::insert('vacancies_customers', array(
                                'vacancy_id' => $id,
                                'customer_id' => $customer_id
                            ));

                            $email = Model::fetch(Model::select('vacancies_customers_email', " `vacancy_id` = '$id' 
                            AND `customer_id` = '$customer_id' LIMIT 1"));

                            if (!$email) {

                                Model::import('panel/team');
                                $customer = TeamModel::getUser($customer_id);
                                $this->view->customer = $customer;
                                // Mail to client/consultant
                                $mail = new Mail;
                                $mail->initDefault('New Vacancy', $this->getView('modules/panel/modules/vacancies/views/email_templates/new_vacancy.php'));
                                $mail->AddAddress($customer->email);
                                $mail->sendEmail('new_vacancy');

                                Model::insert('vacancies_customers_email', array(
                                    'vacancy_id' => $id,
                                    'customer_id' => $customer_id
                                ));
                            }

                        }
                    }


                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Vacancy');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = VacanciesModel::get($id);

        if (!$user)
            Request::returnError('Vacancy error');

        $data['deleted'] = 'yes';
        $result = Model::update('vacancies', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'vacancy#' . $id, 'time' => time()]);

            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function applicationsAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = VacanciesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/vacancy_applications'));

        $this->view->list = VacanciesModel::getAppWhere("`vacancy_id` = '$id'");

        Request::setTitle('Job Application List');
    }

    public function application_popupAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $this->view->apply = VacanciesModel::getApplication($id);

        if (!$this->view->apply)
            redirect(url('panel/vacancies/applications/' . post('vacancy_id')));

        if (!$this->view->apply->status) {
            Model::update('cv_library', ['status' => 'reviewed'], "`id` = '$id'");

            $el = applicationStatus('reviewed');
            $select = '<div class="fs-item ' . $el['class'] . '">' . $el['title'] . '</div>';
            Request::addResponse('html', '#status_text_' . $id, $select);
            Request::addResponse('html', '#popup_status', $select);
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function change_app_statusAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $status = $this->validatePost('status',   'status',   'required|trim|min_length[1]|max_length[15]');

            switch ($status) {
                case 'spoken':
                case 'interviewed':
                case 'shortlisted':
                case 'rejected':
                    break;

                default:
                    $status = 'reviewed';
            }

            if ($this->isValid()) {
                $id = intval(Request::getUri(0));

                $result = Model::update('cv_library', ['status' => $status], "`id` = '$id'"); // Update row

                if ($result) {
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::addResponse('func', 'closeStatusBlock');
                    $el = applicationStatus($status);
                    $select = '<div class="fs-item ' . $el['class'] . '">' . $el['title'] . '</div>';
                    Request::addResponse('html', '#status_text_' . $id, $select);
                    Request::addResponse('html', '#popup_status', $select);
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function add_candidateAction()
    {
        Request::ajaxPart();


        $vacancy_id = post('vacancy_id');
        $candidate_id = post('candidate_id');

        $check = VacanciesModel::getVacancyCandidate($vacancy_id, $candidate_id);

        if ($check){
            Request::returnError('This candidate has already been added');
        } else {

            Model::insert('vacancies_candidates', [
                'vacancy_id' => post('vacancy_id'),
                'candidate_id' => post('candidate_id')
            ]);

            Model::insert('candidates_status', [
                'vacancy_id' => post('vacancy_id'),
                'candidate_id' => post('candidate_id')
            ]);

            Model::import('panel/team');
            Model::import('panel/vacancies');
            $vacancy = VacanciesModel::get($vacancy_id);
            $customer = TeamModel::getUser($vacancy->consultant_id);
            $this->view->customer = $customer;
            $this->view->vacancy = $vacancy;

            if (is_array($vacancy->customers) && count($vacancy->customers) > 0) {
                foreach ($vacancy->customers as $item) {

                    $this->view->customer = $item;
                    $this->view->vacancy = $vacancy;

                    // Mail to client/consultant
                    $mail = new Mail;
                    $mail->initDefault('New Candidate', $this->getView('modules/panel/modules/vacancies/views/email_templates/new_candidate.php'));
                    $mail->AddAddress($item->email);
                    $mail->sendEmail('new_candidate');
                }
            }

            $this->view->vacancy_candidates = VacanciesModel::getVacancyCandidates($vacancy_id);
            $this->view->vacancy_id = $vacancy_id;
            Request::addResponse('html', '#candidates_box', $this->getView('modules/panel/modules/vacancies/views/get_candidates.php'));
        }
    }

    public function apply_deleteAction()
    {
        $id = (Request::getUri(0));
        $app = VacanciesModel::getApplication($id);

        if (!$app)
            redirect(url('panel/vacancies/applications/' . get('id')));

        $data['deleted'] = 'yes';
        $result = Model::update('cv_library', $data, "`id` = '$id'"); // Update row

        if ($result) {} else
            Request::returnError('Database error');

        redirectAny(url('panel/vacancies/applications/' . get('id')));
    }

    public function delete_candidateAction()
    {
        Request::ajaxPart();

        $vacancy_id = post('vacancy_id');
        $candidate_id = post('candidate_id');

        $check = VacanciesModel::getVacancyCandidate($vacancy_id, $candidate_id);

        if ($check){
            Model::delete('vacancies_candidates', " `vacancy_id` = '" . $vacancy_id . "' AND
             `candidate_id` = '" . $candidate_id . "'");

            Model::delete('candidates_status', " `vacancy_id` = '" . $vacancy_id . "' AND
             `candidate_id` = '" . $candidate_id . "'");

            $this->view->vacancy_candidates = VacanciesModel::getVacancyCandidates($vacancy_id);
            $this->view->vacancy_id = $vacancy_id;
            Request::addResponse('html', '#candidates_box', $this->getView('modules/panel/modules/vacancies/views/get_candidates.php'));
        } else {
            Request::returnError('Record does not exist');
        }
    }

    public function search_candidatesAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('candidate_name','Candidate Name', 'trim');

            if ($this->isValid()) {

                $candidate = post('candidate_name');

                Model::import('panel/candidates_portal');
                $this->view->candidates = Candidates_portalModel::search($candidate);
                $this->view->vacancy_id = post('vacancy_id');
                Request::addResponse('html', '#result_box', $this->getView());
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function duplicateAction()
    {
        Request::ajaxPart();
        $id = intval(Request::getUri(0));
        $vacancy = VacanciesModel::get($id);

        if (!$vacancy)
            redirect(url('panel/vacancies'));

        $data = [
            'ref'             => Model::createIdentifier('vacancies', $vacancy->ref, 'ref'),
            'title'           => $vacancy->title,
            'salary_value'    => $vacancy->salary_value,
            'contract_type'   => $vacancy->contract_type,
            'package'         => $vacancy->package,
            'postcode'        => $vacancy->postcode,
            'image'           => $vacancy->image,
            'content_short'   => $vacancy->content_short,
            'content'         => $vacancy->content,
            'consultant_id'   => $vacancy->consultant_id,
            'microsite_id'    => $vacancy->microsite_id,
            'time_expire'     => $vacancy->time_expire,
            'time'            => $vacancy->time,
            'slug'            => Model::createIdentifier('vacancies', makeSlug($vacancy->title)),
        ];

        $result   = Model::insert('vacancies', $data); // Insert row
        $insertID = Model::insertID();

        if (!$result && $insertID) {

            // Remove and after insert sectors
            $sectors = VacanciesModel::getVacancySectors($id);
            if (is_array($sectors) && count($sectors) > 0) {
                foreach ($sectors as $sector) {
                    Model::insert('vacancies_sectors', array(
                        'vacancy_id' => $insertID,
                        'sector_id' => $sector->sector_id
                    ));
                }
            }

            // Remove and after insert locations
            $locations = VacanciesModel::getVacancyLocations($id);
            if (is_array($locations) && count($locations) > 0) {
                foreach ($locations as $location) {
                    Model::insert('vacancies_locations', array(
                        'vacancy_id' => $insertID,
                        'location_id' => $location->location_id
                    ));
                }
            }

            Request::addResponse('redirect', false, url('panel', 'vacancies', 'edit', $insertID));

        } else {
            Request::returnError('Database Error');
        }

    }

    public function archiveAction()
    {
        $id = intval(Request::getUri(0));

        $this->view->list = VacanciesModel::getAll($id, false, " OR (`time_expire` < '" . (time() - 180) . "' AND `time_expire` != 0)", 'yes');

        Request::setTitle('Archive Vacancies');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = VacanciesModel::get($id);

        if (!$user)
            redirect(url('panel/vacancies/archive'));

        $result = Model::update('vacancies', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'vacancy#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/vacancies/archive'));
    }

    public function expireAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = VacanciesModel::get($id);

        if (!$id)
            redirect(url('panel/vacancies'));

        if ($this->startValidation()) {
            $this->validatePost('reason','Reason', 'required|trim|min_length[1]|max_length[255]');


            if ($this->isValid()) {
                $data = array(
                    'expire_reason' => post('reason'),
                    'time_expire'   => time() - 180 * 24 * 3600,
                );

                $result = Model::update('vacancies', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    Request::addResponse('redirect', false, url('panel', 'vacancies'));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Expire Vacancy');
    }

    public function download_csvAction()
    {
        Request::ajaxPart();

        $vacancies = VacanciesModel::getAll();

        if (is_array($vacancies) && count($vacancies) > 0) {
            //prepare data
            $dataToCsv = [];
            foreach ($vacancies as $k => $vacancy) {
                $fullname = ($value->consultant->firstname ?? '') . ' ' . ($value->consultant->lastname ?? '');

                $dataToCsv[$k]['id'] = $vacancy->id;
                $dataToCsv[$k]['title'] = $vacancy->title;
                $dataToCsv[$k]['ref'] = $vacancy->ref;
                $dataToCsv[$k]['locations'] = implode(", ", array_map(function ($location) { return $location->location_name; }, $vacancy->locations));
                $dataToCsv[$k]['sectors'] = implode(", ", array_map(function ($sector) { return $sector->sector_name; }, $vacancy->sectors));
                $dataToCsv[$k]['contract_type'] = $vacancy->contract_type;
                $dataToCsv[$k]['salary'] = $vacancy->salary_value;
                $dataToCsv[$k]['date_created'] = date('m.d.Y', $vacancy->time);
                $dataToCsv[$k]['date_expire'] = date('m.d.Y', $vacancy->time_expire);
                $dataToCsv[$k]['description'] = reFilter($vacancy->content);
                $dataToCsv[$k]['views'] = $vacancy->views;
                $dataToCsv[$k]['applications'] = $vacancy->applications;
                $dataToCsv[$k]['consultant'] = $fullname;
                $dataToCsv[$k]['slug'] = $vacancy->slug;
            }

            $df = fopen("app/data/tmp/vacancies.csv", 'w');
            fputcsv($df, array_keys(reset($dataToCsv)), ';');
            foreach ($dataToCsv as $row) {
                fputcsv($df, $row, ';');
            }
            fclose($df);

            Request::addResponse('func', 'downloadFile', _SITEDIR_ . 'data/tmp/vacancies.csv');
            Request::addResponse('func', 'removeLoader');
            Request::endAjax();
        } else {
            Request::addResponse('func', 'removeLoader');
            Request::addResponse('func', 'alert', 'No Vacancies');
        }

    }

    public function download_xmlAction()
    {
        header("Content-type: xml");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-relative");
        //get all jobs
        $jobs = VacanciesModel::getAll();

        //create xml and set options
        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;
        $xml_file_name = 'jobs.xml';

        //create file content
        $root = $dom->createElement('jobs');
        foreach ($jobs as $value) {
            $fullname = ($value->consultant->firstname ?? '') . ' ' . ($value->consultant->lastname ?? '');

            $job = $root->appendChild($dom->createElement('job'));
            $job->appendChild($dom->createElement('id', $value->id));
            $job->appendChild($dom->createElement('ref', $value->ref));
            $job->appendChild($dom->createElement('title', $value->title));
            $job->appendChild($dom->createElement('locations', implode(", ", array_map(function ($location) { return $location->location_name; }, $value->locations))));
            $job->appendChild($dom->createElement('sectors',  implode(", ", array_map(function ($sector) { return $sector->sector_name; }, $value->sectors))));
            $job->appendChild($dom->createElement('contract_type', $value->contract_type));
            $job->appendChild($dom->createElement('salary', $value->salary_value));
            $job->appendChild($dom->createElement('description', reFilter($value->content)));
            $job->appendChild($dom->createElement('views', $value->views));
            $job->appendChild($dom->createElement('applications', $value->applications));
            $job->appendChild($dom->createElement('consultant', $fullname));
            $job->appendChild($dom->createElement('date_created',  date('m.d.Y', $value->time)));
            $job->appendChild($dom->createElement('date_expire',  date('m.d.Y', $value->time_expire)));
            $job->appendChild($dom->createElement('slug',   $value->slug));
            $job->appendChild($dom->createElement('link', SITE_URL . 'job/' . $value->slug));
        }

        $dom->appendChild($root);
        $dom->save($xml_file_name);
        echo $dom->saveXML($dom->documentElement);

        exit;
    }

    public function export_cvsAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        if (!$id)
            redirect('panel/vacancies');

        Model::import('panel/vacancy_applications');
        $this->view->list = Vacancy_applicationsModel::getWhere("`vacancy_id` = '$id'");

        if (count($this->view->list) > 0) {
            $files = [];
            foreach ($this->view->list as $k => $obj) {
                if (file_exists(_SYSDIR_ . 'data/cvs/' . $obj->cv)) {
                    $files['cv-' . $obj->email . '-' . $obj->time . $k . '.' . File::format($obj->cv)] = _SYSDIR_ . 'data/cvs/' . $obj->cv;
                }
            }

            $result = $this->createZip($files);

            if (!$result)
                Request::returnError('Download Error');
            else
                Request::addResponse('func', 'downloadFile', _SITEDIR_ . 'data/export/cvs.zip');

        } else
            Request::returnError('No CV\'s for this vacancy');

        Request::addResponse('func', 'removeLoader');
        Request::endAjax();
    }

    private function createZip($files)
    {
        if (!file_exists(_SYSDIR_ . 'data/export')) {
            mkdir(_SYSDIR_ . 'data/export', 0777, true);
        }

        $zip = new ZipArchive;

        if ($zip->open(_SYSDIR_ . 'data/export/cvs.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE)
        {
            foreach ($files as $k => $file) {
                $relativeNameInZipFile = basename($file);
                $zip->addFile($file, $relativeNameInZipFile);
                $zip->renameName($relativeNameInZipFile, $k);
            }

            // All files are added, so close the zip file.
            $zip->close();
        }

        if (file_exists(_SYSDIR_ . 'data/export/cvs.zip'))
            return true;
        else
            return false;
    }

    public function widgetAction()
    {
        $target = post('target', true, '#widget_list');
        $action = post('action', true, 'append'); // append|html|...
        $this->view->list = VacanciesModel::getLatest(6);

        Request::ajaxPart();
        Request::addResponse($action, $target, $this->getView());
    }

    // Statistic

    public function statisticAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->vacancy = VacanciesModel::get($id);

        if (!$this->view->vacancy)
            redirect(url('panel/vacancies'));

        $this->view->list = VacanciesModel::getViewsByDays($this->view->vacancy->id);
        $this->view->referrals = VacanciesModel::getReferrersList($this->view->vacancy->id);
        $this->view->views = VacanciesModel::getViews($this->view->vacancy->id);

        // Last 9 days empty array
        $this->view->data = [];
        for ($i = time() - 9 * 24 * 3600; $i <= time(); $i += 24 * 3600)
            $this->view->data[date("d.m", $i)] = 0;

        // Count the number of entities every day
        foreach ($this->view->list as $value)
            $this->view->data[date("d.m", $value->time)]++;

        // Count refs
        $this->view->refArray = [];
        foreach ($this->view->views as $v)
            $this->view->refArray[$v->ref]++;

        $this->view->count = array_sum($this->view->data);

        Request::setTitle('Statistic Vacancy');
    }

    public function add_refAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->vacancy = VacanciesModel::get($id);

        if (!$this->view->vacancy)
            redirect(url('panel/vacancies'));

        if ($this->startValidation()) {
            $this->validatePost('title',            'Referrer Title',           'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'vacancy_id'   => $this->view->vacancy->id,
                    'title'     => makeSlug(post('title')),
                );

                $result   = Model::insert('vacancies_referrers', $data); // Insert row
                $insertID = Model::insertID();


                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'vacancy-ref#' . $insertID, 'time' => time()]);

                    Request::addResponse('redirect', false, url('panel', 'vacancies', 'statistic', $this->view->vacancy->id));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Vacancy Referrer');
    }

    public function delete_refAction()
    {
        $id = (Request::getUri(0));
        $ref = VacanciesModel::getReferrer($id);

        if (!$ref)
            redirect(url('panel/vacancies'));

        $result = Model::delete('vacancies_referrers',"`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'vacancy-referral#' . $id, 'time' => time()]);

        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/vacancies/statistic/' . $ref->vacancy_id));
    }

    // FOR Ajax Pagination
    public function index_paginationAction()
    {
        $this->view->list = VacanciesModel::getAll(false, false, " AND (`time_expire` > '" . (time() - 180) . "' OR `time_expire` = 0)");

        Request::setTitle('Manage Vacancies');
    }

    public function paginationAction()
    {
        Request::ajaxPart();

        $draw        = post('draw'); // request-response identifier
        $start       = intval(post('start')); // limit start
        $end         = post('length'); // rows display per page
        $columnIndex = post('order')[0]['column']; // column index
        $orderby     = post('columns')[$columnIndex]['data']; // column name
        $sort        = post('order')[0]['dir']; // asc or desc
        $searchValue = post('search')['value']; // search value

        //prepare query for search
        $where = " ";
        if ($searchValue) {
            $where = " AND (v.`title` LIKE '%$searchValue%' OR v.`ref` LIKE '%$searchValue%' )";
        }

        //total number of records without filtering

        $totalRecords = count(VacanciesModel::getAll(false, false, " AND (v.`time_expire` > '" . (time() - 180) . "' OR v.`time_expire` = 0)"));

        //total number of record with filtering
        $totalRecordWithFilter = count(VacanciesModel::getAll(false, false, " AND (v.`time_expire` > '" . (time() - 180) . "' OR v.`time_expire` = 0) $where "));

        //search
        $data = VacanciesModel::search($where, $orderby, $sort, $start, $end);

        //prepare data for response
        $responseData = [];
        if (count($data) > 0) {
            foreach ($data as $value) {
                $responseData[] = [
                    'id' => $value->id,
                    'ref' => $value->ref,
                    'title' => $value->title,
                    'sectors' => implode(", ", array_map(function ($sector) {return $sector->name;}, $value->sectors)),
                    'locations' => implode(", ", array_map(function ($location) {return $location->name;}, $value->locations)),
                    'views' => $value->views,
                    'applies' => $value->applies,
                    'time' => date('Y-m-d', $value->time),
                    'slug' => $value->slug,
                ];
            }
        }

        //response
        $response = array(
            "draw" => intval($draw), // request-response identifier
            "recordsTotal" => intval($totalRecords), //total number of records without filtering
            "recordsFiltered" => intval($totalRecordWithFilter), //total number of record with filtering
            "data" => $responseData // data for table
        );

        echo json_encode($response);
        exit;
    }
}
/* End of file */

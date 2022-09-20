<?php
class Candidates_portalController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->team = Candidates_portalModel::getAllCandidates();

        Request::setTitle('Candidates');
    }

    public function archiveAction()
    {
        $this->view->team = Candidates_portalModel::getArchived();

        Request::setTitle('Archived Candidates');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = Candidates_portalModel::getCandidate($id);

        if (!$user)
            redirect(url('panel/candidates_portal/archive'));

        $result = Model::update('candidates_portal', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'candidate_portal#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/candidates_portal/archive'));
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('firstname',    'First Name',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',     'Last Name',        'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'firstname'   => post('firstname'),
                    'lastname'    => post('lastname'),
                    'slug'        => makeSlug(post('firstname') . ' ' . post('lastname')),
                    'time'        => time(),
                );

                $result   = Model::insert('candidates_portal', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'User created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'candidates_portal', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Candidate');
    }

    public function editAction()
    {
        $userID = intval(Request::getUri(0));
        $this->view->user = Candidates_portalModel::getCandidate($userID);
        if (!$this->view->user)
            redirect(url('panel/candidates_portal'));

        Model::import('panel/vacancies');
        Model::import('panel/team');
        $this->view->vacancies = VacanciesModel::getAll();
        $this->view->customers = TeamModel::getUsersWhere(" `role` = 'customer'");

        if ($this->startValidation()) {
            $this->validatePost('firstname',      'First Name',                'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',       'Last Name',                 'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('description',    'Description',               'trim|min_length[0]');
            $this->validatePost('email',          'Email',                     'trim|min_length[5]|email');
            $this->validatePost('tel',            'Phone Number',              'trim|min_length[5]|max_length[15]');
            $this->validatePost('location',       'Location',                  'trim|min_length[0]|max_length[100]');
            $this->validatePost('address',        'Address',                   'trim|min_length[0]|max_length[500]');
            $this->validatePost('notice_period',  'Notice Period',             'trim|min_length[0]|max_length[200]');
            $this->validatePost('salary',         'Salary',                    'trim|min_length[0]|max_length[200]');
            $this->validatePost('hired_salary',   'Salary for hired Candidate','trim|min_length[0]|max_length[200]');
            $this->validatePost('file',           'CV',                        'trim|min_length[0]|max_length[100]');
            $this->validatePost('id_file',        'ID',                        'trim|min_length[0]|max_length[100]');
            $this->validatePost('passport',       'Passport',                  'trim|min_length[0]|max_length[100]');
            $this->validatePost('linkedin',       'LinkedIn URL',              'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('git_hub',        'GitHub',                    'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('stack_overflow', 'StackOverFlow',             'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('site',           'Site',                      'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('link1',          'Link',                      'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('link2',          'Link',                      'trim|min_length[0]|max_length[100]|url');

            // Times comparing/checking
            $intTime   = convertStringTimeToInt(post('dob'));
            $checkTime = date("d/m/Y", $intTime);

            if ($checkTime != post('dob')) {
                $this->addError('dob', 'Wrong Day of Birthday');
            }

            // Times comparing/checking
            $intStartTime   = convertStringTimeToInt(post('start_date'));
            $checkStartTime = date("d/m/Y", $intStartTime);

            if ($checkStartTime != post('start_date')) {
                $this->addError('start_date', 'Wrong Start Date');
            }

            $linkedin = '';
            if (post('linkedin'))
                $linkedin = 'https://' . str_replace(['http://', 'https://'],  ['',''], post('linkedin'));

            $git_hub = '';
            if (post('git_hub'))
                $git_hub = 'https://' . str_replace(['http://', 'https://'],  ['',''], post('git_hub'));

            $stack_overflow = '';
            if (post('stack_overflow'))
                $stack_overflow = 'https://' . str_replace(['http://', 'https://'],  ['',''], post('stack_overflow'));

            $site = '';
            if (post('site'))
                $site = 'https://' . str_replace(['http://', 'https://'],  ['',''], post('site'));

            $link1 = '';
            if (post('link1'))
                $link1 = 'https://' . str_replace(['http://', 'https://'],  ['',''], post('link1'));

            $link2 = '';
            if (post('link2'))
                $link2 = 'https://' . str_replace(['http://', 'https://'],  ['',''], post('link2'));

//            $employed    = post('employed') ? post('employed') : 'off';
            $hide_offers = post('hide_offers') ? post('hide_offers') : 'off';

            if ($this->isValid()) {
                $data = array(
                    'firstname'       => post('firstname'),
                    'lastname'        => post('lastname'),
                    'description'     => post('description'),
                    'location'        => post('location'),
                    'address'         => post('address'),
                    'email'           => post('email'),
                    'tel'             => post('tel'),
                    'notice_period'   => post('notice_period'),
                    'salary'          => post('salary'),
                    'hired_salary'    => post('hired_salary'),
                    'cv'              => post('file'),
                    'id_file'         => post('id_file'),
                    'passport'        => post('passport'),
                    'customer_offer'  => intval(post('customer_offer')),
//                    'employed'        => $employed,
                    'hide_offers'     => $hide_offers,
                    'dob'             => $intTime,
                    'start_date'      => $intStartTime,
                    'linkedin'        => $linkedin,
                    'git_hub'         => $git_hub,
                    'stack_overflow'  => $stack_overflow ,
                    'site'            => $site,
                    'link1'           => $link1,
                    'link2'           => $link2,
                    'slug'            => makeSlug(post('firstname') . ' ' . post('lastname')),
                );

                if ($data['cv']) {
                    if ($this->view->user->cv !== $data['cv']) {
                        if (File::copy('data/tmp/' . $data['cv'], 'data/candidates/' . $data['cv'])) {
                            File::remove('data/candidates/' . $this->view->user->cv);
                        } else
                            print_data(error_get_last());
                    }
                }

                if ($data['id_file']) {
                    if ($this->view->user->id_file !== $data['id_file']) {
                        if (File::copy('data/tmp/' . $data['id_file'], 'data/candidates/' . $data['id_file'])) {
                            File::remove('data/candidates/' . $this->view->user->id_file);
                        } else
                            print_data(error_get_last());
                    }
                }

                if ($data['passport']) {
                    if ($this->view->user->passport !== $data['passport']) {
                        if (File::copy('data/tmp/' . $data['passport'], 'data/candidates/' . $data['passport'])) {
                            File::remove('data/candidates/' . $this->view->user->passport);
                        } else
                            print_data(error_get_last());
                    }
                }

                //send email if accepted
                if (post('customer_offer') && ($this->view->user->customer_offer != post('customer_offer'))) {

                    $this->view->customer = TeamModel::getUser(post('customer_offer'));

                    // Mail to client/consultant
                    $mail = new Mail;
                    $mail->initDefault('New Offer Accepted', $this->getView('modules/panel/modules/candidates_portal/views/email_templates/offer_accepted.php'));
                    $mail->AddAddress($this->view->customer->email);
                    $mail->sendEmail('new offer accepted');

                }


                $result = Model::update('candidates_portal', $data, "`id` = '$userID'"); // Update row

                if ($result) {

                    // Remove and after insert vacancies

                    $vacancies = VacanciesModel::getVacanciesByCandidate($userID);

                    $oldVacancies = [];
                    foreach ($vacancies as $item) {
                        $oldVacancies[] = $item->vacancy_id;
                    }

                    $newVacancies = post('vacancy_ids');

//                    if (count($oldVacancies) == count($newVacancies)) {

                    $updVacancies = [];

                    if (is_array($oldVacancies) && count($oldVacancies) > 0) {
                        if (is_array($newVacancies) && count($newVacancies) > 0) {
                            foreach ($oldVacancies as $k => $oId) {

                                foreach ($newVacancies as $key => $nId) {
                                    if ($oId == $nId) {
                                        $updVacancies[] = $oId;
                                        unset($oldVacancies[$k]);
                                        unset($newVacancies[$key]);
                                    }
                                }

                            }
                        }
                    }

                    if (is_array($newVacancies) && count($newVacancies) > 0) { // new Candidate for Vacancies

                        foreach ($newVacancies as $vacancy_id) {
                            Model::insert('vacancies_candidates', array(
                                'candidate_id' => $userID,
                                'vacancy_id' => $vacancy_id
                            ));

                            Model::insert('candidates_status', array(
                                'candidate_id' => $userID,
                                'vacancy_id' => $vacancy_id
                            ));

                            $vacancy = VacanciesModel::get($vacancy_id);

                            if (is_array($vacancy->customers) && count($vacancy->customers) > 0) {

                                if (!post('customer_offer', 'int')) {
                                    foreach ($vacancy->customers as $item) {

                                        $this->view->customer = $item;
                                        $this->view->vacancy = $vacancy;

                                        // Mail to client/consultant
                                        $mail = new Mail;
                                        $mail->initDefault('New Candidate', $this->getView('modules/panel/modules/vacancies/views/email_templates/new_candidate.php'));
                                        $mail->AddAddress($this->view->customer->email);
                                        $mail->sendEmail('new_candidate');
                                    }
                                }
                            }
                        }
                    }

                    if (is_array($updVacancies) && count($updVacancies) > 0) { // updated vacancies
                        foreach ($updVacancies as $vacancy_id) {
                            $vacancy = VacanciesModel::get($vacancy_id);

                            if (is_array($vacancy->customers) && count($vacancy->customers) > 0) {
                                if (!post('customer_offer', 'int')) {
                                    foreach ($vacancy->customers as $item) {

                                        $this->view->customer = $item;
                                        $this->view->vacancy = $vacancy;

                                        // Mail to client/consultant
                                        $mail = new Mail;
                                        $mail->initDefault('Candidate Updated', $this->getView('modules/panel/modules/portal_vacancies/views/email_templates/updated_candidate.php'));
                                        $mail->AddAddress($item->email);
                                        $mail->sendEmail('candidate', $this->view->user->id);
                                    }
                                }
                            }
                        }
                    }

//                    Request::addResponse('redirect', false, url('panel', 'candidates_portal', 'edit', $userID));
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Candidate');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $userID = intval(Request::getUri(0));
        $user = Candidates_portalModel::getCandidate($userID);

        if (!$user)
            Request::returnError('Candidate error');

        $data['deleted'] = 'yes';
        $result = Model::update('candidates_portal', $data, "`id` = '$userID'"); // Update row

        if ($result) {
            Model::delete('vacancies_candidates', " `candidate_id` = '$userID'");

            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $userID);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function sortAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $userID = Request::getUri(1);
        $direction = Request::getUri(0);
        if ($direction != 'up') $direction = 'down';

        $user = TeamModel::getUser($userID);

        if (!$user)
            redirectAny(url('panel/team'));

        if (!$user->sort) { // if sort = 0
            $biggest = TeamModel::getBiggestSort();
            $data['sort'] = intval($biggest->sort) + 1;
            Model::update('users', $data, "`id` = '$userID'");
        } else { // if sort > 0
            if ($direction == 'up') {
                $smallest = TeamModel::getNextSmallestSort($user->sort);
                if (!$smallest)
                    Request::returnError('Already on the top');

                Model::update('users', ['sort' => $smallest->sort], "`id` = '$userID'");
                Model::update('users', ['sort' => $user->sort], "`id` = '" . ($smallest->id) . "'");
            } else {
                $biggest = TeamModel::getNextBiggestSort($user->sort);
                if (!$biggest)
                    Request::returnError('Already on the bottom');

                Model::update('users', ['sort' => $biggest->sort], "`id` = '$userID'");
                Model::update('users', ['sort' => $user->sort], "`id` = '" . ($biggest->id) . "'");
            }
        }

        redirectAny(url('panel/team'));
    }
}
/* End of file */

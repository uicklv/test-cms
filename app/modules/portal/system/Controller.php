<?php

class PortalController extends Controller
{
    use Validator;

    public function indexAction()
    {
        Model::import('panel/vacancies');
        $this->view->list = VacanciesModel::getVacanciesByConsultant(User::get('id', 'portal'));

        Request::setTitle('Portal');
    }

    public function loginAction()
    {
        $this->view->email = getCookie('email');
        $this->view->pass = getCookie('pass');

        if ($this->startValidation()) {
            $email = $this->validatePost('email',       'Email',    'required|trim|email');
            $pass  = $this->validatePost('password',    'Password', 'required|trim|min_length[6]|max_length[32]');

            if ($this->isValid()) {
                $user = PageModel::getUserByEmail($email);

                // Check password
                if ($user && $user->deleted == 'no' && $user->password == md5($pass) && $user->role == 'customer') {
                    if (post('remember') == 'on') {
                        setMyCookie('email', $email,time() + 12*30*24*3600);
                        setMyCookie('pass', $pass, time() + 12*30*24*3600);
                    }

                    $token = $user->token ?: randomHash(); // generate token
                    Model::update('users', ['token' => $token], " `id` = $user->id");
                    // additional login
                    User::setCookie($user->id, 'portal'); // set user cookie
                    User::setTokenCookie($token, 'portal'); // set user token

                    redirectAny(get('url') ? get('url') : url('portal'));
                } else
                    Request::returnError('Invalid email and/or password. Please check your data and try again');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Portal Login');
    }

    public function logoutAction()
    {
        User::set(null, 'portal');
        User::setCookie(null, 'portal');
        User::setTokenCookie(null, 'portal');

        redirectAny(url('/portal'));
    }

    public function get_candidatesAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);

        Model::import('panel/vacancies');
        Model::import('panel/candidates_portal');
        $this->view->job = VacanciesModel::get($id);

//        if ($this->view->job->candidates)
//            $this->view->candidates = Candidates_portalModel::getCandidatesFromString($this->view->job->candidates);
//        else
//            $this->view->candidates = false;
        $this->view->candidates = VacanciesModel::getVacancyCandidates($this->view->job->id, " `customer_offer` != '". User::get('id') ."'");

        foreach ($this->view->candidates as $item) {
            if (!$item->c_status) {
                Model::insert('candidates_status', [
                    'status' => '1',
                    'candidate_id' => $item->id,
                    'vacancy_id' => $this->view->job->id
                ]);

                $item->c_status = '1';
            }
        }

//        Request::addResponse('html', '.advert_rem', '');
//        Request::addResponse('html', '#advert_' . $id, '<a href="' . url('job/' . $this->view->job->slug) . '" target="_blank">View Advert</a>');
        Request::addResponse('html', '#candidates_box', $this->getView());
        Request::endAjax();
    }

    public function sort_statusAction()
    {
        Request::ajaxPart();

        Model::import('panel/vacancies');
        Model::import('panel/candidates_portal');

        $id = post('vacancy_id');
        $status = post('status');
        $this->view->job = VacanciesModel::get($id);


        if (post('sort') == 'true' && post('srt') == $status) {
            $this->view->candidates = VacanciesModel::getVacancyCandidates($this->view->job->id, " `employed` = 'off'");

            Request::addResponse('attr', '#sort', '', 'value');
            Request::addResponse('attr', '#srt', '0', 'value');
        } else {
            $this->view->candidates = VacanciesModel::getVacancyCandidates($this->view->job->id, " `employed` = 'off' HAVING c_status = '$status'");
            Request::addResponse('attr', '#sort', 'true', 'value');
            Request::addResponse('attr', '#srt', $status, 'value');
        }

//        if ($this->view->job->candidates)
//            $this->view->candidates = Candidates_portalModel::getCandidatesFromString($this->view->job->candidates);
//        else
//            $this->view->candidates = false;


        Request::addResponse('html', '#candidates_sort_box', $this->getView());
        Request::endAjax();

    }

    public function questionAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('question', 'Question', 'required|trim|min_length[1]|max_length[2500]');

            if ($this->isValid()) {

                $id = Request::getUri(0);

                Model::import('panel/candidates_portal');
                Model::import('panel/team');
                $candidate = Candidates_portalModel::getCandidate($id);
                $this->view->candidate = $candidate;
                $this->view->vacancy = post('vacancy');
                $vacancy_id = post('vacancy_id');
                $this->view->customer = TeamModel::getUser(User::get('id'));

                $result = Model::update('candidates_status', ['status' => '2'], "`candidate_id` = '$candidate->id' AND `vacancy_id` = '$vacancy_id'"); // Update row

                // Send email to admin
                require_once(_SYSDIR_ . 'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));

                $mail->Subject = 'New Question';
                $mail->Body = $this->getView('modules/page/views/email_templates/question.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';
                $mail->Send();


                Request::addResponse('html', '#question_form_' . $candidate->id, '<h3 class="title"><span>Thank you!</span></h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function challengeAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('message', 'Message', 'required|trim|min_length[1]|max_length[2500]');

            if ($this->isValid()) {

                $id = Request::getUri(0);

                Model::import('panel/candidates_portal');
                Model::import('panel/team');
                $candidate = Candidates_portalModel::getCandidate($id);
                $this->view->candidate = $candidate;
                $this->view->vacancy = post('vacancy');
                $vacancy_id = post('vacancy_id');
                $this->view->customer = TeamModel::getUser(User::get('id'));

                $result = Model::update('candidates_status', ['status' => '2'], "`candidate_id` = '$candidate->id' AND `vacancy_id` = '$vacancy_id'"); // Update row

                // Send email to admin
                require_once(_SYSDIR_ . 'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));


                $mail->Subject = 'New Technical Challenge';
                $mail->Body = $this->getView('modules/page/views/email_templates/challenge.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';
                $mail->Send();


                Request::addResponse('html', '#challenge_form_' . $candidate->id, '<h3 class="title"><span>Thank you!</span></h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function rejectAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('message', 'Message', 'required|trim|min_length[1]|max_length[2500]');

            if ($this->isValid()) {

                $id = Request::getUri(0);

                Model::import('panel/candidates_portal');
                Model::import('panel/team');
                $candidate = Candidates_portalModel::getCandidate($id);
                $this->view->candidate = $candidate;
                $this->view->vacancy = post('vacancy');
                $vacancy_id = post('vacancy_id');
                $this->view->customer = TeamModel::getUser(User::get('id'));

                $result = Model::update('candidates_status', ['status' => '3'], "`candidate_id` = '$candidate->id' AND `vacancy_id` = '$vacancy_id'"); // Update row

                // Send email to admin
                require_once(_SYSDIR_ . 'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));

                $mail->Subject = 'New Candidate Rejection';
                $mail->Body = $this->getView('modules/page/views/email_templates/reject.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';
                $mail->Send();


                Request::addResponse('html', '#reject_form_' . $candidate->id, '<h3 class="title"><span>Thank you!</span></h3>');
                Request::addResponse('html', '#status_' . $candidate->id, 'Rejected');
                Request::addResponse('html', '#reject_button_' . $candidate->id, 'Return to process');
                Request::addResponse('attr', '#reject_button_' . $candidate->id, "load('page/change_status/" . $candidate->id . "', 'vacancy_id=" . $vacancy_id . "'); return false;", 'onclick');
                Request::addResponse('func', 'changeStatus', $candidate->id);
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function interviewAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('message', 'Message', 'required|trim|min_length[1]|max_length[2500]');
            $this->validatePost('availability', 'Your Availability', 'required|trim|min_length[1]|max_length[2500]');

            if ($this->isValid()) {

                if (!is_array(post('type')))
                    Request::returnError('The Type field is required');

                $id = Request::getUri(0);

                Model::import('panel/candidates_portal');
                Model::import('panel/team');
                $candidate = Candidates_portalModel::getCandidate($id);
                $this->view->candidate = $candidate;
                $this->view->vacancy = post('vacancy');
                $vacancy_id = post('vacancy_id');
                $this->view->customer = TeamModel::getUser(User::get('id'));

                $result = Model::update('candidates_status', ['status' => '2'], "`candidate_id` = '$candidate->id' AND `vacancy_id` = '$vacancy_id'"); // Update row

                // Send email to admin
                require_once(_SYSDIR_ . 'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));


                $mail->Subject = 'New Interview';
                $mail->Body = $this->getView('modules/page/views/email_templates/interview.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';
                $mail->Send();


                Request::addResponse('html', '#interview_form_' . $candidate->id, '<h3 class="title"><span>Thank you!</span></h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function share_cvAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);

        Model::import('panel/candidates_portal');
        $this->view->candidate = Candidates_portalModel::getCandidate($id);

        if ($this->startValidation()) {
            $this->validatePost('email',        'Email',            'required|trim|email');
            $this->validatePost('subject',      'Subject',          'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('message',      'Message',          'required|trim|min_length[1]');

            if ($this->isValid()) {

                // Send email to admin
                require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(post('email')); //ADMIN_MAIL

                $mail->Subject = post('subject');
                $mail->Body = $this->getView('modules/page/views/email_templates/share_cv.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';

                $name = $this->view->candidate->firstname . ' ' . $this->view->candidate->lastname;

                $mail->AddAttachment(_SYSDIR_ . 'data/candidates/' . $this->view->candidate->cv, $name . '.' . File::format($this->view->candidate->cv));
                $mail->Send();

                Request::addResponse('html', '#apply_form', '<h3 class="title-small" style="font-size: 25px;">CV Sent</h3>');
                Request::endAjax();

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function offersAction()
    {

        if (User::get('access') != 'limited') {
            Model::import('panel/vacancies');
            $this->view->candidates = VacanciesModel::getCandidatesForOffers(User::get('id'));
        } else {
            redirect(url('portal'));
        }

        Request::setTitle('Offers');
    }

}
/* End of file */

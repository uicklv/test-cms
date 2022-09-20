<?php

class Anonymous_profileController extends Controller
{
    use Validator;

    public function indexAction()
    {

        // module protection
        $acess = getSession('acess');
        $protection = Model::fetch(Model::select('talent_password_protection'));
        $areas = explode('||', trim($protection->areas, '|'));

        if (in_array('anonymous_search', $areas) && $acess !== 'yes')
            redirectAny(url("talent/protection?url=talent/anonymous_profile" ));

        Model::import('panel/talents/anonymous_profiles');
        Model::import('panel/talents/languages');
        $this->view->list  = Anonymous_profilesModel::getAll();
        $this->view->languages  = LanguagesModel::getAll();

        Request::setTitle('Anonymous profile');
    }

    public function viewAction()
    {
        $id = Request::getUri(0);

        Model::import('panel/talents/anonymous_profiles');
        $this->view->profile = Anonymous_profilesModel::get($id);
        if (!$this->view->profile)
            redirect(url('talent/anonymous_profile'));

        // module protection
        $acess = getSession('acess');
        $protection = Model::fetch(Model::select('talent_password_protection'));
        $areas = explode('||', trim($protection->areas, '|'));

        if (in_array('anonymous_profiles', $areas) && $acess !== 'yes')
            redirectAny(url("talent/protection?url=talent/anonymous_profile/" . $this->view->profile->id));



        $this->view->tc = Model::fetch(Model::select('your_tc'));

        Request::setTitle('Profile - ' . $this->view->profile->job_title);
    }

    public function searchAction()
    {
        Request::ajaxPart();

        $keywords      = post('keywords');
        $postcode      = post('postcode');
        $language      = post('language');
        $salary        = post('salary');
        $salary_term   = post('salary_term');

        Model::import('panel/talents/anonymous_profiles');
        $this->view->list = Anonymous_profileModel::search($keywords, $postcode, $language, $salary, $salary_term);

        if ($postcode && post('radius')) {
            usort($this->view->list, function ($a, $b) {
                return ($a->distance - $b->distance);
            });
        }
        Request::addResponse('html', '#anonymous-profiles', $this->getView());

    }

    public function postcodeAction()
    {
        Request::ajaxPart();
        $postcode = post('postcode');
        Model::import('panel/talents/open_profiles');
        $this->view->postcodes = Open_profilesModel::getPostcodes($postcode);

        if (!count($this->view->postcodes))
            Request::addResponse('html', '.suggests_result', '<div class="st-msg" onclick="closeSuggest();">Code not found</div>');
        else
            Request::addResponse('html', '.suggests_result', $this->getView());

        Request::endAjax();
    }

    public function candidate_alertAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        Model::import('panel/talents/anonymous_profiles');
        $this->view->profile = Anonymous_profilesModel::get($id);

        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',            'Email',                'required|trim|email');
            $this->validatePost('company_name',     'Company Name',         'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('postcode',         'Postcode/Zip',         'required|trim');
            $this->validatePost('skills_keywords',  'Skills/Keywords',      'required|trim|max_length[255]');
            $this->validatePost('max_salary',       'Max_salary',           'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('salary_term',      'Salary Term',          'required|trim|min_length[1]|max_length[50]');
            $this->validatePost('check',            'Agree',                'required');

            if ($this->isValid()) {
                $data = array(
                    'area'             => 'Anonymous profile',
                    'area_id'          => $this->view->profile->id ? $this->view->profile->id : 0,
                    'name'             => post('name'),
                    'email'            => post('email'),
                    'company_name'     => post('company_name'),
                    'postcode'         => post('postcode'),
                    'skills_keywords'  => post('skills_keywords'),
                    'max_salary'       => post('max_salary'),
                    'salary_term'      => post('salary_term'),
                    'time'             => time()
                );

                $this->view->area = 'Anonymous profile';
                $this->view->area_id = $this->view->profile->id;

                // Send email to admin
                require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));


                $mail->Subject = 'Candidate Alert';
                $mail->Body = $this->getView('modules/talent/modules/anonymous_profile/views/email_templates/candidate_alert.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';

                $mail->Send();

                $result = Model::insert('talent_candidate_alerts', $data);
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('html', '#apply_form', '<h3 style="font-size: 35px;">Thank you!</h3>');
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

    public function request_cvAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        Model::import('panel/talents/anonymous_profiles');
        $this->view->profile = Anonymous_profilesModel::get($id);

        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',            'Email',                'required|trim|email');
            $this->validatePost('company_name',     'Company Name',         'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('tel',              'Telephone',            'required|trim|min_length[1]|max_length[20]');
            $this->validatePost('check',            'Agree',                'required');

            if ($this->isValid()) {
                $data = array(
                    "request_type" => "open_profile",
                    "user_id" =>  $this->view->profile->consultant_id,
                    "profile_type" => "anonymous",
                    "profile_id" =>  $this->view->profile->id,
                    "name" => post('name'),
                    "company" => post('company_name'),
                    "email" => post('email'),
                    "tel" => post('tel'),
                    "ip" => getIP(),
                    "time" => time()
                );

                // Send email to admin
                require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));


                $mail->Subject = 'Request Full Profile & CV';
                $mail->Body = $this->getView('modules/talent/modules/anonymous_profile/views/email_templates/request_cv.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';

                $mail->Send();

                $result = Model::insert('talent_requests', $data);
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('html', '#apply_form', '<h3 style="font-size: 35px;">Thank you!</h3>');
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

    public function request_interviewAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        Model::import('panel/talents/anonymous_profiles');
        $this->view->profile = Anonymous_profilesModel::get($id);

        if ($this->startValidation()) {
            $this->validatePost('date',             'Date',                 'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('name',             'Name',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',            'Email',                'required|trim|email');
            $this->validatePost('company_name',     'Company Name',         'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('tel',              'Telephone',            'required|trim|min_length[1]|max_length[20]');
            $this->validatePost('check',            'Agree',                'required');

            if ($this->isValid()) {
                $data = array(
                    "request_type" => "interview",
                    "user_id"      =>  $this->view->profile->consultant_id,
                    "profile_type" => "anonymous",
                    "profile_id"   =>  $this->view->profile->id,
                    "name"         => post('name'),
                    "date"         => post('date'),
                    "company"      => post('company_name'),
                    "email"        => post('email'),
                    "tel"          => post('tel'),
                    "ip"           => getIP(),
                    "time"         => time()
                );

                // Send email to admin
                require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));


                $mail->Subject = 'Request Interview';
                $mail->Body = $this->getView('modules/talent/modules/anonymous_profile/views/email_templates/request_interview.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';

                $mail->Send();

                $result = Model::insert('talent_requests', $data);
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('html', '#apply_form', '<h3 style="font-size: 35px;">Thank you!</h3>');
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

    public function request_infoAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        Model::import('panel/talents/anonymous_profiles');
        $this->view->profile = Anonymous_profilesModel::get($id);

        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',            'Email',                'required|trim|email');
            $this->validatePost('company_name',     'Company Name',         'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('tel',              'Telephone',            'required|trim|min_length[1]|max_length[20]');
            $this->validatePost('check',            'Agree',                'required');

            if ($this->isValid()) {
                $data = array(
                    "request_type" => "further_info",
                    "user_id"      =>  $this->view->profile->consultant_id,
                    "profile_type" => "anonymous",
                    "profile_id"   =>  $this->view->profile->id,
                    "name"         => post('name'),
                    "company"      => post('company_name'),
                    "email"        => post('email'),
                    "tel"          => post('tel'),
                    "ip"           => getIP(),
                    "time"         => time()
                );

                // Send email to admin
                require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress(Request::getParam('admin_mail'));


                $mail->Subject = 'Request Further Info';
                $mail->Body = $this->getView('modules/talent/modules/anonymous_profile/views/email_templates/request_info.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';

                $mail->Send();

                $result = Model::insert('talent_requests', $data);
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('html', '#apply_form', '<h3 style="font-size: 35px;">Thank you!</h3>');
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

}
/* End of file */
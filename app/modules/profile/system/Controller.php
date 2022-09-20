<?php

class ProfileController extends Controller
{
    use Validator;

    public function loginAction()
    {
        if ($this->startValidation()) {
            $email = $this->validatePost('email',       'Email',    'required|trim|email');
            $pass  = $this->validatePost('password',    'Password', 'required|trim|min_length[6]|max_length[32]');

            if ($this->isValid()) {
                $user = ProfileModel::getCandidateByEmail($email);

                // Check password
                if ($user && $user->password == md5($pass) && $user->deleted == 'no') {
                    if ($user->email_confirm != 1) {
                        Request::addErrorResponse('func', 'resetRecaptcha');
                        Request::returnError('Please, confirm account. Confirmation was sent on email.');
                    }

                    $token = PageModel::createSession($user->id, 'candidate'); // Generate session and add to `users_session`
                    User::setTokenCookie($token, 'candidate'); // Set session in cookies(token)

                    redirectAny(get('url') ?: url('profile'));
                } else {
                    Request::addErrorResponse('func', 'resetRecaptcha');
                    Request::returnError('Invalid email and/or password. Please check your data and try again');
                }
                Request::endAjax();
            } else {
                if (Request::isAjax()) {
                    Request::addErrorResponse('func', 'resetRecaptcha');
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        Request::setTitle('Login / Register');
    }

    public function registerAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('firstname',     'First Name',        'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',      'Last Name',         'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',         'Email',             'required|trim|email');
            $this->validatePost('tel',           'Telephone Number',  'trim|min_length[0]|max_length[100]');
//            $this->validatePost('job_title',     'Job Title',         'trim|min_length[0]|max_length[250]');
//            $this->validatePost('pay_type',      'Pay Type',          'trim|min_length[0]|max_length[250]');
//            $this->validatePost('salary_range',  'Salary Range',      'trim|min_length[0]|max_length[250]');
//            $this->validatePost('pay_rate',      'Pay Rate',          'trim|min_length[0]|max_length[250]');
//            $this->validatePost('current_salary','Current Salary',    'trim|min_length[0]|max_length[250]');
//            $this->validatePost('location',      'Current Location',  'trim|min_length[0]|max_length[250]');
            $this->validatePost('password',      'Password',          'required|trim|password');
            $this->validatePost('password2',     'Confirm Password',  'required|trim|password');
            $this->validatePost('check',         'Privacy Policy',    'required|trim|min_length[1]');

            $checkUser = ProfileModel::getCandidateByEmail(post('email'));
            if ($checkUser)
                $this->addError('email', 'This email already exist in the system');

            if (post('password') !== post('password2'))
                $this->addError('password', 'Passwords should match');

            if ($this->isValid()) {
                $data = array(
                    'firstname'         => post('firstname'),
                    'lastname'          => post('lastname'),
                    'email'             => post('email'),
                    'tel'               => post('tel'),
//                    'job_title'         => post('job_title'),
//                    'pay_type'          => post('pay_type'),
//                    'salary_range'      => post('salary_range'),
//                    'pay_rate'          => post('pay_rate'),
//                    'current_salary'    => post('current_salary'),
//                    'location'          => post('location'),
                    'role'              => 'user',
                    'password'          => md5(post('password')),
                    'slug'              => makeSlug(post('firstname') . ' ' . post('lastname')),
                    'reg_time'          => time(),
                    'last_time'         => time(),
                );


                $result   = Model::insert('candidates', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    //email confirmation
                    $token = md5($data['email'] . time());
                    $result = Model::update('candidates', ['email_token' => $token], " `id` = $insertID");
                    if ($result) {
                        $this->view->token = $token;
                        $this->view->email = $data['email'];

                        $mail = new Mail;
                        $mail->initDefault('Email Confirmation', $this->getView('modules/profile/views/email_templates/email_confirm.php'));
                        $mail->AddAddress($data['email']);
                        $mail->sendEmail('email_confirmation');
                        Request::addResponse('html', '#form_register', '<p>Thank you for signing up. Please check your email to activate your account.</p>');
                        Request::endAjax();
                    } else {
                        Request::returnError('Database error');
                    }
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Login / Register');
    }

    public function email_confirmationAction()
    {
        $email = get('email');
        $token = get('token');

        //get user with this email
        $user = Model::fetch(Model::select('candidates', " `deleted` = 'no' AND `email` = '$email'"));

        $this->view->error = false;
        if ($user->email_token === $token) {
            $token = $user->token ?: randomHash(); //generate token

            Model::update('candidates', ['email_confirm' => 1, 'email_token' => '', 'token' => $token],
                "`id` = $user->id");

            User::setCookie($user->id, 'candidate'); // set user cookie
            User::setTokenCookie($token, 'candidate'); // set user token
            redirectAny(url('profile'));
        } else {
            $this->view->error = true;
        }

        Request::setTitle('Confirmation');
    }

    public function profileAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('firstname',                'First Name',               'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',                 'Last Name',                'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',                    'Email',                    'required|trim|email');
            $this->validatePost('tel',                      'Telephone',                'trim|min_length[0]|max_length[100]');
//            $this->validatePost('pay_type',                 'Pay Type',                 'trim|min_length[0]|max_length[250]');
//            $this->validatePost('salary_range',             'Salary Range',             'trim|min_length[0]|max_length[250]');
//            $this->validatePost('pay_rate',                 'Pay Rate',                 'trim|min_length[0]|max_length[250]');
//            $this->validatePost('current_salary',           'Current Salary',           'trim|min_length[0]|max_length[250]');
            $this->validatePost('location',                 'Current Location',         'trim|min_length[0]|max_length[250]');
            $this->validatePost('job_title',                'Current Job Title',        'trim|min_length[0]|max_length[100]');
//            $this->validatePost('desired_location',          'Desired Location',        'trim|min_length[0]|max_length[100]');
            $this->validatePost('employment_status',        'Employment Status',        'trim|min_length[0]|max_length[100]');
            $this->validatePost('interview_availability',   'Interview Availability',   'trim|min_length[0]|max_length[100]');
            $pass  = $this->validatePost('password',     'New Password',             'trim|password');
            $pass2 = $this->validatePost('password2',    'Confirm New Password',     'trim|password');

            if ($this->isValid()) {

                $locations = post('locations') ? post('locations') : [];
                $sectors = post('sectors') ? post('sectors') : [];
//                $specialisms = post('specialisms') ? post('specialisms') : [];

                $data = array(
                    'firstname'             => post('firstname'),
                    'lastname'              => post('lastname'),
                    'email'                 => post('email'),
                    'tel'                   => post('tel'),
                    'job_title'             => post('job_title'),
                    'location'              => post('location'),
//                    'pay_type'              => post('pay_type'),
//                    'salary_range'          => post('salary_range'),
//                    'pay_rate'              => post('pay_rate'),
//                    'current_salary'        => post('current_salary'),
//                    'desired_salary'        => post('desired_salary'),
//                    'desired_location'      => post('desired_location'),
//                    'specialisms'           => '|' . implode('||', $specialisms) . '|',
                    'employment_status'     => post('employment_status'),
                    'interview_availability'=> post('interview_availability'),
                    'sectors'               => '|' . implode('||', $sectors) . '|',
                    'locations'             => '|' . implode('||', $locations) . '|',
                    'cv'                    => post('cv'),
                );

                if (post('password')) {
                    if ($pass == $pass2) {
                        $data['password'] = md5($pass);
                    } else
                        Request::returnError('Passwords should match');
                }


                // Copy and remove image
                if ($data['cv']) {
                    if (User::get('cv', 'candidate') !== $data['cv']) {
                        if (File::copy('data/tmp/' . $data['cv'], 'data/cvs/' . $data['cv'])) {
                            File::remove('data/cvs/' . $this->view->user->cv);

                            // Mail to client/consultant
                            $mail = new Mail;
                            $mail->initDefault('Uploaded CV', $this->getView('modules/profile/views/email_templates/added_cv.php'));
                            $mail->AddAddress(Request::getParam('admin_mail'));
                            $mail->AddAttachment(_SYSDIR_ . 'data/cvs/' . $data['cv'], $data['firstname'] . ' ' . $data['lastname'] . '.' . File::format($data['cv']));
                            $mail->sendEmail('uploaded cv');

                        } else {
                            Request::returnError('CV file error');
                            //print_data(error_get_last());
                        }
                    }
                }

                $result = Model::update('candidates', $data, "`id` = '" . ( User::get('id', 'candidate') ) . "'"); // Update row
                Request::addResponse('func', 'noticeSuccess', 'Profile saved!');
                Request::endAjax();

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }


        // for you
        Model::import('jobs');

        $sectorsNames = explode('||', trim(User::get('sectors', 'candidate'), '|'));
        $sectors = [];
        if (count(array_filter($sectorsNames)) < 1) {
            $sectors = false;
        } else {
//            foreach ($sectorsNames as $item) {
//                $sec = JobsModel::getSectorByName($item);
//                $sectors[] = $sec->id;
//            }
        }

        Model::import('panel/vacancies/locations');
        $loc = LocationsModel::get(User::get('desired_location', 'candidate'));
        $this->view->currency = $loc->currency_s;

        $this->view->jobs = JobsModel::search(false,false, $sectors, User::get('desired_location', 'candidate'), false, 5);
        $this->view->sectors   = JobsModel::getSectors();
        $this->view->locations = JobsModel::getLocations();
//        $this->view->specialisms = JobsModel::getSpecialisms();
//        $this->view->shortlist = JobsModel::getFavorites(User::get('id', 'candidate'));
//        $this->view->applies = JobsModel::getApplies(User::get('email', 'candidate'));

        Request::setTitle('My Account');
    }

    public function restore_passwordAction()
    {
        if ($this->startValidation()) {
            Request::ajaxPart();
            $this->validatePost('email',   'Email',    'required|trim|email');

            if ($this->isValid()) {
                $user = PageModel::getCandidateByEmail(post('email'));
                if (!$user)
                    Request::returnError('This email does not exist');

                //generate hash and update user row
                $this->view->email = $user->email;
                $this->view->hash = md5($user->email . time());
                Model::update('candidates', ['restore_token' => $this->view->hash], " `id` = $user->id");

                // Send email to admin
                require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
                $mail = new PHPMailer;

                // Mail to client/consultant
                $mail->IsHTML(true);
                $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
                $mail->AddAddress($user->email);


                $mail->Subject = 'Restore Password';
                $mail->Body = $this->getView('modules/profile/views/email_templates/restore_password.php');
                $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';
                $mail->Send();

                Request::addResponse('html', '#restore_form', '<h3 class="title"><span>An email has been sent to the address you provided. Please check your inbox and junk mail folder.</span></h3>');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        if ($this->isErrors())
            $this->view->errors = $this->getErrors();

        Request::setTitle('Restore Password');
    }

    public function restore_processAction()
    {
        $email = get('email');
        $hash = get('hash');
        $user = PageModel::getCandidateByEmail($email);

        //check hash
        if ($user->restore_token !== $hash)
            $this->view->errors = 'Hash is invalid';

        if ($this->startValidation()) {
            Request::ajaxPart();
            $this->validatePost('password',      'Password',          'required|trim|password');
            $this->validatePost('password2',     'Confirm Password',  'required|trim|password');

            if (post('password') !== post('password2'))
                $this->addError('password', 'Passwords should match');

            if ($this->view->errors)
                $this->addError('hash', 'Hash is invalid');

            if ($this->isValid()) {
                Model::update('candidates', ['password' => md5(post('password')), 'restore_token' => ''], "`email` = '$user->email'"); // Update row

                Request::addResponse('html', '#restore_form', '<h3 class="title"><span>Password updated successfully</span></h3>');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Restore Password');
    }

    public function salary_surveyAction()
    {
        Model::import('panel/vacancies/sectors');
        $this->view->sectors = SectorsModel::getAll();

        Model::import('panel/vacancies/locations');
        $this->view->locations = LocationsModel::getAll();

        $this->view->job_types = Model::fetchAll(Model::select('job_type'));
        $this->view->experiences = Model::fetchAll(Model::select('text_experience'));
        $this->view->salary_increases = Model::fetchAll(Model::select('salary_increase'));


        if ($this->startValidation()) {
            $this->validatePost('sector_id',      'Sector',          'required|trim');
            $this->validatePost('job_type',       'Job Type',        'required|trim');
            $this->validatePost('job_role',       'Job Role',        'required|trim');
            $this->validatePost('location',       'Location',        'required|trim');
            $this->validatePost('base_salary',    'Base Salary',     'required|trim');
            $this->validatePost('text_experience','Experience',      'required|trim');
            $this->validatePost('name',           'Full Name',       'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('email',          'Email',           'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('current_company','Company',         'trim|min_length[1]|max_length[255]');
            $this->validatePost('salary_move',    'Salary Move',     'trim|min_length[1]|max_length[255]');
            $this->validatePost('check',          'Privacy Policy',  'required|trim|min_length[1]');

            if ($this->isValid()) {

                if (User::get('id', 'candidate'))
                    $user_id = User::get('id', 'candidate');
                else
                    $user_id = 0;

                $data = [
                    'industry_sector' => post('sector_id'),
                    'job_type' =>  post('job_type'),
                    'job_role' =>  post('job_role'),
                    'location' =>  post('location'),
                    'current_basic_salary' =>  post('base_salary'),
                    'text_experience' =>  post('text_experience'),
                    'full_name' =>  post('name'),
                    'email_address' =>  post('email'),
                    'current_company_name' =>  post('current_company'),
                    'salary_increase' =>  post('salary_move'),
                    'user_id' => $user_id,
                    'time' => time()
                ];

                Model::insert('marketers', $data);
                $insertId = Model::insertID();

                $max_min_avg = $this->getMinMaxSurvey($data);

                $this->view->maximum_salary = $max_min_avg['basic_salary_max'];
                $this->view->minimum_salary = $max_min_avg['basic_salary_min'];
                $this->view->average_salary = $max_min_avg['basic_salary_avg'];

                $this->view->location = LocationsModel::get($data['location']);

                Model::update('marketers', ['maximum_salary' => $max_min_avg['basic_salary_max'],
                    'minimum_salary' => $max_min_avg['basic_salary_min'],
                    'average_salary' => $max_min_avg['basic_salary_avg']], "`id` = '$insertId'");

                Request::addResponse('html', '#result_box', $this->getView('modules/profile/views/salary_result.php'));
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Salary Survey');
    }

    public function get_roleAction()
    {
        Request::ajaxPart();

        $jobTypeId = post('job_type');

        $this->view->roles = Model::fetchAll(Model::select('job_role', " `job_type_id` = '" . $jobTypeId . "'"));

        Request::addResponse('html', '#roles_box', $this->getView());

    }

    private function getMinMaxSurvey($postParams) {

        $min_annual_salary = 16349;
        $max_annual_salary = 1000000;
        $minimum = 5;

        $get_similar_markers = ProfileModel::getMarketers($postParams['sector_id'], $postParams['job_type'], $postParams['job_role'],
            $postParams['location'], $postParams['text_experience']);

        if (count($get_similar_markers) > $minimum) {
            $max_min_avg = ProfileModel::getMaxMinAvgMarketers($postParams['sector_id'], $postParams['job_type'], $postParams['job_role'],
                $postParams['location'], $postParams['text_experience']);
        } else {
            $base_salary_half = $postParams['base_salary']/2;

            if($postParams['base_salary'] < $min_annual_salary)
                $postParams['base_salary'] = $min_annual_salary;

            if($postParams['base_salary'] > $max_annual_salary){
                $postParams['base_salary'] = $max_annual_salary;
            }

            $base_salary_half = $postParams['base_salary']/2;

            $max_min_avg['basic_salary_min'] = rand(
                $base_salary_half+0,
                $postParams['base_salary']+0
            );

            $max_min_avg['basic_salary_max'] = rand(
                $postParams['base_salary']+0,
                $postParams['base_salary']+$base_salary_half+0
            );

            $max_min_avg['basic_salary_avg'] = (
                    $max_min_avg['basic_salary_max']
                    + $max_min_avg['basic_salary_min']
                ) / 2;
        }
        return $max_min_avg;
    }


    public function delete_accountAction()
    {
        Model::update('candidates', ['deleted' => 'yes'], "`id` = '" . ( User::get('id', 'candidate') ) . "'"); // Update row
        User::set(null, 'candidate');
        User::setCookie(null, 'candidate');
        User::setTokenCookie(null, 'candidate');

        redirectAny(url('/profile'));
    }

    public function logoutAction()
    {
        PageModel::closeSession(User::getTokenCookie('candidate'));
        User::set(null, 'candidate');
        User::setTokenCookie(null, 'candidate');

        redirectAny(url('/profile'));
    }
}
/* End of file */

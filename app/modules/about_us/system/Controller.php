<?php

class About_usController extends Controller
{
    use Validator;

    public function indexAction()
    {
        Model::import('panel/videos');
        $this->view->video = VideosModel::getVideoByName('what-we-do');

        Request::setTitle('What We Do');
        Request::setKeywords('');
        Request::setDescription('');
    }

    public function profileAction()
    {
        $slug = Request::getUri(0);
        $this->view->profile = About_usModel::getUser($slug);

        if (!$this->view->profile)
            redirect(url('about-us','our-people'));

        Model::import('panel/team');
        $this->view->fun_images = TeamModel::getUserImages($this->view->profile->id);

        Request::setTitle($this->view->profile->firstname . ' ' . $this->view->profile->lastname);
    }

    public function our_peopleAction()
    {
        $this->view->list = About_usModel::getUsers("AND `role` IN('moder', 'admin') AND `display_team` = 'yes' ORDER BY `sort` ASC"); // AND `id` <= '21'
        Request::setTitle('Our People');
    }

    public function work_for_usAction()
    {
        Model::import('panel/testimonials');
        $this->view->testimonials = TestimonialsModel::getAll();

        Model::import('panel/videos');
        $this->view->video = VideosModel::getVideoByName('work-for-us');

        Model::import('jobs');
        $this->view->jobs = JobsModel::search(false, false, false, false, 10, " `internal` = 1");

        Request::setTitle('Work For Us');
    }

    public function upload_cvAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',            'Email',                'required|trim|email');
            $this->validatePost('tel',              'Contact Number',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('linkedin',         'LinkedIn',             'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('message',          'Further information',  'trim|min_length[1]|max_length[100]');
            $this->validatePost('cv_field',         'CV',                   'required|trim|min_length[0]|max_length[100]');
            $this->validatePost('check',            'Agree',                'required|trim|min_length[0]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'name'      => post('name'),
                    'email'     => post('email'),
                    'tel'       => post('tel'),
                    'linkedin'  => post('linkedin'),
                    'message'   => post('message'),
                    'cv'        => post('cv_field'),
                    'time'      => time()
                );

                // Copy CV
                if ($data['cv']) {
                    if (!File::copy('data/tmp/' . $data['cv'], 'data/cvs/' . $data['cv']))
                        print_data(error_get_last());
                }

                $result   = Model::insert('talent_pool_cv', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('html', '#upload_cv_form', '<h3 class="title-small">Thank you!</h3>');
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

    public function contactAction()
    {
        Request::ajaxPart();

        $slug = Request::getUri(0);
        $this->view->get = $consultant = About_usModel::getUser($slug);

        if (!$consultant)
            redirect(url('about-us','our-people'));

        if ($this->startValidation()) {
            $this->validatePost('name',    'Name',    'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',   'Email',   'required|trim|email');
            $this->validatePost('message', 'Message', 'required|trim|min_length[1]|max_length[500]');

            if ($this->isValid()) {
                // Mail to client/consultant
                $mail = new Mail;
                $mail->initDefault('Contact Us', $this->getView('modules/about_us/views/email_templates/contact.php'));
                $mail->AddAddress(Request::getParam('admin_mail'));
                $mail->sendEmail('contact_form');

                Request::addResponse('html', '#apply_form', '<h3 class="title-small">Thank you!</h3>');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function contact_usAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',                 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',            'Email',                'required|trim|email');
            $this->validatePost('tel',              'Contact Number',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('message',          'Further information',  'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('linkedin',         'LinkedIn',             'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('job_spec',         'Job spec',             'trim|min_length[0]|max_length[100]');
            $this->validatePost('cv',               'CV',                   'trim|min_length[0]|max_length[100]');
            $this->validatePost('check',            'GDPR',                 'required|trim|min_length[1]');

            if (Request::getParam('recaptcha_status'))
                $this->validatePost('g-recaptcha-response', 'reCAPTCHA', 'required');

            if ($this->isValid()) {
                if (Request::getParam('recaptcha_status')) {
                    $checkCaptcha = checkCaptcha(Request::getParam('recaptcha_key'), post('g-recaptcha-response'));
                    if (!$checkCaptcha)
                        Request::returnError('reCaptcha Error');
                }

                $data = array(
                    'name'      => post('name'),
                    'email'     => post('email'),
                    'tel'       => post('tel'),
                    'message'   => post('message'),
                    'linkedin'  => post('linkedin'),
                    'job_spec'  => post('job_spec'),
                    'cv'        => post('cv'),
                    'time'      => time()
                );

                // Copy job spec
                if ($data['job_spec']) {
                    if (!File::copy('data/tmp/' . $data['job_spec'], 'data/spec/' . $data['job_spec']))
                        print_data(error_get_last());
                }

                // Copy CV
                if ($data['cv']) {
                    if (!File::copy('data/tmp/' . $data['cv'], 'data/cvs/' . $data['cv']))
                        print_data(error_get_last());
                }


                $result   = Model::insert('cv_library', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    // Send email to admin
                    $this->view->uvid = $insertID;
                    $this->view->data = $data;

                    // Mail to client/consultant
                    $mail = new Mail;
                    $mail->initDefault('Submitted Contact Us form', $this->getView('modules/about_us/views/email_templates/contact_us.php'));
                    $mail->AddAddress(Request::getParam('admin_mail'));

                    if ($data['job_spec']) {
                        $mail->AddAttachment(_SYSDIR_ . 'data/spec/' . $data['job_spec'], $data['name'] . '_spec.' . File::format($data['job_spec']));
                    }

                    if ($data['cv']) {
                        $mail->AddAttachment(_SYSDIR_ . 'data/cvs/' . $data['cv'], $data['name'] . '.' . File::format($data['cv']));
                    }

                    $mail->sendEmail('contact_form');

                    Request::addResponse('html', '#contact_form', '<h3 class="title-small">Thank you!</h3>');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Contact Us');
    }

    /**
     * Upload CV files, etc.
     */
    public function uploadAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $name    = post('name', true); // file name, if not set - will be randomly
        $path    = post('path', true, 'tmp'); // path where file will be saved, default: 'tmp'
        $field   = post('field', true, '#image'); // field where to put file name after uploading
        $preview = post('preview', true, '#preview_file'); // field where to put file name after uploading

        $path = 'data/' . $path . '/';

        $result = null;
        foreach ($_FILES as $file) {
            $result = File::uploadCV($file, $path, $name);
            break;
        }

        $newFileName = $result['name'] . '.' . $result['format']; // randomized name

        Request::addResponse('val', $field, $newFileName);
        Request::addResponse('html', $preview, $result['fileName']);
    }
}
/* End of file */

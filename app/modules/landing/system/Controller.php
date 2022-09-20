<?php
class LandingController extends Controller
{
    use Validator;

    public function indexAction()
    {
        $slug = Request::getUri(0);

        Model::import('panel/landings');
        $this->view->landing = LandingsModel::getBySlug($slug);

        if (!$this->view->landing)
            redirect('/');

        $this->view->maps_api_key = SettingsModel::get('maps_api_key');

        Request::setTitle($this->view->landing->meta_title);
        Request::setKeywords($this->view->landing->meta_keywords);
        Request::setDescription($this->view->landing->meta_desc);
    }

    public function contactAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('firstname',    'First Name',            'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('lastname',     'Last Name',             'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('email',        'Email',                 'required|trim|email');
            $this->validatePost('tel',          'Contact Number',        'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('message',      'Further information',   'trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                // Mail to client/consultant
                $mail = new Mail;
                $mail->initDefault('New Message', $this->getView('modules/landing/views/email_templates/contact.php'));
                $mail->AddAddress(post('contact_email'));
                $mail->sendEmail('landing_contact');

                Request::addResponse('html', '#form_' . post('section_id'), '<h3 class="title-small" style="font-size: 24px; color: black;">Thank you!</h3>');

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

}
/* End of file */
<?php
class TalentController extends Controller
{

    use Validator;

    public function indexAction()
    {
        redirectAny(url('talent','anonymous_profile'));
    }

    public function protectionAction()
    {
        if ($this->startValidation()) {
            $pass = $this->validatePost('password', 'Password', 'required|trim|min_length[6]|max_length[32]');

            if ($this->isValid()) {

                $protection = Model::fetch(Model::select('talent_password_protection'));

                if ($pass === $protection->password) {
                    setSession('acess', 'yes');
                    redirectAny(url(post('url')));
                } else
                    Request::returnError('Invalid password. Please try again');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Protection');
    }

}
/* End of file */
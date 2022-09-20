<?php
class TalentsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        Request::setTitle('Dashboard');
    }

    public function password_protectionAction()
    {

        $this->view->edit = Model::fetch(Model::select('talent_password_protection'));

        if ($this->startValidation()) {
            $this->validatePost('password', 'Password', 'required|trim|min_length[6]|max_length[50]');

            if ($this->isValid()) {

                $areas = is_array(post('areas')) ? post('areas') : [];

                $data = array(
                    'password'   => post('password'),
                    'protection' => intval(post('protection')),
                    'areas'      => '|' . implode('||', $areas) . '|'
                );

                Model::delete('talent_password_protection');

                $result = Model::insert('talent_password_protection', $data); // Update row
                $insertId = Model::insertID();

                if (!$result && $insertId) {
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Password Protection');
    }

}
/* End of file */
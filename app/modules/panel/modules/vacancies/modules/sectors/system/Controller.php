<?php
class SectorsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = SectorsModel::getAll();

        Request::setTitle('Industry Sectors');
    }

    public function archiveAction()
    {
        $this->view->list = SectorsModel::getArchived();

        Request::setTitle('Archived Sectors');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = SectorsModel::get($id);

        if (!$user)
            redirect(url('panel/vacancies/sectors/archive'));

        $result = Model::update('sectors', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'sector#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/vacancies/sectors/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',           'required|trim|min_length[1]|max_length[200]');


            if ($this->isValid()) {
                $data = array(
                    'name'          => post('name'),
                );

                $result   = Model::insert('sectors', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'User created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'vacancies', 'sectors', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Industry Sector');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = SectorsModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/vacancies/sectors'));

        if ($this->startValidation()) {
            $this->validatePost('name',             'Name',           'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name'          => post('name'),
                    'content'       => post('content'),
                );

                $result = Model::update('sectors', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'sectors', 'edit', $id));
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Industry Sector');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = SectorsModel::get($id);

        if (!$user)
            Request::returnError('Archived Sectors error');

        $data['deleted'] = 'yes';
        $result = Model::update('sectors', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }
}
/* End of file */

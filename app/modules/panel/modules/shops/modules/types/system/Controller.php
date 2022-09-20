<?php
class TypesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = TypesModel::getAll();

        Request::setTitle('Types');
    }

    public function archiveAction()
    {
        $this->view->list = TypesModel::getArchived();

        Request::setTitle('Archived Types');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = TypesModel::get($id);

        if (!$user)
            redirect(url('panel/shops/types/archive'));

        $result = Model::update('types', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'types#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/shops/types/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result   = Model::insert('types', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'shops', 'types', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax()) {
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        Request::setTitle('Add Type');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->sector = TypesModel::get($id);

        if (!$this->view->sector)
            redirect(url('panel/shops/types'));

        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');


            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result = Model::update('types', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Location');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = TypesModel::get($id);

        if (!$user)
            Request::returnError('Location error');

        $data['deleted'] = 'yes';
        $result = Model::update('types', $data, "`id` = '$id'"); // Update row

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
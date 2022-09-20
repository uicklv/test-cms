<?php
class BrandsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = BrandsModel::getAll();

        Request::setTitle('Brands');
    }

    public function archiveAction()
    {
        $this->view->list = BrandsModel::getArchived();

        Request::setTitle('Archived Brands');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = BrandsModel::get($id);

        if (!$user)
            redirect(url('panel/shops/brands/archive'));

        $result = Model::update('brands', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'brands#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/shops/brands/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result   = Model::insert('brands', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'shops', 'brands', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax()) {
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        Request::setTitle('Add Brand');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->sector = BrandsModel::get($id);

        if (!$this->view->sector)
            redirect(url('panel/shops/brands'));

        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');


            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result = Model::update('brands', $data, "`id` = '$id'"); // Update row

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

        Request::setTitle('Edit Brand');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = BrandsModel::get($id);

        if (!$user)
            Request::returnError('Location error');

        $data['deleted'] = 'yes';
        $result = Model::update('brands', $data, "`id` = '$id'"); // Update row

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
<?php
class Club_categoriesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = Club_categoriesModel::getAll();

        Request::setTitle('Categories');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name',    'Name',         'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name'      => post('name'),
                );

                $result   = Model::insert('club_blog_categories', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'User created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'club_blog', 'club_categories', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Category');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = Club_categoriesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/sectors'));

        if ($this->startValidation()) {
            $this->validatePost('name',    'Name',         'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('content', 'Page Content', 'trim|min_length[0]');

            if ($this->isValid()) {
                $data = array(
                    'name'      => post('name'),
                    'content'   => post('content'),
                );

                $result = Model::update('club_blog_categories', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'blog', 'club_categories', 'edit', $id));
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Category');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = Club_categoriesModel::get($id);

        if (!$user)
            Request::returnError('Category error');

        $data['deleted'] = 'yes';
        $result = Model::update('club_blog_categories', $data, "`id` = '$id'"); // Update row

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

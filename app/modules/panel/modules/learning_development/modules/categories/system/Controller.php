<?php
class CategoriesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = CategoriesModel::getAll();

        Request::setTitle('Learning Development Categories');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name',    'Name',         'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name'      => post('name'),
                );

                $result   = Model::insert('ld_categories', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'User created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'learning_development', 'categories', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Categories');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = CategoriesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/sectors'));

        Model::import('panel/team');
        $this->view->users = TeamModel::getUsersWhere(" `role` = 'moder'");

        if ($this->startValidation()) {
            $this->validatePost('name',    'Name',         'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('image',   'Image',        'required|trim|min_length[1]|max_length[60]');

            if ($this->isValid()) {
                $data = array(
                    'name'      => post('name'),
                    'image'     => post('image'),
                );

                // Copy and remove image
                if ($this->view->edit->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/learning_development/' . $data['image'])) {
                        File::remove('data/learning_development/' . $this->view->edit->image);
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('ld_categories', $data, "`id` = '$id'"); // Update row

                if ($result) {

                    // Remove and after insert users
                    CategoriesModel::removeUsers($id);
                    if (is_array(post('users')) && count(post('users')) > 0) {
                        foreach (post('users') as $user_id) {
                            Model::insert('ld_access_categories', array(
                                'category_id' => $id,
                                'user_id' => $user_id
                            ));
                        }
                    }

//                    Request::addResponse('redirect', false, url('panel', 'learning_development', 'categories', 'edit', $id));
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit categories');
    }

    public function sortAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $ID = Request::getUri(1);
        $direction = Request::getUri(0);
        if ($direction != 'up') $direction = 'down';

        $blog = CategoriesModel::get($ID);

        if (!$blog)
            redirectAny(url('panel/learning_development/categories'));

        if (!$blog->sort) { // if sort = 0
            $biggest = CategoriesModel::getBiggestSort();
            $data['sort'] = intval($biggest->sort) + 1;
            Model::update('ld_categories', $data, "`id` = '$ID'");
        } else { // if sort > 0
            if ($direction == 'up') {
                $smallest = CategoriesModel::getNextSmallestSort($blog->sort);
                if (!$smallest)
                    Request::returnError('Already on the top');

                Model::update('ld_categories', ['sort' => $smallest->sort], "`id` = '$ID'");
                Model::update('ld_categories', ['sort' => $blog->sort], "`id` = '" . ($smallest->id) . "'");
            } else {
                $biggest = CategoriesModel::getNextBiggestSort($blog->sort);
                if (!$biggest)
                    Request::returnError('Already on the bottom');

                Model::update('ld_categories', ['sort' => $biggest->sort], "`id` = '$ID'");
                Model::update('ld_categories', ['sort' => $blog->sort], "`id` = '" . ($biggest->id) . "'");
            }
        }

        redirectAny(url('panel/learning_development/categories'));
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = CategoriesModel::get($id);

        if (!$user)
            Request::returnError('Category error');

        $data['deleted'] = 'yes';
        $result = Model::update('ld_categories', $data, "`id` = '$id'"); // Update row

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

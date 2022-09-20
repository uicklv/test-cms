<?php
class Learning_developmentController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = Learning_developmentModel::getAll();

        Request::setTitle('Learning Development');
    }

    public function archiveAction()
    {
        $this->view->list = Learning_developmentModel::getArchived();

        Request::setTitle('Archived Resources');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = Learning_developmentModel::get($id);

        if (!$user)
            redirect(url('panel/learning_development/archive'));

        $result = Model::update('learning_development', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'learning_development#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/learning_development/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title',            'Blog Title',           'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'title'         => post('title'),
                    'slug'          => makeSlug(post('title')),
                    'time'          => time()
                );

                $result   = Model::insert('learning_development', $data); // Insert row
                $insertID = Model::insertID();


                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'learning_development', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Resource');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = Learning_developmentModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/learning_development'));

        Model::import('panel/learning_development/categories');
        Model::import('panel/team');
        $this->view->sectors = CategoriesModel::getAll();
        $this->view->users = TeamModel::getUsersWhere(" `role` = 'moder'");

        if ($this->startValidation()) {
            $this->validatePost('title',            'Blog Title',           'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('image',            'Image',                'required|trim|min_length[1]|max_length[100]');
//            $this->validatePost('video_poster',     'Video Poster',         'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('video',            'Video',                'trim|min_length[1]|max_length[100]');
            $this->validatePost('file',             'File',                 'trim|min_length[1]|max_length[100]');
            $this->validatePost('description',      'Page Content',         'required|trim|min_length[0]');
            $this->validatePost('category',         'Category',             'required|trim|min_length[1]');
            $this->validatePost('time',             'Date Posted',          'trim|min_length[1]|max_length[50]');
            $this->validatePost('posted',           'Posted',               'trim|min_length[1]|max_length[50]');

            // Times comparing/checking
            $intTime   = convertStringTimeToInt(post('time'));
            $checkTime = date("d/m/Y", $intTime);

            if ($checkTime != post('time')) {
                $this->addError('time', 'Wrong Date Posted');
            }

            if ($this->isValid()) {
                $data = array(
                    'title'         => post('title'),
                    'image'         => post('image'),
                    'video_poster'  => post('video_poster'),
                    'video'         => post('video'),
                    'file'          => post('file'),
                    'content'       => post('description'),
                    'category'      => post('category'),
                    'slug'          => makeSlug(post('title')),
                    'posted'        => post('posted'),
                    'time'          => $intTime
                );

                // Copy and remove image
                if ($data['file']) {
                    if ($this->view->edit->file !== $data['file']) {
                        if (File::copy('data/tmp/' . $data['file'], 'data/learning_development/' . $data['file'])) {
                            File::remove('data/learning_development/' . $this->view->edit->file);
                        } else
                            print_data(error_get_last());
                    }
                }


                // Copy and remove image
                if ($this->view->edit->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/learning_development/' . $data['image'])) {
                        File::remove('data/learning_development/' . $this->view->edit->image);
                    } else
                        print_data(error_get_last());
                }
//
//                // Copy and remove image
//                if ($this->view->edit->video_poster !== $data['video_poster']) {
//                    if (File::copy('data/tmp/' . $data['video_poster'], 'data/learning_development/' . $data['video_poster'])) {
//                        File::remove('data/learning_development/' . $this->view->edit->video_poster);
//                    } else
//                        print_data(error_get_last());
//                }

                $result = Model::update('learning_development', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    // Remove and after insert users
                    Learning_developmentModel::removeUsers($id);
                    if (is_array(post('users')) && count(post('users')) > 0) {
                        foreach (post('users') as $user_id) {
                            Model::insert('ld_access_resources', array(
                                'resource_id' => $id,
                                'user_id' => $user_id
                            ));
                        }
                    }

//                    Request::addResponse('redirect', false, url('panel', 'learning_development', 'edit', $id));
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Resource');
    }

    public function sortAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $ID = Request::getUri(1);
        $direction = Request::getUri(0);
        if ($direction != 'up') $direction = 'down';

        $blog = Learning_developmentModel::get($ID);

        if (!$blog)
            redirectAny(url('panel/learning_development'));

        if (!$blog->sort) { // if sort = 0
            $biggest = Learning_developmentModel::getBiggestSort();
            $data['sort'] = intval($biggest->sort) + 1;
            Model::update('learning_development', $data, "`id` = '$ID'");
        } else { // if sort > 0
            if ($direction == 'up') {
                $smallest = Learning_developmentModel::getNextSmallestSort($blog->sort);
                if (!$smallest)
                    Request::returnError('Already on the top');

                Model::update('learning_development', ['sort' => $smallest->sort], "`id` = '$ID'");
                Model::update('learning_development', ['sort' => $blog->sort], "`id` = '" . ($smallest->id) . "'");
            } else {
                $biggest = Learning_developmentModel::getNextBiggestSort($blog->sort);
                if (!$biggest)
                    Request::returnError('Already on the bottom');

                Model::update('learning_development', ['sort' => $biggest->sort], "`id` = '$ID'");
                Model::update('learning_development', ['sort' => $blog->sort], "`id` = '" . ($biggest->id) . "'");
            }
        }

        redirectAny(url('panel/learning_development'));
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = Learning_developmentModel::get($id);

        if (!$user)
            Request::returnError('Resource error');

        $data['deleted'] = 'yes';
        $result = Model::update('learning_development', $data, "`id` = '$id'"); // Update row

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

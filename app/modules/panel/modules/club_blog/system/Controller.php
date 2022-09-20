<?php
class Club_blogController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = Club_blogModel::getAll();

        Request::setTitle('Articles');
    }

    public function archiveAction()
    {
        $this->view->list = Club_blogModel::getArchived();

        Request::setTitle('Archived Articles');
    }

    public function viewsAction()
    {
        $id = intval(Request::getUri(0));

        $this->view->blog = Club_blogModel::get($id);
        $this->view->list = Club_blogModel::getViews($id);

        Request::setTitle('Views');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = Club_blogModel::get($id);

        if (!$user)
            redirect(url('panel/club_blog/archive'));

        $result = Model::update('club_blog', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'club_blog#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/club_blog/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title',       'Blog Title',    'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'title'         => post('title'),
                    'slug'          => makeSlug(post('title')),
                    'time'          => time()
                );

                $result   = Model::insert('club_blog', $data); // Insert row
                $insertID = Model::insertID();


                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'club_blog', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Article');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->blog = Club_blogModel::get($id);

        if (!$this->view->blog)
            redirect(url('panel/club_blog'));

        Model::import('panel/club_blog/club_categories');
        $this->view->sectors = Club_categoriesModel::getAll();

        Model::import('panel/team');
        $this->view->team = TeamModel::getUsersWhere("`role` = 'moder'");

        if ($this->startValidation()) {
            $this->validatePost('title',            'Blog Title',           'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('subtitle',         'Subtitle',             'trim|min_length[1]|max_length[200]');
            $this->validatePost('subtitle2',        'Super Subtitle',       'trim|min_length[1]|max_length[200]');
            $this->validatePost('image',            'Image',                'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('consultant_id',    'Author',               'required|trim');
            $this->validatePost('meta_title',       'Meta Title',           'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_keywords',    'Meta Keywords',        'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_desc',        'Meta Description',     'trim|min_length[0]|max_length[200]');
            $this->validatePost('content',          'Page Content',         'required|trim|min_length[0]');
            $this->validatePost('content_before',   'Content before image', 'trim|min_length[0]');
            $this->validatePost('sector',           'Industries/Sectors',   'required|trim|min_length[1]');
            $this->validatePost('slug',             'Slug',                 'required|trim|min_length[1]|max_length[200]');
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
                    'title'          => post('title'),
                    'subtitle'       => post('subtitle'),
                    'subtitle2'      => post('subtitle2'),
                    'consultant_id'  => post('consultant_id', 'int', 0),
                    'image'          => post('image'),
                    'meta_title'     => post('meta_title'),
                    'meta_keywords'  => post('meta_keywords'),
                    'meta_desc'      => post('meta_desc'),
                    'content'        => post('content'),
                    'content_before' => post('content_before'),
                    'sector'         => post('sector'),
                    'slug'           => post('slug'),
                    'posted'         => post('posted'),
                    'time'           => $intTime
                );


                // Copy and remove image
                if ($this->view->blog->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/club_blog/' . $data['image'])) {
                        File::remove('data/club_blog/' . $this->view->blog->image);
                        File::remove('data/club_blog/mini_' . $this->view->blog->image);

                        File::resize(_SYSDIR_ . 'data/club_blog/' . $data['image'], _SYSDIR_ . 'data/club_blog/mini_' . $data['image'], 400, 300
                        );
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('club_blog', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'blog', 'edit', $id));
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

        Request::setTitle('Edit Article');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = Club_blogModel::get($id);

        if (!$user)
            Request::returnError('Article error');

        $data['deleted'] = 'yes';
        $result = Model::update('club_blog', $data, "`id` = '$id'"); // Update row

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

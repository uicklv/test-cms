<?php
class BlogController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;
    use RecordVersion;

    public function indexAction()
    {
        Model::import('panel/blog/categories');
        $this->view->categories = CategoriesModel::getAll();

        $this->view->list = BlogModel::getAll();
        Request::setTitle('Blog Posts');
    }

    public function index_paginationAction()
    {
        $this->view->list = BlogModel::getAll();

        Request::setTitle('Blog Posts');
    }

    public function filterAction()
    {
        Request::ajaxPart();

        $category = post('category') ?? false;

        $this->view->list = BlogModel::getAllByCategoryId($category);
        Request::addResponse('html', '#filter_result', $this->getView());
    }

    public function paginationAction()
    {
        Request::ajaxPart();

        $draw        = post('draw'); // request-response identifier
        $start       = intval(post('start')); // limit start
        $end         = post('length'); // rows display per page
        $columnIndex = post('order')[0]['column']; // column index
        $orderby     = post('columns')[$columnIndex]['data']; // column name
        $sort        = post('order')[0]['dir']; // asc or desc
        $searchValue = post('search')['value']; // search value

        //prepare query for search
        $where = " ";
        if ($searchValue) {
            $where = " AND `title` LIKE '%$searchValue%'";
        }

        //total number of records without filtering
        $totalRecords = BlogModel::countBlogs();

        //total number of record with filtering
        $totalRecordWithFilter = BlogModel::countBlogs($where);

        //search
        $data = BlogModel::search($where, $orderby, $sort, $start, $end);

        //prepare data for response
        $responseData = [];
        if (count($data) > 0) {
            foreach ($data as $value) {
                $responseData[] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'views' => $value->views,
                    'time' => date('Y-m-d', $value->time),
                    'slug' => $value->slug,
                ];
            }
        }

        //response
        $response = array(
            "draw" => intval($draw), // request-response identifier
            "recordsTotal" => intval($totalRecords), //total number of records without filtering
            "recordsFiltered" => intval($totalRecordWithFilter), //total number of record with filtering
            "data" => $responseData // data for table
        );

        echo json_encode($response);
        exit;
    }

    public function addAction()
    {
        Model::import('panel/blog/categories');
        $this->view->sectors = CategoriesModel::getAll();

        if ($this->startValidation()) {
            $this->validatePost('title',            'Blog Title',           'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'title'     => post('title'),
                    'sector'    => 0,
                    'slug'      => Model::createIdentifier('blog', makeSlug(post('title'))),
                    'time'      => time()
                );

                $result   = Model::insert('blog', $data); // Insert row
                $insertID = Model::insertID();

              
                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'blog#' . $insertID, 'time' => time()]);

                    Request::addResponse('redirect', false, url('panel', 'blog', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Blog Post');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->blog = BlogModel::get($id);

        if (!$this->view->blog)
            redirect(url('panel/blog'));

        Model::import('panel/blog/categories');
        $this->view->sectors = CategoriesModel::getAll();

        Model::import('panel/team');
        $this->view->team = TeamModel::getUsersWhere("`role` IN ('moder', 'admin')");

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
            $this->validatePost('time',             'Date Published',       'trim|min_length[1]|max_length[50]');
            $this->validatePost('posted',           'Posted',               'trim|min_length[1]|max_length[50]');

            // Times comparing/checking
            $intTime   = convertStringTimeToInt(post('time'));
            $checkTime = date("d/m/Y", $intTime);

            if ($checkTime != post('time')) {
                $this->addError('time', 'Wrong Date Published');
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
                    'slug'           => Model::createIdentifier('blog', post('slug'), 'slug', $this->view->blog->id),
                    'posted'         => post('posted'),
                    'time'           => $intTime
                );


                // Copy and remove image
                if ($this->view->blog->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/blog/' . $data['image'])) {
                        File::remove('data/blog/' . $this->view->blog->image);
                        File::remove('data/blog/mini_' . $this->view->blog->image);
                        File::resize(
                            _SYSDIR_ . 'data/blog/' . $data['image'],
                            _SYSDIR_ . 'data/blog/mini_' . $data['image'],
                            358*2, 264*2
                        );
                    } else
                        Request::returnError('Image copy error: ' . error_get_last()['message']);
                }

                $result = Model::update('blog', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'edit', 'entity' => 'blog#' . $id, 'time' => time()]);

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

        Request::setTitle('Edit Blog Post');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = BlogModel::get($id);

        if (!$user)
            Request::returnError('Blog error');

        $data['deleted'] = 'yes';
        $result = Model::update('blog', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'blog#' . $id, 'time' => time()]);

            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }

        //redirect(url('panel/blog'));
    }

    public function archiveAction()
    {
        $this->view->list = BlogModel::getArchived();

        Request::setTitle('Archive Blogs');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = BlogModel::get($id);

        if (!$user)
            redirect(url('panel/blog/archive'));

        $result = Model::update('blog', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'blog#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/blog/archive'));
    }

    // Statistics

    public function statisticAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->blog = BlogModel::get($id);

        if (!$this->view->blog)
            redirect(url('panel/blog'));

        $this->view->list = BlogModel::getViewsByDays($this->view->blog->id);
        $this->view->referrals = BlogModel::getReferrersList($this->view->blog->id);
        $this->view->views = BlogModel::getViews($this->view->blog->id);

        // Last 9 days empty array
        $this->view->data = [];
        for ($i = time() - 9 * 24 * 3600; $i <= time(); $i += 24 * 3600)
            $this->view->data[date("d.m", $i)] = 0;

        // Count the number of entities every day
        foreach ($this->view->list as $value)
            $this->view->data[date("d.m", $value->time)]++;

        // Count refs
        $this->view->refArray = [];
        foreach ($this->view->views as $v)
            $this->view->refArray[$v->ref]++;

        $this->view->count = array_sum($this->view->data);

        Request::setTitle('Statistic Blog Post');
    }

    public function add_refAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->blog = BlogModel::get($id);

        if (!$this->view->blog)
            redirect(url('panel/blog'));

        if ($this->startValidation()) {
            $this->validatePost('title', 'Referrer Title', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'blog_id'   => $this->view->blog->id,
                    'title'     => makeSlug(post('title')),
                );

                $result   = Model::insert('blogs_referrers', $data); // Insert row
                $insertID = Model::insertID();


                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'blog#' . $insertID, 'time' => time()]);

                    Request::addResponse('redirect', false, url('panel', 'blog', 'statistic', $this->view->blog->id));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Blog Referrer');
    }

    public function delete_refAction()
    {
        $id = (Request::getUri(0));
        $user = BlogModel::getReferrer($id);

        if (!$user)
            redirect(url('panel/blog'));

        $result = Model::delete('blogs_referrers',"`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'blog-referral#' . $id, 'time' => time()]);

//            $this->session->set_flashdata('success', 'User created successfully.');
//            Request::addResponse('redirect', false, url('panel', 'blog', 'edit', $insertID));
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/blog/statistic/' . $user->blog_id));
    }
}
/* End of file */
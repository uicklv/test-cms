<?php

use nadar\quill\Lexer;

class BlogsController extends Controller
{

    public function indexAction()
    {
        Model::import('panel/blog/categories');

        // Pagination
        $count = Model::count('blog', '*', "`deleted` = 'no' AND `posted` = 'yes'");

        //if use ajax pagination need change get -> post;
        Pagination::calculate(get('page', 'int'), 10, $count);
        $this->view->blogs = BlogsModel::getAll(Pagination::$start, Pagination::$end);

        // Get sector list and make ID => NAME array
        $sectors = CategoriesModel::getAll();
        $sectorsArray = array();
        foreach ($sectors as $sector)
            $sectorsArray[$sector->id] = $sector->name;
        $this->view->sectors = $sectorsArray;

        Request::setTitle('Our Blog');
    }

    /**
     * AJAX example of indexAction
     */
    public function ajaxAction()
    {
        Request::ajaxPart();
        Model::import('panel/blog/categories');

        // Pagination
        $count = Model::count('blog', '*', "`deleted` = 'no' AND `posted` = 'yes'");
        Pagination::calculate(post('page', 'int'), 10, $count);
        $this->view->blogs = BlogsModel::getAll(Pagination::$start, Pagination::$end);

        // Get sector list and make ID => NAME array
        $sectors = CategoriesModel::getAll();
        $sectorsArray = array();
        foreach ($sectors as $sector)
            $sectorsArray[$sector->id] = $sector->name;
        $this->view->sectors = $sectorsArray;

        Request::addResponse('html', '#content', $this->getView('modules/blogs/views/index.php'));
    }

    public function viewAction()
    {
        include _SYSDIR_ . 'system/lib/parser/vendor/autoload.php';

        $slug = Request::getUri(0);
        $this->view->blog = BlogsModel::getBySlug($slug);

        if (!$this->view->blog || (!User::get('id') && $this->view->blog->posted == 'no'))
            redirect(url('blogs'));

        // convert delta object quill json to html
//        if ($this->view->blog->content) {
//            $content = reFilter($this->view->blog->content);
//
//            $delta = new Lexer($content);
//
//            $this->view->blog->content = $delta->render();
//        }

        $this->view->prev = BlogsModel::getPrevBlog($this->view->blog->id);
        $this->view->next = BlogsModel::getNextBlog($this->view->blog->id);

        // Set stat
        if (setViewStat('blog_', $this->view->blog->id, 'blogs_analytics')) {
            $data_views['views'] = '++';
            Model::update('blog', $data_views, "`id` = '" . $this->view->blog->id . "'");
        }

        Request::setTitle('Blog - ' . $this->view->blog->meta_title);
        Request::setKeywords($this->view->blog->meta_keywords);
        Request::setDescription($this->view->blog->meta_desc);
    }

    public function our_blogsAction()
    {
        Request::ajaxPart();
        $this->view->blogs = BlogsModel::getAll(3);

        // Get sector list and make ID => NAME array
        Model::import('panel/blog/categories');
        $sectors = CategoriesModel::getAll();
        $sectorsArray = array();
        foreach ($sectors as $sector)
            $sectorsArray[$sector->id] = $sector->name;
        $this->view->sectors = $sectorsArray;

        Request::addResponse('html', '.blog-list', $this->getView());
    }
}

/* End of file */

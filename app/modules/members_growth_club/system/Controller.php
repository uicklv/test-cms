<?php

class Members_growth_clubController extends Controller
{
    use Validator;

    public function indexAction()
    {
        Model::import('panel/club_blog/club_categories');

        // Pagination
        $count = Model::count('club_blog', '*', "`deleted` = 'no' AND `posted` = 'yes'");
        Pagination::calculate(get('page', 'int'), 10, $count);
        $this->view->blogs = Members_growth_clubModel::getAll(Pagination::$start, Pagination::$end);

        // Get sector list and make ID => NAME array
        Model::import('panel/club_blog/club_categories');
        $sectors = Club_categoriesModel::getAll();
        $sectorsArray = array();
        foreach ($sectors as $sector)
            $sectorsArray[$sector->id] = $sector->name;
        $this->view->sectors = $sectorsArray;

        Request::setTitle('Our Blog');
        Request::setKeywords('');
        Request::setDescription('');
    }

    public function viewAction()
    {
        $slug = Request::getUri(0);
        $this->view->blog = Members_growth_clubModel::getBySlug($slug);
        if (!$this->view->blog)
            redirect(url('members-growth-club'));

        $this->view->prev = Members_growth_clubModel::getPrevBlog($this->view->blog->id);
        $this->view->next = Members_growth_clubModel::getNextBlog($this->view->blog->id);

        // insert view into table
        $user_id = User::get('id');
        $blog_id = $this->view->blog->id;

        Model::insert('club_blog_views', ['user_id' => $user_id, 'blog_id' => $blog_id, 'time' => time()]);

        Request::setTitle('Blog - ' . $this->view->blog->meta_title);
        Request::setKeywords($this->view->blog->meta_keywords);
        Request::setDescription($this->view->blog->meta_desc);
    }

    public function registerAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('firstname',     'First Name',        'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',      'Last Name',         'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',         'Email',             'required|trim|email');
            $this->validatePost('password',      'Password',          'required|trim|password');
            $this->validatePost('password2',     'Confirm Password',  'required|trim|password');
            $this->validatePost('check',         'Privacy Policy',    'required|trim|min_length[1]');

            Model::import('page');
            $checkUser = Members_growth_clubModel::getUserByEmail(post('email'));
            if ($checkUser)
                $this->addError('email', 'This email already exist in system');

            if (post('password') !== post('password2'))
                $this->addError('password', 'Passwords should match');

            if ($this->isValid()) {
                $data = array(
                    'firstname'         => post('firstname'),
                    'lastname'          => post('lastname'),
                    'email'             => post('email'),
                    'role'              => 'user',
                    'password'          => md5(post('password')),
                    'reg_time'          => time(),
                    'last_time'         => time(),
                );

                $result   = Model::insert('club_users', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    $user = Members_growth_clubModel::getClubUser($insertID);
                    $token = $user->token ?: randomHash(); // generate token
                    Model::update('club_users', ['token' => $token], " `id` = $user->id");
                    // additional login
                    User::setCookie($user->id, 'club'); // set user cookie
                    User::setTokenCookie($token, 'club'); // set user token
                    Request::addResponse('redirect', false, url('members-growth-club'));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Register');

    }

    public function loginAction()
    {
        if ($this->startValidation()) {
            $email = $this->validatePost('email',       'Email',    'required|trim|email');
            $pass  = $this->validatePost('password',    'Password', 'required|trim|min_length[6]|max_length[32]');

            if ($this->isValid()) {
                $user = Members_growth_clubModel::getUserByEmail($email);
                // Check password
                if ($user && $user->deleted == 'no' && $user->password == md5($pass)) {

                    $token = $user->token ?: randomHash(); // generate token
                    Model::update('club_users', ['token' => $token], " `id` = $user->id");
                    // additional login
                    User::setCookie($user->id, 'club'); // set user cookie
                    User::setTokenCookie($token, 'club'); // set user token

                    redirectAny(get('url') ? get('url') : url('members-growth-club'));
                } else
                    Request::returnError('Invalid email and/or password. Please check your data and try again');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
        Request::setTitle('Login');
    }

    public function logoutAction()
    {
        User::set(null, 'club');
        User::setCookie(null, 'club');
        User::setTokenCookie(null, 'club');

        redirectAny(url('/members-growth-club'));
    }
}

/* End of file */
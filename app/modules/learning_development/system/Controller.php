<?php

class Learning_developmentController extends Controller
{
    use Validator;

    public function indexAction()
    {
        Model::import('panel/learning_development/categories');

        $this->view->list = CategoriesModel::getAllWithAccess(User::get('id'));

        Request::setTitle('Learning & Development');
    }

    public function loginAction()
    {
        $this->view->email = getCookie('email');
        $this->view->pass = getCookie('pass');

        if ($this->startValidation()) {
            $email = $this->validatePost('email',       'Email',    'required|trim|email');
            $pass  = $this->validatePost('password',    'Password', 'required|trim|min_length[6]|max_length[32]');

            if ($this->isValid()) {
                $user = PageModel::getUserByEmail($email);

                // Check password
                if ($user && $user->deleted == 'no' && $user->password == md5($pass) && $user->role == 'moder') {
                    if (post('remember') == 'on') {
                        setMyCookie('email', $email,time() + 12*30*24*3600);
                        setMyCookie('pass', $pass, time() + 12*30*24*3600);
                    }

                    $token = $user->token ?: randomHash(); // generate token
                    Model::update('users', ['token' => $token], " `id` = $user->id");

                    User::setCookie($user->id, 'ld'); // Create user session
                    User::setTokenCookie($token, 'ld'); // set user token
                    redirectAny(get('url') ? get('url') : url('learning_development'));
                } else
                    Request::returnError('Invalid email and/or password. Please check your data and try again');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Portal Login');
    }

    public function searchAction()
    {
        Request::ajaxPart();

        $type = post('type');
        $category = post('category');

        if ($type == 'completed')
            $this->view->list = Learning_developmentModel::getCompletedWithAccess($category, User::get('id'));
        else if ($type == 'incomplete')
            $this->view->list = Learning_developmentModel::getIncompleteWithAccess($category, User::get('id'));
        else
            $this->view->list = Learning_developmentModel::getByCategoryWithAccess($category, User::get('id'));

        setSession('type', $type);
        setSession('category', $category);

        Request::addResponse('html', '#search_results_box', $this->getView());
    }

    public function categoryAction()
    {

        if (get('type') && get('category')) {

            $type = get('type');
            $category = get('category');

            Model::import('panel/learning_development/categories');
            $this->view->category = CategoriesModel::get($category);

            if (!$this->view->category)
                redirect(url('learning-development-resources'));

            //Check Access
            $check = Model::fetch(Model::select('ld_access_categories', " `user_id` = '" . User::get('id') . "' AND `category_id` = '" .  $category . "'"));
            if ($check)
                redirect(url('learning-development-resources'));

            if ($type == 'completed')
                $this->view->list = Learning_developmentModel::getCompletedWithAccess($category, User::get('id'));
            else if ($type == 'incomplete')
                $this->view->list = Learning_developmentModel::getIncompleteWithAccess($category, User::get('id'));
            else
                $this->view->list = Learning_developmentModel::getByCategoryWithAccess($category, User::get('id'));


        } else {

            $id = Request::getUri(0);

            Model::import('panel/learning_development/categories');
            $this->view->category = CategoriesModel::get($id);

            if (!$this->view->category)
                redirect(url('learning-development-resources'));

            //Check Access
            $check = Model::fetch(Model::select('ld_access_categories', " `user_id` = '" . User::get('id') . "' AND `category_id` = '" .  $id . "'"));
            if ($check)
                redirect(url('learning-development-resources'));

            Model::import('panel/learning_development/categories');
            $this->view->list = Learning_developmentModel::getByCategoryWithAccess($id, User::get('id'));
        }

        Request::setTitle('Resource - ' . $this->view->resource->title);
        Request::setKeywords($this->view->resource->meta_keywords);
        Request::setDescription($this->view->resource->meta_desc);
    }

    public function viewAction()
    {
        $slug = Request::getUri(0);

        $this->view->blog = Learning_developmentModel::getBySlug($slug);
        if (!$this->view->blog)
            redirect(url('learning-development-resources'));

        //Check Access
        $check = Model::fetch(Model::select('ld_access_resources', " `user_id` = '" . User::get('id') . "' AND `resource_id` = '" .  $this->view->blog->id . "'"));
        if ($check)
            redirect(url('learning-development-resources'));


        $this->view->prev = Learning_developmentModel::getPrevBlogByCategory($this->view->blog->id, $this->view->blog->category_name->id);
        $this->view->next = Learning_developmentModel::getNextBlogByCategory($this->view->blog->id, $this->view->blog->category_name->id);

        Request::setTitle('Blog - ' . $this->view->blog->title);
        Request::setKeywords($this->view->blog->meta_keywords);
        Request::setDescription($this->view->blog->meta_desc);
    }

    public function change_statusAction()
    {
        Request::ajaxPart();

        $check = Model::fetch(Model::select('ld_users', " `resource_id` = '" . post('resource_id') . "' 
        AND `user_id` = '" . User::get('id') . "'"));

        if ($check) {
            Model::delete('ld_users', " `resource_id` = '" . post('resource_id') . "' 
            AND `user_id` = '" . User::get('id') . "'");
        } else {

            $data = [
                'user_id' => User::get('id'),
                'resource_id' => post('resource_id'),
                'time' => time()
            ];

            Model::insert('ld_users', $data);

            Model::import('panel/team');
            $this->view->user = TeamModel::getUser(User::get('id'));
            $this->view->resource = Learning_developmentModel::get(post('resource_id'));
            // Send email to admin
            require_once(_SYSDIR_.'system/lib/phpmailer/class.phpmailer.php');
            $mail = new PHPMailer;

            $mail->IsHTML(true);
            $mail->SetFrom(Request::getParam('noreply_mail'), Request::getParam('noreply_name'));
            $mail->AddAddress(Request::getParam('admin_mail'));

            $mail->Subject = 'New Message';
            $mail->Body = $this->getView('modules/learning_development/views/email_templates/completed.php');
            $mail->AltBody = 'Note: Our emails are a lot nicer with HTML enabled!';
            $mail->Send();
        }

        if (getSession('type') && getSession('category')) {
            Request::addResponse('redirect', false,
                url('resources/category?type=' . getSession('type') . '&category=' . getSession('category')));
        } else {
            Request::addResponse('redirect', false,
                url('resources/category/' . post('category_id')));
        }

    }

    public function logoutAction()
    {
        User::set(null, 'ld');
        User::setCookie(null, 'ld');
        User::setTokenCookie(null, 'ld');

        redirectAny(url('/'));
    }
}

/* End of file */
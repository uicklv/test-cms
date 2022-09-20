<?php
class TeamController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->team = TeamModel::getUsersWhere("`role` IN ('admin', 'moder')");

        Request::setTitle('Team Manager');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('firstname',    'First Name',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',     'Last Name',        'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',        'Email',            'required|trim|email');
            $this->validatePost('password',     'Password',         'required|trim|password');

            if ($this->isValid()) {
                $data = array(
                    'firstname'     => post('firstname'),
                    'lastname'      => post('lastname'),
                    'email'         => post('email'),
                    'role'          => post('role'),
                    'password'      => md5(post('password')),
                    'slug'          => makeSlug(post('firstname') . ' ' . post('lastname')),
                    'reg_time'      => time(),
                    'last_time'     => time(),
                );

                // Copy and remove image
                if ($data['image']) {
                    if (!File::copy('data/tmp/' . $data['image'], 'data/users/' . $data['image']))
                        print_data(error_get_last());
                }

                $checkEmail = TeamModel::getUserByEmail($data['email']);
                if ($checkEmail)
                    Request::returnError('This email is already taken');

                $result   = Model::insert('users', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'user#' . $insertID, 'time' => time()]);

//                    $this->session->set_flashdata('success', 'User created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'team', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Team Member');
    }

    public function editAction()
    {
        $userID = intval(Request::getUri(0));
        $this->view->user = TeamModel::getUser($userID);

        if (!$this->view->user)
            redirect(url('panel/team'));

        $this->view->imagesList = TeamModel::getUserImages($userID);

        if ($this->startValidation()) {
            $this->validatePost('firstname',    'First Name',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',     'Last Name',        'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',        'Email',            'required|trim|email');
            $this->validatePost('tel',          'Telephone Number', 'trim|min_length[0]|max_length[100]');
            $this->validatePost('password',     'Password',         'trim|password');
            $this->validatePost('title',        'Title',            'trim|min_length[0]|max_length[150]');
            $this->validatePost('job_title',    'Job Title',        'trim|min_length[0]|max_length[150]');
            $this->validatePost('role',         'Role',             'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('description',  'Description',      'trim|min_length[0]');
            $this->validatePost('meta_title',   'Meta Title',       'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_keywords','Meta Keywords',    'trim|min_length[0]|max_length[200]');
            $this->validatePost('meta_desc',    'Meta Description', 'trim|min_length[0]|max_length[200]');
            //$this->validatePost('for_fun',      'For fun',          'trim|min_length[0]');
            $this->validatePost('linkedin',     'LinkedIn URL',     'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('twitter',      'Twitter URL',      'trim|min_length[0]|max_length[100]|url');
            $this->validatePost('skype',        'Skype',            'trim|min_length[0]|max_length[100]');
            $this->validatePost('slug',         'Slug',             'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {

                $displayTeam = post('display_team');
                if ($displayTeam !== 'yes')
                    $displayTeam = 'no';

                $data = array(
                    'firstname'     => post('firstname'),
                    'lastname'      => post('lastname'),
                    'email'         => post('email'),
                    'tel'           => post('tel'),
                    'title'         => post('title'),
                    'job_title'     => post('job_title'),
                    'role'          => post('role'),
                    'description'   => post('description'),
                    'meta_title'    => post('meta_title'),
                    'meta_keywords' => post('meta_keywords'),
                    'meta_desc'     => post('meta_desc'),
                    //'for_fun'       => post('for_fun'),
                    'linkedin'      => processUrl(post('linkedin')),
                    'twitter'       => processUrl(post('twitter')),
                    'display_team'  => $displayTeam,
                    'skype'         => post('skype'),
                    'image'         => post('image'),
                    'slug'          => post('slug'),
                );

                if (post('password'))
                    $data['password'] = md5(post('password'));

                $checkEmail = TeamModel::getUserByEmail($data['email']);
                if ($checkEmail && $checkEmail->id !== $this->view->user->id)
                    Request::returnError('This email is already taken');

                // Copy and remove image
                if ($this->view->user->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/users/' . $data['image'])) {
                        File::remove('data/users/' . $this->view->user->image);

                        File::remove('data/users/mini_' . $this->view->user->image);
                        File::resize(_SYSDIR_ . 'data/users/' . $data['image'],
                            _SYSDIR_ . 'data/users/mini_' . $data['image'], 50, 50);
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('users', $data, "`id` = '$userID'"); // Update row

                if ($result) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'edit', 'entity' => 'user#' . $userID, 'time' => time()]);

//                    Request::addResponse('redirect', false, url('panel', 'team', 'edit', $userID));
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

        Request::setTitle('Edit Team Member');
    }

    public function to_archiveAction()
    {
        $id = intval(Request::getUri(0));
        $user = TeamModel::getUser($id);

        if (!$user)
            Request::returnError('Team error');

        $data['deleted'] = 'yes';
        $result = Model::update('users', $data, "`id` = '$id'"); // Update row

        if ($result) {
            PageModel::closeAllSessions($id);

            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'archive', 'entity' => 'user#' . $id, 'time' => time()]);

            Request::addResponse('func', 'noticeSuccess', 'Archived');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = TeamModel::getUser($id);

        if (!$user)
            Request::returnError('Team error');

        $result = Model::delete('users', "`id` = '$id'"); // delete row

        if ($result) {

            PageModel::closeAllSessions($id);

            //remove user images
            File::remove('data/users/' . $user->image);
            File::remove('data/users/mini_' . $user->image);

            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'user#' . $id, 'time' => time()]);

            Request::addResponse('func', 'noticeSuccess', 'Deleted');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

    public function archiveAction()
    {
        $this->view->team = TeamModel::getArchived();

        Request::setTitle('Archive Team Members');
    }

    public function resumeAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = TeamModel::getUser($id);

        if (!$user)
            Request::returnError('Team error');

        $result = Model::update('users', ['deleted' => 'no'], "`id` = '" . $id . "'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'archive', 'entity' => 'user#' . $id, 'time' => time()]);

            Request::addResponse('func', 'noticeSuccess', 'Deleted');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }

    }

    public function sortAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $userID = Request::getUri(1);
        $direction = Request::getUri(0);
        if ($direction != 'up') $direction = 'down';

        $user = TeamModel::getUser($userID);

        if (!$user)
            redirectAny(url('panel/team'));

        if (!$user->sort) { // if sort = 0
            $biggest = TeamModel::getBiggestSort();
            $data['sort'] = intval($biggest->sort) + 1;
            Model::update('users', $data, "`id` = '$userID'");
        } else { // if sort > 0
            if ($direction == 'up') {
                $smallest = TeamModel::getNextSmallestSort($user->sort);
                if (!$smallest)
                    Request::returnError('Already on the top');

                Model::update('users', ['sort' => $smallest->sort], "`id` = '$userID'");
                Model::update('users', ['sort' => $user->sort], "`id` = '" . ($smallest->id) . "'");
            } else {
                $biggest = TeamModel::getNextBiggestSort($user->sort);
                if (!$biggest)
                    Request::returnError('Already on the bottom');

                Model::update('users', ['sort' => $biggest->sort], "`id` = '$userID'");
                Model::update('users', ['sort' => $user->sort], "`id` = '" . ($biggest->id) . "'");
            }
        }

        redirectAny(url('panel/team'));
    }

    public function remove_funAction()
    {
        Request::ajaxPart(); // if not Ajax part load
        $id = post('id');
        $image = TeamModel::getUserImage($id);

        Model::delete('user_images', "`id` = '$id'");
        File::remove('data/fun/' . $image->image);
        Request::addResponse('remove', '#ft_' . $id, false);
    }

    public function vacanciesAction()
    {
        $id = intval(Request::getUri(0));

        $this->view->user = TeamModel::getUser($id);
        if (!$this->view->user)
            redirectAny(url('panel/team'));

        Model::import('panel/vacancies');
        $this->view->list = VacanciesModel::getVacanciesForConsultant($id);

        Request::setTitle('Consultant Vacancies');
    }
}
/* End of file */
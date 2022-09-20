<?php
class CustomersController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->team = CustomersModel::getUsersWhere("`role` IN ('customer')");

        Request::setTitle('Manage Clients');
    }

    public function archiveAction()
    {
        $this->view->team = CustomersModel::getArchived();

        Request::setTitle('Archived Clients');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = CustomersModel::getUser($id);

        if (!$user)
            redirect(url('panel/customers/archive'));

        $result = Model::update('users', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'customer#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/customers/archive'));

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
                    'role'          => 'customer',
                    'password'      => md5(post('password')),
                    'reg_time'      => time(),
                    'last_time'     => time(),
                );

                // Copy and remove image
                if ($data['image']) {
                    if (!File::copy('data/tmp/' . $data['image'], 'data/users/' . $data['image']))
                        print_data(error_get_last());
                }

                $checkEmail = CustomersModel::getUserByEmail($data['email']);
                if ($checkEmail)
                    Request::returnError('This email is already taken');

                $result   = Model::insert('users', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'User created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'customers', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Client');
    }

    public function editAction()
    {
        $userID = intval(Request::getUri(0));
        $this->view->user = CustomersModel::getUser($userID);

        if (!$this->view->user)
            redirect(url('panel/customers'));

        $this->view->imagesList = CustomersModel::getUserImages($userID);

        if ($this->startValidation()) {
            $this->validatePost('firstname',    'First Name',       'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('lastname',     'Last Name',        'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('email',        'Email',            'required|trim|email');
            $this->validatePost('tel',          'Telephone Number', 'trim|min_length[0]|max_length[100]');
            $this->validatePost('password',     'Password',         'trim|password');
            $this->validatePost('title',        'Title',            'trim|min_length[0]|max_length[150]');
            $this->validatePost('job_title',    'Job Title',        'trim|min_length[0]|max_length[150]');
            $this->validatePost('description',  'Description',      'trim|min_length[0]');
            $this->validatePost('for_fun',      'For fun',          'trim|min_length[0]');
            $this->validatePost('linkedin',     'LinkedIn URL',     'trim|min_length[0]|max_length[100]');
            $this->validatePost('twitter',      'Twitter URL',      'trim|min_length[0]|max_length[100]');
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
                    'description'   => post('description'),
                    'for_fun'       => post('for_fun'),
                    'linkedin'      => post('linkedin'),
                    'twitter'       => post('twitter'),
                    'display_team'  => $displayTeam,
                    'skype'         => post('skype'),
                    'image'         => post('image'),
                    'slug'          => post('slug'),
                );

                if (post('password'))
                    $data['password'] = md5(post('password'));

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

                File::resize(_SYSDIR_ . 'data/users/' . $data['image'],
                    _SYSDIR_ . 'data/users/mini_' . $data['image'], 50, 50);

                $result = Model::update('users', $data, "`id` = '$userID'"); // Update row

                if ($result) {
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

    public function deleteAction()
    {
        Request::ajaxPart();

        $userID = intval(Request::getUri(0));
        $user = CustomersModel::getUser($userID);

        if (!$user)
            Request::returnError('Team Member error');

        $data['deleted'] = 'yes';
        $result = Model::update('users', $data, "`id` = '$userID'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $userID);
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

        $user = CustomersModel::getUser($userID);

        if (!$user)
            redirectAny(url('panel/team'));

        if (!$user->sort) { // if sort = 0
            $biggest = CustomersModel::getBiggestSort();
            $data['sort'] = intval($biggest->sort) + 1;
            Model::update('users', $data, "`id` = '$userID'");
        } else { // if sort > 0
            if ($direction == 'up') {
                $smallest = CustomersModel::getNextSmallestSort($user->sort);
                if (!$smallest)
                    Request::returnError('Already on the top');

                Model::update('users', ['sort' => $smallest->sort], "`id` = '$userID'");
                Model::update('users', ['sort' => $user->sort], "`id` = '" . ($smallest->id) . "'");
            } else {
                $biggest = CustomersModel::getNextBiggestSort($user->sort);
                if (!$biggest)
                    Request::returnError('Already on the bottom');

                Model::update('users', ['sort' => $biggest->sort], "`id` = '$userID'");
                Model::update('users', ['sort' => $user->sort], "`id` = '" . ($biggest->id) . "'");
            }
        }

        redirectAny(url('panel/customers'));
    }

    public function remove_funAction()
    {
        Request::ajaxPart(); // if not Ajax part load
        $id = post('id');
        $image = CustomersModel::getUserImage($id);

        Model::delete('user_images', "`id` = '$id'");
        File::remove('data/fun/' . $image->image);
        Request::addResponse('remove', '#ft_' . $id, false);
    }
}
/* End of file */

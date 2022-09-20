<?php
class DashboardController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->dashboard_sort = SettingsModel::get('dashboard_sort');
        $this->view->list = DashboardModel::getAll(false, false, $this->view->dashboard_sort);

        Request::setTitle('Dashboard Settings');
    }

    public function addAction()
    {
        $this->view->tables = Model::getTables();

        if ($this->startValidation()) {
            $this->validatePost('title',   'Title',   'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('table',   'Table',   'required|trim|min_length[0]|max_length[24]');
            $this->validatePost('where',   'Where',   'trim|min_length[0]|max_length[200]');
            $this->validatePost('status',  'Status',  'required|trim|min_length[0]');

            if ($this->isValid()) {
                $data = array(
                    'title'  => post('title'),
                    'table'  => post('table'),
                    'where'  => post('where'),
                    'status' => post('status'),
                );

                $result         = Model::insert('dashboard_settings', $data); // Insert row
                $insertID       = Model::insertID();
                $dashboard_sort = SettingsModel::get('dashboard_sort');

                if (!$result && $insertID) {
                    if ($dashboard_sort) {  // Updated dashboard_sort if he created
                        $update = Model::update('settings', array(
                            'title' => 'Dashboard Sort',
                            'value' => $dashboard_sort . '|' . $insertID . '|',
                        ), "`name` = 'dashboard_sort'");
                    }

                    Request::addResponse('redirect', false, url('panel', 'settings', 'dashboard', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Setting');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit   = DashboardModel::get($id);
        $this->view->tables = Model::getTables();

        if (!$this->view->edit)
            redirect(url('panel/settings/dashboard'));

        $this->view->links = Model::fetchAll(Model::select('modules', "`visible` = 'yes' ORDER BY `id` DESC"));

        if ($this->startValidation()) {
            $this->validatePost('title',    'Title',    'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('table',    'Table',    'required|trim|min_length[0]|max_length[24]');
            $this->validatePost('where',    'Where',    'trim|min_length[0]|max_length[200]');
            $this->validatePost('status',   'Status',   'required|trim|min_length[0]');
            $this->validatePost('link',     'Link',     'trim|min_length[0]');

            if ($this->isValid()) {
                $data = array(
                    'title'  => post('title'),
                    'table'  => post('table'),
                    'where'  => post('where'),
                    'status' => post('status'),
                    'link'   => post('link'),
                );

                $result = Model::update('dashboard_settings', $data, "`id` = '$id'"); // Update row

                if ($result) {
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

        Request::setTitle('Edit Setting');
    }

    public function deleteAction()
    {
        $id = (Request::getUri(0));
        $user = DashboardModel::get($id);

        if (!$user)
            redirect(url('panel/settings/dashboard'));

        $data['deleted'] = 'yes';
        $result = Model::update('dashboard_settings', $data, "`id` = '$id'"); // Update row

        if ($result) {
//            Request::addResponse('redirect', false, url('panel', 'dashboard', 'edit', $insertID));
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/settings/dashboard'));
    }

    public function save_sortAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('#sort_list', 'Sort List', 'required|trim|min_length[1]');

            if ($this->isValid()) {
                SettingsModel::set('dashboard_sort', post('#sort_list'));

                Request::addResponse('func', 'noticeSuccess', 'Saved');
                Request::endAjax();
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

    }

    public function change_statusAction()
    {
        Request::ajaxPart();
        $id = post('item_id');
        $setting = DashboardModel::get($id);

        if (!$setting)
            redirect(url('panel/settings/dashboard'));

        if ($setting->status == 'active')
            $newStatus = 'inactive';
        else
            $newStatus = 'active';

        $setting->status = $newStatus;

        $data['status'] = $newStatus;
        $result = Model::update('dashboard_settings', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Request::addResponse('html', '#status_block_' . $setting->id,
                '<span>Status</span>' . ($setting->status === 'inactive' ? '<b style="color: red;">inactive</b>' : '<b style="color: green;">active</b>'));
        } else {
            Request::returnError('Database error');
        }
    }
}
/* End of file */
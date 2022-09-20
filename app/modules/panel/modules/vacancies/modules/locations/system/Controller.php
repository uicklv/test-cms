<?php
class LocationsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = LocationsModel::getAll();

        Request::setTitle('Locations');
    }

    public function archiveAction()
    {
        $this->view->list = LocationsModel::getArchived();

        Request::setTitle('Archived Locations');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = LocationsModel::get($id);

        if (!$user)
            redirect(url('panel/vacancies/locations/archive'));

        $result = Model::update('locations', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'locations#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/vacancies/locations/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result   = Model::insert('locations', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'Location created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'vacancies', 'locations', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax()) {
//                    Request::addResponse('func', 'noticeError', Request::returnErrors($this->validationErrors, true));
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        Request::setTitle('Add Location');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->sector = LocationsModel::get($id);

        if (!$this->view->sector)
            redirect(url('panel/vacancies/locations'));

        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');


            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result = Model::update('locations', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    $this->session->set_flashdata('success', 'Location created successfully.');
//                    Request::addResponse('redirect', false, url('panel', 'locations', 'edit', $id));
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Location');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = LocationsModel::get($id);

        if (!$user)
            Request::returnError('Location error');

        $data['deleted'] = 'yes';
        $result = Model::update('locations', $data, "`id` = '$id'"); // Update row

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
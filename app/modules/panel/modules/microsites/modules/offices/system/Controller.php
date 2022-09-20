<?php
class OfficesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $id = intval(Request::getUri(0));
        if ($id) {
            Model::import('panel/microsites');
            $this->view->microsite = MicrositesModel::get($id);
        } else
            $id = false;

        $this->view->list = OfficesModel::getAll($id);

        Request::setTitle('Offices');
    }

    public function addAction()
    {
        $this->view->microsite_id = intval(Request::getUri(0));

        if ($this->startValidation()) {
            $this->validatePost('name',         'Name',         'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('coordinates',  'Coordinates',  'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'microsite_id' => $this->view->microsite_id,
                    'name'         => post('name'),
                    'coordinates'  => post('coordinates'),
                    'time'         => time()
                );

                $result   = Model::insert('microsites_offices', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'microsites', 'offices', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        $this->view->maps_api = SettingsModel::get('maps_api_key');

        Request::setTitle('Add Industry Sector');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = OfficesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/microsites/offices'));

        if ($this->startValidation()) {
            $this->validatePost('name',         'Name',         'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('coordinates',  'Coordinates',  'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'name'        => post('name'),
                    'coordinates' => post('coordinates'),
                );

                $result = Model::update('microsites_offices', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'microsites', 'offices', 'edit', $id));
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

        $this->view->maps_api = SettingsModel::get('maps_api_key');

        Request::setTitle('Edit Industry Sector');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = OfficesModel::get($id);

        if (!$user)
            Request::returnError('Industry Sector error');

        $data['deleted'] = 'yes';
        $result = Model::update('microsites_offices', $data, "`id` = '$id'"); // Update row

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

<?php
class HotlistsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = HotlistsModel::getAll();

        Request::setTitle('Hot Lists');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name' => post('name'),
                    'consultant_id' => User::get('id'),
                    'time' => time()
                );

                $result   = Model::insert('talent_hotlists', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'talents', 'hotlists', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Hot-list');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->list = HotlistsModel::get($id);

        if (!$this->view->list)
            redirect(url('panel/talents/hotlists'));

        Model::import('panel/talents/open_profiles');
        Model::import('panel/talents/anonymous_profiles');

        $this->view->opens = Open_profilesModel::getAll();
        $this->view->anonymous = Anonymous_profilesModel::getAll();

        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('description', 'Description', 'required|trim|min_length[1]');


            if ($this->isValid()) {
                $data = array(
                    'name' => post('name'),
                    'description' => post('description'),
                );

                $result = Model::update('talent_hotlists', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    // Remove and after insert opens
                    HotlistsModel::removeOpenProfiles($id);
                    if (is_array(post('opens_ids')) && count(post('opens_ids')) > 0) {
                        foreach (post('opens_ids') as $open_id) {
                            Model::insert('talent_hotlists_open_profiles', array(
                                'hotlist_id' => $id,
                                'profile_id' => $open_id
                            ));
                        }
                    }

                    // Remove and after insert anonymous
                    HotlistsModel::removeAnonymousProfiles($id);
                    if (is_array(post('anonymous_ids')) && count(post('anonymous_ids')) > 0) {
                        foreach (post('anonymous_ids') as $open_id) {
                            Model::insert('talent_hotlists_anonymous_profiles', array(
                                'hotlist_id' => $id,
                                'profile_id' => $open_id
                            ));
                        }
                    }

                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Hot-list');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $skill = HotlistsModel::get($id);

        if (!$skill)
            Request::returnError('Hot-list error');

        $data['deleted'] = 'yes';
        $result = Model::update('talent_hotlists', $data, "`id` = '$id'"); // Update row

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

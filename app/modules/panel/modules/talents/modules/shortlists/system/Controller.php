<?php
class ShortlistsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = ShortlistsModel::getAll();

        Request::setTitle('Short Lists');
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

                $result   = Model::insert('talent_shortlists', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'talents', 'shortlists', 'edit', $insertID));
                } else
                    Request::returnError('Database error');
            } else {
                if (Request::isAjax()) {
//                    Request::addResponse('func', 'noticeError', Request::returnErrors($this->validationErrors, true));
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        Request::setTitle('Add Short-list');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->list = ShortlistsModel::get($id);

        if (!$this->view->list)
            redirect(url('panel/talents/shortlists'));


        Model::import('panel/talents/open_profiles');
        $this->view->opens = Open_profilesModel::getAll();

        if ($this->startValidation()) {
            $this->validatePost('name',           'Company Name',  'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('vacancy_title',  'Vacancy Title', 'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('location',       'Location',      'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('description',    'Description',   'trim|min_length[1]');


            if ($this->isValid()) {
                $data = array(
                    'name'           => post('name'),
                    'vacancy_title'  => post('vacancy_title'),
                    'location'       => post('location'),
                    'description'    => post('description'),
                );

                $result = Model::update('talent_shortlists', $data, "`id` = '$id'"); // Update row

                if ($result) {

                    // Remove and after insert opens
                    ShortlistsModel::removeOpenProfiles($id);
                    if (is_array(post('opens_ids')) && count(post('opens_ids')) > 0) {
                        foreach (post('opens_ids') as $open_id) {
                            Model::insert('talent_shortlists_open_profiles', array(
                                'shortlist_id' => $id,
                                'profile_id'   => $open_id
                            ));
                        }
                    }

                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                } else
                    Request::returnError('Database error');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Short-list');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $skill = ShortlistsModel::get($id);

        if (!$skill)
            Request::returnError('Short Lists error');

        $data['deleted'] = 'yes';
        $result = Model::update('talent_shortlists', $data, "`id` = '$id'"); // Update row

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

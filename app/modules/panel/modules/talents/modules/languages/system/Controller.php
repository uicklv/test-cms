<?php
class LanguagesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = LanguagesModel::getAll();

        Request::setTitle('Languages');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');

            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result   = Model::insert('talent_languages', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'Location created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'talents' , 'languages', 'edit', $insertID));
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

        Request::setTitle('Add Language');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->language = LanguagesModel::get($id);

        if (!$this->view->language)
            redirect(url('panel/talents/languages'));

        if ($this->startValidation()) {
            $this->validatePost('name', 'Name', 'required|trim|min_length[1]|max_length[200]');


            if ($this->isValid()) {
                $data = array(
                    'name' => post('name')
                );

                $result = Model::update('talent_languages', $data, "`id` = '$id'"); // Update row

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

        Request::setTitle('Edit Language');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $skill = LanguagesModel::get($id);

        if (!$skill)
            Request::returnError('Language error');

        $data['deleted'] = 'yes';
        $result = Model::update('talent_languages', $data, "`id` = '$id'"); // Update row

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

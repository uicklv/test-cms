<?php
class VideosController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = VideosModel::getAll();

        Request::setTitle('Videos');
    }

    public function archiveAction()
    {
        $this->view->list = VideosModel::getArchived();

        Request::setTitle('Archived Videos');
    }

    public function resumeAction()
    {
        $id = (Request::getUri(0));
        $user = VideosModel::get($id);

        if (!$user)
            redirect(url('panel/videos/archive'));

        $result = Model::update('videos', ['deleted' => 'no'], "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'restore', 'entity' => 'video#' . $id, 'time' => time()]);
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/videos/archive'));

    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name',  'Name',   'required|trim|min_length[1]|max_length[150]');
            $this->validatePost('text',  'Text',   'trim|min_length[0]|max_length[150]');
            $this->validatePost('video', 'Video',  'required|trim|min_length[0]|url');
            $this->validatePost('image', 'Image',  'required|trim|min_length[0]');

            if ($this->isValid()) {
                $data = array(
                    'name'  => post('name'),
                    'text'  => post('text'),
                    'video' => post('video'),
                    'image' => post('image'),
                );

                // Copy and remove image
                if ($data['image']) {
                    if (!File::copy('data/tmp/' . $data['image'], 'data/videos/' . $data['image']))
                        print_data(error_get_last());
                }

                $result   = Model::insert('videos', $data); // Insert row
                $insertID = Model::insertID();


                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'Video created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'videos', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Video');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = VideosModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/videos'));

        if ($this->startValidation()) {
            $this->validatePost('name',  'Name',   'required|trim|min_length[1]|max_length[150]');
            $this->validatePost('text',  'Text',   'trim|min_length[0]|max_length[150]');
            $this->validatePost('video', 'Video',  'required|trim|min_length[0]|url');
            $this->validatePost('image', 'Image',  'required|trim|min_length[0]');

            if ($this->isValid()) {
                $data = array(
                    'name'  => post('name'),
                    'text'  => post('text'),
                    'video' => post('video'),
                    'image' => post('image'),
                );

                // Copy and remove image
                if ($this->view->edit->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/videos/' . $data['image'])) {
                        File::remove('data/videos/' . $this->view->edit->image);
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('videos', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'videos', 'edit', $id));
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

        Request::setTitle('Edit Video');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = VideosModel::get($id);

        if (!$user)
            Request::returnError('Vacancy error');

        $data['deleted'] = 'yes';
        $result = Model::update('videos', $data, "`id` = '$id'"); // Update row

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

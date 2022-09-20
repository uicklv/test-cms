<?php
class VideosController extends Controller
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

        $this->view->list = VideosModel::getAll($id);

        Request::setTitle('Videos');
    }

    public function addAction()
    {
        $this->view->microsite_id = intval(Request::getUri(0));

        if ($this->startValidation()) {
            $this->validatePost('name',  'Name',   'required|trim|min_length[1]|max_length[150]');
            //  $this->validatePost('text',  'Text',   'trim|min_length[0]|max_length[150]');
            $this->validatePost('file', 'Video',  'required|trim|min_length[1]');
            $this->validatePost('image', 'Image',  'required|trim|min_length[1]');

            if ($this->isValid()) {
                $data = array(
                    'microsite_id' => $this->view->microsite_id,
                    'name'  => post('name'),
                    //        'text'  => post('text'),
                    'video' => post('file'),
                    'image' => post('image'),
                );

                $result   = Model::insert('microsites_videos', $data); // Insert row
                $insertID = Model::insertID();



                if (!$result && $insertID) {
                    // Copy and remove image
                    if ($data['image']) {
                        if (!File::copy('data/tmp/' . $data['image'], 'data/microsites/videos/' . $data['image']))
                            print_data(error_get_last());
                    }
                    if ($data['video']) {
                        if (!File::copy('data/tmp/' . $data['video'], 'data/microsites/videos/' . $data['video']))
                            print_data(error_get_last());
                    }
                    Request::addResponse('redirect', false, url('panel', 'microsites', 'videos', 'edit', $insertID));
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
            redirect(url('panel/microsites/videos'));

        if ($this->startValidation()) {
            $this->validatePost('name',  'Name',   'required|trim|min_length[1]|max_length[150]');
            // $this->validatePost('text',  'Text',   'trim|min_length[0]|max_length[150]');
            $this->validatePost('file', 'Video',  'required|trim|min_length[1]');
            $this->validatePost('image', 'Image',  'required|trim|min_length[1]');

            if ($this->isValid()) {
                $data = array(
                    'name'  => post('name'),
                    //   'text'  => post('text'),
                    'video' => post('file'),
                    'image' => post('image'),
                );

                $result = Model::update('microsites_videos', $data, "`id` = '$id'"); // Update row
                // Copy and remove image
                if ($this->view->edit->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/microsites/videos/' . $data['image'])) {
                        File::remove('data/microsites/videos/' . $this->view->edit->image);
                    } else
                        print_data(error_get_last());
                }
                if ($this->view->edit->video !== $data['video']) {
                    if (File::copy('data/tmp/' . $data['video'], 'data/microsites/videos/' . $data['video'])) {
                        File::remove('data/microsites/videos/' . $this->view->edit->video);
                    } else
                        print_data(error_get_last());
                }


                $result = Model::update('microsites_videos', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'microsites', 'videos', 'edit', $id));
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

    public function uploadVideoAction()
    {
        Request::ajaxPart(); // if not Ajax part load

        $name    = post('name', true); // file name, if not set - will be randomly
        $path    = post('path', true, 'tmp'); // path where file will be saved, default: 'tmp'
        $field   = post('field', true, '#image'); // field where to put file name after uploading
        $preview = post('preview', true, '#preview_file'); // field where to put file name after uploading

        $path = 'data/' . $path . '/';

        $result = null;
        foreach ($_FILES as $file) {
            $result = File::uploadVideo($file, $path, $name);
            break;
        }

        $newFileName = $result['name'] . '.' . $result['format']; // randomized name

        Request::addResponse('val', $field, $newFileName);
        Request::addResponse('html', $preview, $result['fileName']);
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $video = VideosModel::get($id);

        if (!$video)
            Request::returnError('Video error');

        $data['deleted'] = 'yes';
        $result = Model::update('microsites_videos', $data, "`id` = '$id'"); // Update row

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

<?php
class PhotosController extends Controller
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

        $this->view->list = PhotosModel::getAll($id);

        Request::setTitle('Photos');
    }

    public function addAction()
    {
        $this->view->microsite_id = intval(Request::getUri(0));

        if ($this->startValidation()) {
            $this->validatePost('name',  'Name',   'required|trim|min_length[1]|max_length[150]');
            //  $this->validatePost('text',  'Text',   'trim|min_length[0]|max_length[150]');
            $this->validatePost('image', 'Image',  'required|trim|min_length[1]');

            if ($this->isValid()) {
                $data = array(
                    'microsite_id' => $this->view->microsite_id,
                    'name'  => post('name'),
                    //        'text'  => post('text'),
                    'image' => post('image'),
                );

                $result   = Model::insert('microsites_photos', $data); // Insert row
                $insertID = Model::insertID();



                if (!$result && $insertID) {
                    // Copy and remove image
                    if ($data['image']) {
                        if (!File::copy('data/tmp/' . $data['image'], 'data/microsites/photos/' . $data['image']))
                            print_data(error_get_last());
                    }

                    Request::addResponse('redirect', false, url('panel', 'microsites', 'photos', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Photos');

    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = PhotosModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/microsites/photos'));

        if ($this->startValidation()) {
            $this->validatePost('name',  'Name',   'required|trim|min_length[1]|max_length[150]');
            // $this->validatePost('text',  'Text',   'trim|min_length[0]|max_length[150]');
            $this->validatePost('image', 'Image',  'required|trim|min_length[1]');

            if ($this->isValid()) {
                $data = array(
                    'name'  => post('name'),
                    //   'text'  => post('text'),
                    'image' => post('image'),
                );

                $result = Model::update('microsites_photos', $data, "`id` = '$id'"); // Update row
                // Copy and remove image
                if ($this->view->edit->image !== $data['image']) {
                    if (File::copy('data/tmp/' . $data['image'], 'data/microsites/photos/' . $data['image'])) {
                        File::remove('data/microsites/photos/' . $this->view->edit->image);
                    } else
                        print_data(error_get_last());
                }


                $result = Model::update('microsites_photos', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'microsites', 'photos', 'edit', $id));
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

        Request::setTitle('Edit Photo');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = PhotosModel::get($id);

        if (!$user)
            Request::returnError('Photo error');

        $data['deleted'] = 'yes';
        $result = Model::update('microsites_photos', $data, "`id` = '$id'"); // Update row

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

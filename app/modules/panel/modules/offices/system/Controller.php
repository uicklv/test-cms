<?php
class OfficesController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = OfficesModel::getAll();

        Request::setTitle('Offices');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name',         'Name',         'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                $data = array(
                    'name'         => post('name'),
                    'time'         => time(),
                    'slug'         => makeSlug(post('name'))
                );

                $result   = Model::insert('offices', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
//                    $this->session->set_flashdata('success', 'User created successfully.');
                    Request::addResponse('redirect', false, url('panel', 'offices', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        $this->view->maps_api = SettingsModel::get('maps_api_key');

        Request::setTitle('Add Office');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->edit = OfficesModel::get($id);

        if (!$this->view->edit)
            redirect(url('panel/offices'));

        if ($this->startValidation()) {
            $this->validatePost('name',          'Name',                 'required|trim|min_length[1]|max_length[100]');
            $this->validatePost('day_1',         'Days',                 'trim|min_length[1]|max_length[255]');
            $this->validatePost('day_2',         'Days',                 'trim|min_length[1]|max_length[255]');
            $this->validatePost('time_1',        'Time',                 'trim|min_length[1]|max_length[255]');
            $this->validatePost('time_2',        'Time',                 'trim|min_length[1]|max_length[255]');
            $this->validatePost('address',       'Address',              'trim|min_length[1]|max_length[255]');
            $this->validatePost('tel',           'Phone Number',         'trim|min_length[1]|max_length[60]');
            $this->validatePost('postcode',      'Postcode',             'trim|min_length[1]|max_length[255]');
//            $this->validatePost('image',         'Map Image',            'trim|min_length[1]|max_length[100]');
//            $this->validatePost('image_main',    'Image',                'trim|min_length[1]|max_length[100]');
            $this->validatePost('coordinates',   'Coordinates',          'trim|min_length[1]|max_length[100]');
            $this->validatePost('content',       'Page Content',         'trim|min_length[0]');
            $this->validatePost('apply',         'Apply Content',        'trim|min_length[0]');
            $this->validatePost('email',         'Email',                'trim|min_length[1]|max_length[100]|email');
            $this->validatePost('meta_title',    'Meta Title',           'trim|min_length[0]|max_length[255]');
            $this->validatePost('meta_keywords', 'Meta Keywords',        'trim|min_length[0]|max_length[255]');
            $this->validatePost('meta_desc',     'Meta Description',     'trim|min_length[0]|max_length[255]');

            $posted = post('posted');
            if ($posted !== 'yes')
                $posted = 'no';

            if ($this->isValid()) {
                $data = array(
                    'name'           => post('name'),
                    'day_1'          => post('day_1'),
                    'day_2'          => post('day_2'),
                    'time_1'         => post('time_1'),
                    'time_2'         => post('time_2'),
                    'address'        => post('address'),
                    'tel'            => post('tel'),
//                    'image'          => post('image'),
//                    'image_main'     => post('image_main'),
                    'postcode'       => post('postcode'),
                    'content'        => post('content'),
                    'apply'          => post('apply'),
                    'email'          => post('email'),
                    'posted'         => $posted,
                    'coordinates'    => post('coordinates'),
                    'slug'           => makeSlug(post('name')),
                    'meta_title'     => post('meta_title'),
                    'meta_keywords'  => post('meta_keywords'),
                    'meta_desc'      => post('meta_desc'),
                    'time'           => time()
                );


                $oldslug = $slug = makeSlug(post('name'));
                $i = 2;
                while ($vac = OfficesModel::getNotThis($slug, $id)) {
                    $slug = $oldslug . '-' . $i;
                    $i ++;
                }
                $data['slug'] = $slug;

                $result = Model::update('offices', $data, "`id` = '$id'"); // Update row

                // Copy and remove image
//                if ($data['image']) {
//                    if ($this->view->edit->image !== $data['image']) {
//                        if (File::copy('data/tmp/' . $data['image'], 'data/offices/' . $data['image'])) {
//                            File::remove('data/offices/' . $this->view->edit->image);
//                        } else
//                            print_data(error_get_last());
//                    }
//                }
//
//                // Copy and remove image
//                if ($data['image_main']) {
//                    if ($this->view->edit->image_main !== $data['image_main']) {
//                        if (File::copy('data/tmp/' . $data['image_main'], 'data/offices/' . $data['image_main'])) {
//                            File::remove('data/offices/' . $this->view->edit->image_main);
//                        } else
//                            print_data(error_get_last());
//                    }
//                }

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
            Request::returnError('Offices error');

        $data['deleted'] = 'yes';
        $result = Model::update('offices', $data, "`id` = '$id'"); // Update row

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

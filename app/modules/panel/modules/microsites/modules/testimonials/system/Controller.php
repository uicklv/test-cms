<?php
class TestimonialsController extends Controller
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

        $this->view->list = TestimonialsModel::getAll($id);

        Request::setTitle('Testimonials');
    }

    public function addAction()
    {
        $this->view->microsite_id = intval(Request::getUri(0));

        if ($this->startValidation()) {
            $this->validatePost('name',    'Name',           'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('video',   'video',          'trim|min_length[1]|max_length[100]');
            $this->validatePost('image',   'Image',          'trim|min_length[1]|max_length[100]');
            $this->validatePost('content', 'Page Content',   'required|trim|min_length[0]');

            if ($this->isValid()) {
                $data = array(
                    'microsite_id'  => $this->view->microsite_id,
                    'name'          => post('name'),
                    'video'         => post('video'),
                    'image'         => post('image'),
                    'content'       => post('content')
                );

                // Copy and remove image
                if ($data['image']) {
                    if (!File::copy('data/tmp/' . $data['image'], 'data/microsites/testimonials/' . $data['image']))
                        print_data(error_get_last());
                }

                $result   = Model::insert('microsites_testimonials', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'microsites', 'testimonials', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Testimonial');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->testimonial = TestimonialsModel::get($id);

        if (!$this->view->testimonial)
            redirect(url('panel/microsites/testimonials'));

        if ($this->startValidation()) {
            $this->validatePost('name',    'Name',           'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('video',   'video',          'trim|min_length[1]|max_length[100]');
            $this->validatePost('image',   'Image',          'trim|min_length[1]|max_length[100]');
            $this->validatePost('content', 'Page Content',   'required|trim|min_length[0]');

            if ($this->isValid()) {
                $data = array(
                    'name'     => post('name'),
                    'video'    => post('video'),
                    'image'    => post('image'),
                    'content'  => post('content')
                );

                // Copy and remove image
                if ($this->view->testimonial->image !== $data['image']) {
//                    print_data('data/microsites/testimonials/' . $data['image']);
                    if (File::copy('data/tmp/' . $data['image'], 'data/microsites/testimonials/' . $data['image'])) {
                        File::remove('data/microsites/testimonials/' . $this->view->testimonial->image);
                    } else
                        print_data(error_get_last());
                }

                $result = Model::update('microsites_testimonials', $data, "`id` = '$id'"); // Update row

                if ($result) {
//                    Request::addResponse('redirect', false, url('panel', 'microsites', 'testimonials', 'edit', $id));
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

        Request::setTitle('Edit Testimonial');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = TestimonialsModel::get($id);

        if (!$user)
            Request::returnError('Testimonial error');

        $data['deleted'] = 'yes';
        $result = Model::update('microsites_testimonials', $data, "`id` = '$id'"); // Update row

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

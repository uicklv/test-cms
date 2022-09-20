<?php
class Salary_surveyController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = Salary_surveyModel::getAll();

        Request::setTitle('Salary Survey');
    }

    public function addAction()
    {
//        Model::import('panel/team');
//        $this->view->team = TeamModel::getAllUsers();
//
//        if ($this->startValidation()) {
//            $this->validatePost('name',             'Name',           'required|trim|min_length[1]|max_length[200]');
//
//            if ($this->isValid()) {
//                $data = array(
//                    'name'     => post('name'),
//                );
//
//                $result   = Model::insert('testimonials', $data); // Insert row
//                $insertID = Model::insertID();
//
//                if (!$result && $insertID) {
//                    Request::addResponse('redirect', false, url('panel', 'testimonials', 'edit', $insertID));
//                } else {
//                    Request::returnError('Database error');
//                }
//            } else {
//                if (Request::isAjax())
//                    Request::returnErrors($this->validationErrors);
//            }
//        }
//
//        Request::setTitle('Add Testimonial');
    }

    public function editAction()
    {
//        $id = intval(Request::getUri(0));
//        $this->view->testimonial = TestimonialsModel::get($id);
//
//        if (!$this->view->testimonial)
//            redirect(url('panel/testimonials'));
//
//        Model::import('panel/team');
//        $this->view->team = TeamModel::getAllUsers();
//
//        if ($this->startValidation()) {
//            $this->validatePost('name',             'Name',           'required|trim|min_length[1]|max_length[200]');
//            $this->validatePost('position',         'Position',       'trim|min_length[1]|max_length[100]');
//            $this->validatePost('image',            'Image',          'required|trim|min_length[1]|max_length[100]');
//            $this->validatePost('user_image',       'User Image',     'required|trim|min_length[1]|max_length[100]');
//            $this->validatePost('content',          'Page Content',   'required|trim|min_length[0]');
//
//            if ($this->isValid()) {
//                $data = array(
//                    'name'       => post('name'),
//                    'position'   => post('position'),
//                    'image'      => post('image'),
//                    'user_image' => post('user_image'),
//                    'content'    => post('content')
//                );
//
//                // Copy and remove image
//                if ($this->view->testimonial->image !== $data['image']) {
//                    if (File::copy('data/tmp/' . User::get('id') . '.png', 'data/testimonials/' . $data['image'])) {
//                        File::remove('data/testimonials/' . $this->view->testimonial->image);
//                    } else
//                        print_data(error_get_last());
//                }
//
//                $result = Model::update('testimonials', $data, "`id` = '$id'"); // Update row
//
//                if ($result) {
////                    Request::addResponse('redirect', false, url('panel', 'testimonials', 'edit', $id));
//                    Request::addResponse('func', 'noticeSuccess', 'Saved');
//                    Request::endAjax();
//                } else {
//                    Request::returnError('Database error');
//                }
//            } else {
//                if (Request::isAjax())
//                    Request::returnErrors($this->validationErrors);
//            }
//        }
//
//        Request::setTitle('Edit Testimonial');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $user = Salary_surveyModel::get($id);

        if (!$user)
            Request::returnError('Salary Survey error');

        $data['deleted'] = 'yes';
        $result = Model::update('marketers', $data, "`id` = '$id'"); // Update row

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

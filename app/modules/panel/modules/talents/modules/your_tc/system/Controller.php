<?php
class Your_tcController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->edit = Your_tcModel::getLast();

        Request::setTitle('Your Terms and Conditions');
    }

    public function addAction()
    {
        Request::ajaxPart();

        if ($this->startValidation()) {
            $this->validatePost('file', 'File', 'required|trim|min_length[1]|max_length[60]');

            if ($this->isValid()) {
                $data = array(
                    'file' => post('file'),
                    'time' => time(),
                );

                $file = Your_tcModel::get(post('file_id'));

                if ($file->file !== $data['file']) {
                    if (File::copy('data/tmp/' . $data['file'], 'data/talent/your_tc/' . $data['file'])) {
                        File::remove('data/talent/your_tc/' . $file->file);
                    } else
                        print_data(error_get_last());
                }

                if ($file)
                    $result   = Model::update('your_tc', $data, "`id` = '$file->id'"); // Insert row
                else
                    $result   = Model::insert('your_tc', $data); // Insert row

                Request::addResponse('redirect', false, url('panel', 'talents', 'your_tc'));

            } else {
                if (Request::isAjax()) {
//                    Request::addResponse('func', 'noticeError', Request::returnErrors($this->validationErrors, true));
                    Request::returnErrors($this->validationErrors);
                }
            }
        }
    }

}
/* End of file */
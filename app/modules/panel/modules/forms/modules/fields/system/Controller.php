<?php
class FieldsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = FieldsModel::getAll(" `duplicate` = 0");

        Request::setTitle('Fields');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title',           'Title',           'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('type',            'Type',            'required|trim|min_length[1]|max_length[100]');

            if ($this->isValid()) {
                $data = [
                    'title'   => post('title'),
                    'type'    => post('type'),
                    'time'    => time()
                ];

                $result   = Model::insert('forms_fields', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'add', 'entity' => 'forms_fields#' . $insertID, 'time' => time()]);

                    Request::addResponse('redirect', false, url('panel', 'forms', 'fields', 'edit', $insertID));
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Field');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->field = FieldsModel::get($id);

        if (!$this->view->field)
            redirect(url('panel/forms/fields'));

        if ($this->startValidation()) {
            $this->validatePost('title',           'Title',           'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('type',            'Type',            'required|trim|min_length[1]|max_length[100]');

            if ($options = post('options'))
                $options = arrayToString($options);

            if (in_array(post('type'), ['checkbox', 'radio']))
                $this->validatePost('options', 'Options', 'required');

            if ((post('type') == 'info'))
                $options = post('answer_options');

            if ($this->isValid()) {
                $data = [
                    'title'          => post('title'),
                    'type'           => post('type'),
                    'answer_options' => $options ?: '',
                ];

                $result = Model::update('forms_fields', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    $images = post('images') ?: [];
                    //get all images from DB and check
                    $dbImages = Model::fetchAll(Model::select('forms_fields_images', ' `field_id` = ' . $id));
                    foreach ($dbImages as $image) {
                        if (!in_array($image->image, $images)) {
                            File::remove('data/form_builder/' . $image->image);
                        }
                    }
                    Model::delete('forms_fields_images', ' `field_id` = ' . $id);

                    if ($images) {
                        foreach ($images as $image) {

                            if (!File::copy('data/tmp/' . $image, 'data/form_builder/' . $image))
                                Request::returnError('Image copy error: ' . error_get_last()['message']);

                            Model::insert('forms_fields_images', [
                                'field_id' => $id,
                                'image' => $image,
                            ]);
                        }
                    }

                    Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'edit', 'entity' => 'forms_field#' . $id, 'time' => time()]);

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

        Request::setTitle('Edit Field');
    }

    public function duplicateAction()
    {
        Request::ajaxPart();

        $id = (Request::getUri(0));
        $field = FieldsModel::get($id);

        if (!$field)
            redirectAny(url('panel/forms/fields'));

        $newData = [
            'title' => $field->title . ' (' . date('d.m/H:i:s') .')',
            'type' => $field->type,
            'answer_options' => $field->answer_options,
            'duplicate' => 0,
            'time' => time()
        ];

        $resultField = Model::insert('forms_fields', $newData);
        $insertId = Model::insertID();

        if (!$resultField && $insertId)
            Request::addResponse('redirect', false, url('panel', 'forms', 'fields', 'edit', $insertId));
        else
            Request::returnError('Database error');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $field = FieldsModel::get($id);

        if (!$field)
            Request::returnError('Field Error');

        $data['deleted'] = 'yes';
        $result = Model::update('forms_fields', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Model::insert('actions_logs', ['user_id' => User::get('id'), 'action' => 'delete', 'entity' => 'forms_fields#' . $id, 'time' => time()]);

            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }

}
/* End of file */
<?php
class SectionsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = SectionsModel::getAll(" `duplicate` = 0");

        Request::setTitle('Sections');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title', 'Title', 'required|trim|min_length[1]|max_length[255]');

            if ($this->isValid()) {
                $data = [
                    'title' => post('title'),
                    'time'  => time()
                ];

                $result   = Model::insert('forms_sections', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'forms', 'sections', 'edit', $insertID));
                } else
                    Request::returnError('Database error');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Section');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->section = SectionsModel::get($id);

        if (!$this->view->section)
            redirect(url('panel/forms/sections'));

        $this->view->fields = $this->getSectionFields($this->view->section);

        if ($this->startValidation()) {
            $this->validatePost('title', 'Title', 'required|trim|min_length[1]|max_length[255]');

            if ($this->isValid()) {
                $data = [
                    'title'        => post('title'),
                    'fields_row'   => post('fields_row'),
                ];

                $result = Model::update('forms_sections', $data, "`id` = '$id'"); // Update row

                // Update sorting fields
                if (Model::fetch(Model::select('forms_fields_sort', " `section_id` = $id"))) {
                    Model::query("UPDATE `forms_fields_sort` SET `fields_row` = '" . post('fields_row') . "' WHERE `section_id` = '$id'");
                } else {
                    // Create row for sorting fields
                    $data = [
                        'form_id' => 0,
                        'section_id' => $id,
                        'fields_row' => post('fields_row'),
                        'time' => time()
                    ];
                    Model::insert("forms_fields_sort", $data);
                }

                if ($result) {
                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::endAjax();
                } else
                    Request::returnError('Database error');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Section');
    }

    public function deleteAction()
    {
        $id = (Request::getUri(0));
        $user = SectionsModel::get($id);

        if (!$user)
            redirect(url('panel/forms/sections'));

        $data['deleted'] = 'yes';
        $result = Model::update('forms_sections', $data, "`id` = '$id'"); // Update row

        if ($result) {
//            $this->session->set_flashdata('success', 'Testimonial created successfully.');
//            Request::addResponse('redirect', false, url('panel', 'templates', 'edit', $insertID));
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/forms/sections'));
    }

    public function duplicateAction()
    {
        $id = (Request::getUri(0));
        $section = SectionsModel::get($id);

        if (!$section)
            redirectAny(url('panel/forms/sections'));

        $newData = [
            'title' => $section->title . '(' . date('d.m/H:i:s') .')',
            'fields_row' => $section->fields_row,
            'fields_real_sec' => $section->fields_real_sec,
            'duplicate' => 0,
            'time' => time()
        ];

        $resultSection = Model::insert('forms_sections', $newData);
        $insertId = Model::insertID();

        if (!$resultSection && $insertId) {
            $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = 0 AND `section_id` = $id"));
            // Create row for sorting fields
            $fieldsRow = $sort->fields_row ?: $section->fields_row;

            $data = [
                'form_id' => 0,
                'section_id' => $insertId,
                'fields_row' => $fieldsRow,
                'time' => time()
            ];

            Model::insert("forms_fields_sort", $data);

            redirectAny(url('panel/forms/sections/edit/' . $insertId));
        } else
            Request::returnError('Database error');
    }

    public function field_popupAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        $this->view->section = SectionsModel::get($id);

        if (!$this->view->section)
            redirectAny(url('panel/forms/sections'));

        Model::import('panel/forms/fields');
        $this->view->fields = FieldsModel::getAll();

        if ($this->startValidation()) {
            if (post('action_type') == 'new')
                $this->addNewFields($id);
            else
                $this->addExistsFields($id);
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function copy_fieldAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);

        Model::import('panel/forms/fields');
        Model::import('panel/forms');

        $section_id = post('section_id');
        $field   = FieldsModel::get($id);
        $section = SectionsModel::get($section_id);

        if (!$field || !$section)
            redirectAny(url('panel/forms/sections'));

        $newData = [
            'title' => $field->title . '(' . date('d.m/H:i:s') .')',
            'type' => $field->type,
            'answer_options' => $field->answer_options,
            'title_date' => '',
            'duplicate' => 1,
            'time' => time()
        ];

        $resultField = Model::insert('forms_fields', $newData);
        $insertId = Model::insertID();

//        if (post('from') == 'forms') {
//            $sectionId = $this->getSectionId($section, $form_id);
//            $section = SectionsModel::get($sectionId);
//        }

        if ($section->fields_row)
            Model::query("UPDATE `forms_sections` SET `fields_row` = CONCAT(`fields_row`, '|$insertId|') WHERE `id` = '$section->id'");
        else
            Model::update("forms_sections", ['fields_row' => '|' . $insertId . '|'], " `id` = '$section->id'");

        // Update sorting fields
        if (Model::fetch(Model::select('forms_fields_sort', " `section_id` = $section->id"))) {
            Model::query("UPDATE `forms_fields_sort` SET `fields_row` = CONCAT(`fields_row`, '|$insertId|') WHERE `section_id` = '$section->id'");
        } else {
            // Create row for sorting fields
            $data = [
                'form_id' => 0,
                'section_id' => $section->id,
                'fields_row' => $section->fields_row . "|$insertId|",
                'time' => time()
            ];
            Model::insert("forms_fields_sort", $data);
        }

        if (!$resultField && $insertId) {} else
            Request::returnError('Database error');

        $this->view->field = FieldsModel::get($insertId);
        $this->view->section = $section;

        $this->view->fields = [];

        //for request from foms module
//        if (post('from') == 'forms') {
//            // use temp_row from template for get sections and fields
//            $this->view->form_id  = intval($form_id);
//            $this->view->form = FormsModel::get($form_id);
//            $this->view->sections = [];
//
//            $sectionsArr = stringToArray($this->view->form->sections_row);
//            if (count($sectionsArr) > 0)
//                $this->view->sections = SectionsModel::getWhere(" `id` IN ('" . implode( "','", $sectionsArr) . "') ORDER BY FIELD(`id`,'" . implode( "','", $sectionsArr) . "')",'object', $form_id);
//
//            Request::addResponse('html', '#sections', $this->getView('modules/panel/modules/forms/views/sections.php'));
//            Request::addResponse('html', '#popup', $this->getView('modules/panel/modules/forms/views/title_popup.php'));
//        } else {

        $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = 0 AND `section_id` = $section_id"));
        // Create row for sorting fields
        $fieldsRow = $sort->fields_row ?: $section->fields_row;

        $fieldsArr = stringToArray($fieldsRow);

        if ($fieldsArr > 0) {
            Model::import('panel/forms/fields');
            $this->view->fields = FieldsModel::getWhere(" `id` IN ('" . implode("','", $fieldsArr) . "') ORDER BY FIELD(`id`,'" . implode("','", $fieldsArr) . "')");
        }
        Request::addResponse('html', '#sections_block', $this->getView('modules/panel/modules/forms/modules/sections/views/fields.php'));
        Request::addResponse('html', '#popup', $this->getView('modules/panel/modules/forms/modules/sections/views/field_edit_popup.php'));

        Request::endAjax();
    }

    public function remove_fieldAction()
    {
        Model::import('panel/forms/fields');
        Model::import('panel/forms');

        $id         = (Request::getUri(0));
        $section_id = post('section_id');
        $field      = FieldsModel::get($id);
        $section    = SectionsModel::get($section_id);

        if (!$field || !$section)
            redirectAny(url('panel/forms/sections'));

        //if request from forms module
//        if (post('from') == 'forms') {
//            $sectionId = $this->getSectionId($section, $form->id);
//            $section = SectionsModel::get($sectionId);
//            $form_id = $form_id ?: 0;
//            $redirect = url('panel/forms/edit/' . $form_id);
//        }

        $fieldsRow = $section->fields_row;
        $fieldsArray = stringToArray($fieldsRow);

        foreach ($fieldsArray as $k => $item) {
            if ($item == $field->id)
                unset($fieldsArray[$k]);
        }

        $newFieldsRow = arrayToString($fieldsArray);

        $data['fields_row'] = $newFieldsRow;
        $result = Model::update('forms_sections', $data, "`id` = '$section->id'"); // Update row

        //get sort row and remove field
        $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = 0 AND `section_id` = $section->id"));

        // Update sorting fields
        if ($sort) {
            $fieldsArraySort = stringToArray($sort->fields_row);
            foreach ($fieldsArraySort as $k => $item) {
                if ($item == $field->id)
                    unset($fieldsArraySort[$k]);
            }
            $newFieldsRowSort = arrayToString($fieldsArraySort);
            Model::query("UPDATE `forms_fields_sort` SET `fields_row` = '$newFieldsRowSort' WHERE `section_id` = $section->id AND `form_id` = 0");
        } else {
            $data = [
                'form_id' => 0,
                'section_id' => $section->id,
                'fields_row' => $newFieldsRow,
                'time' => time()
            ];

            Model::insert("forms_fields_sort", $data);
        }

        if (!$result)
            Request::returnError('Database error');

        redirectAny(url('panel/forms/sections/edit/' . $section->id));
    }

    public function field_edit_popupAction()
    {
        Request::ajaxPart();

        Model::import('panel/forms/sections');
        Model::import('panel/forms/fields');

        $field_id    = Request::getUri(0);
        $section_id  = Request::getUri(1);

        $field    = $this->view->field = FieldsModel::get($field_id);
        $section  = $this->view->section = SectionsModel::get($section_id);

        if (!$field || !$section)
            redirectAny(url('panel/forms/sections'));

        if ($this->startValidation()) {
            $title  = $this->validatePost('title',    'Title',    'required|trim|min_length[1]|max_length[255]');
            $type   = $this->validatePost('type',     'Type',     'required|trim|min_length[1]|max_length[255]');

            // answers for checkbox field
            if ($options = post('options'))
                $options = arrayToString($options);

            if ($type == 'radio' || $type == 'checkbox')
                $this->validatePost('options', 'Options', 'required');

            if ($type == 'info')
                $options = post('answer_options');

            if ($this->isValid()) {
                //update field
                $updateData = [
                    'title'          => $title,
                    'type'           => $type,
                    'answer_options' => $options ?: '',
                ];

                $result = Model::update('forms_fields', $updateData, " `id` = $field_id");

                if ($result) {
                    Request::addResponse('redirect', false, url('panel', 'forms', 'sections', 'edit', $section_id));
                } else {
                    Request::returnError('Database error');
                }

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    /**
     * Get all section fields with sorting
     * @param $section
     * @return array|mixed
     */
    private function getSectionFields($section)
    {
        $fields = [];

        $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = 0 AND `section_id` = $section->id"));

        // Create row for sorting fields
        $fieldsRow = $sort->fields_row ?: $section->fields_row;

        $fieldsArr = stringToArray($fieldsRow);

        if (count($fieldsArr) > 0) {
            Model::import('panel/forms/fields');
            $fields = FieldsModel::getWhere(" `id` IN ('" . implode( "','", $fieldsArr)  . "') ORDER BY FIELD(`id`,'" . implode( "','", $fieldsArr) . "')");
        }

        return $fields;
    }

    /**
     * Add exists fields to section
     * @param $section_id
     */
    private function addExistsFields($section_id)
    {
        $this->validatePost('field_ids',     'Field',      'required|min_count[1]');

        if ($this->isValid()) {
            $fieldsStr = arrayToString(post('field_ids'));

            $result = Model::update('forms_sections', ['fields_row' => $fieldsStr], " `id` = $section_id");

            if ($result) {
                if (Model::fetch(Model::select('forms_fields_sort', " `section_id` = $section_id")))
                    Model::query("UPDATE `forms_fields_sort` SET `fields_row` = '$fieldsStr' WHERE `section_id` = '$section_id'");
//                    Model::query("UPDATE `forms_fields_sort` SET `fields_row` = CONCAT(`fields_row`, '$fieldsStr') WHERE `section_id` = '$section_id'");

                Request::addResponse('redirect', false, url('panel', 'forms', 'sections', 'edit', $section_id));
                Request::endAjax();
            } else
                Request::returnError('Database error');

        } else {
            if (Request::isAjax())
                Request::returnErrors($this->validationErrors);
        }
    }

    /**
     * create and add new field to section
     * @param $section_id
     */
    private function addNewFields($section_id)
    {
        $this->validatePost('title',           'Title',         'required|trim|min_length[1]|max_length[255]');
        $this->validatePost('type',            'Type',          'required|trim|min_length[1]|max_length[255]');

        if ($options = post('options'))
            $options = arrayToString($options);

        if (post('type') == 'dropdown' || post('type') == 'checkbox')
            $this->validatePost('options', 'Options', 'required');

        if ((post('type') == 'info'))
            $options = post('answer_options');

        if ($this->isValid()) {
            $data = array(
                'title'              => post('title'),
                'type'               => post('type'),
                'answer_options'     => $options ?: '',
                'time'               => time()
            );

            $result = Model::insert('forms_fields', $data); // Update row
            $insertID = Model::insertID();

            if (!$result && $insertID) {

                if ($this->view->section->fields_row)
                    Model::query("UPDATE `forms_sections` SET `fields_row` = CONCAT(`fields_row`, '|$insertID|') WHERE `id` = '$section_id'");
                else
                    Model::update("forms_sections", ['fields_row' => '|' . $insertID . '|'], " `id` = '$section_id'");

                // Update sorting fields
                if (Model::fetch(Model::select('forms_fields_sort', " `section_id` = $section_id")))
                    Model::query("UPDATE `forms_fields_sort` SET `fields_row` = CONCAT(`fields_row`, '|$insertID|') WHERE `section_id` = '$section_id'");

                Request::addResponse('redirect', false, url('panel', 'forms', 'sections', 'edit', $section_id));
                Request::endAjax();
//                } else {
//
//                    //if request from Forms module
//                    if ($this->view->from == 'forms') {
//                        $sectionId = $this->getSectionId($this->view->section, $this->view->form->id);
//
//                        // Create row for sorting fields
//                        $data = [
//                            'form_id' => $this->view->form->id,
//                            'section_id' => $sectionId,
//                            'fields_row' => $this->view->section->fields_row . "|$insertID|",
//                            'time' => time()
//                        ];
//                        Model::insert("forms_fields_sort", $data);
//                    }
//                }
            } else
                Request::returnError('Database error');
        } else {
            if (Request::isAjax())
                Request::returnErrors($this->validationErrors);
        }
    }

    //check from request and redirect
    private function redirect($section_id)
    {
        if ($this->view->form && $this->view->from == 'forms') {
            $url = url('panel', 'forms', 'edit', $this->view->form->id);
        } else {
            $url = url('panel', 'forms', 'sections', 'edit', $section_id);
        }

        Request::addResponse('redirect', false, $url);
        Request::endAjax();
    }


    /**
     * Duplicate section
     * @param $section
     * @param $template_id
     * @return int|string
     */
    private function duplicateSection($section, $template_id)
    {
        $dataSec = [
            'title'         => $section->title,
            'fields_row'    => $section->fields_row,
//            'title_date'    => '(' . date('d.m/H:i:s') .')',
            'time'          => time(),
            'duplicate'     => 1
        ];

        $insert = Model::insert('forms_sections', $dataSec);
        $sectionId = Model::insertID();

        if (!$insert && $sectionId) {
            $newForm = Model::fetch(Model::select('forms', " `id` = '$template_id'"));
            if ($newForm->sections_row)
                Model::query("UPDATE `forms` SET `sections_row` = REPLACE (`sections_row`, '|$section->id|', '|$sectionId|') WHERE `id` = '$template_id'");
        }

        return $sectionId;
    }

    /**
     * check if section is original and get section id from exist or new section
     * @param $section
     * @param $form_id
     * @return int|string
     */

    //todo move into Functions file ?
    private function getSectionId($section, $form_id)
    {
        $checkSection = Model::fetch(Model::select('forms', " `deleted` = 'no' AND `sections_row` LIKE '%|$section->id|%' AND `id` != {$form_id}"));
        // Create duplicate if section used somewhere or its original section
        if ($checkSection || $section->duplicate != 1)
            $sectionId = $this->duplicateSection($section, $form_id);
        else
            $sectionId = $section->id;

        return $sectionId;
    }
}
/* End of file */
<?php
class FormsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = FormsModel::getAll();

        Request::setTitle('Forms');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('title',      'Title',         'required|trim|min_length[1]|max_length[255]');

            if ($this->isValid()) {
                $data = [
                    'title' => post('title'),
                    'time'  => time()
                ];

                $result   = Model::insert('forms', $data); // Insert row
                $insertID = Model::insertID();

                if (!$result && $insertID)
                    Request::addResponse('redirect', false, url('panel', 'forms', 'edit', $insertID));
                else
                    Request::returnError('Database error');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Add Form');
    }

    public function editAction()
    {
        $id = intval(Request::getUri(0));
        $this->view->form = FormsModel::get($id);

        if (!$this->view->form)
            redirect(url('panel/forms'));

        Model::import('panel/forms/sections');

        // use sections_row from form for get sections and fields
        $this->view->sections = [];
        $sectionsArr = stringToArray($this->view->form->sections_row);

        if ($sectionsArr)
            $this->view->sections = SectionsModel::getWhere(" `id` IN ('" . implode( "','", $sectionsArr) . "') ORDER BY FIELD(`id`,'" . implode( "','", $sectionsArr) . "')",'object', $id);

        if ($this->startValidation()) {
            $this->validatePost('title', 'title', 'required|trim|min_length[1]|max_length[255]');

            if ($this->isValid()) {
                $data = array(
                    'title'    => post('title'),
                    'sections_row' => post('sections_row'),
                );

                $result = Model::update('forms', $data, "`id` = '$id'"); // Update row

                if ($result) {
                    if ($this->view->sections) {
                        foreach ($this->view->sections as $item) {
                            Model::update('forms_fields_sort', ['fields_row' => post('fields_row_' . $item->id)], " `form_id` = $id AND `section_id` = $item->id");


                            // images for fields
                            $fieldIds = stringToArray(post('fields_row_' . $item->id));
                            $images = post('images');

                            foreach ($fieldIds as $fieldId) {

                                //get all images from DB and check
                                $dbImages = Model::fetchAll(Model::select('forms_fields_images', "`field_id` = $fieldId AND `form_id` = {$this->view->form->id} AND `section_id` = {$item->id}"));
                                foreach ($dbImages as $image) {
                                    if (!in_array($image->image, $images['field_' . $fieldId .'_section_' . $item->id . '_form_' . $this->view->form->id] ?: [])) {
                                        File::remove('data/form_builder/' . $image->image);
                                    }
                                }
                                Model::delete('forms_fields_images', "`field_id` = $fieldId AND `form_id` = {$this->view->form->id} AND `section_id` = {$item->id}");

                                if ($fieldImages = $images['field_' . $fieldId .'_section_' . $item->id . '_form_' . $this->view->form->id]) {
                                    if ($fieldImages) {
                                        foreach ($fieldImages as $image) {

                                            if (!File::copy('data/tmp/' . $image, 'data/form_builder/' . $image))
                                                Request::returnError('Image copy error: ' . error_get_last()['message']);

                                            Model::insert('forms_fields_images', [
                                                'field_id' => $fieldId,
                                                'form_id' => $this->view->form->id,
                                                'section_id' => $item->id,
                                                'image' => $image,
                                            ]);
                                        }
                                    }

                                }
                            }

                        }
                    }

                    Request::addResponse('func', 'noticeSuccess', 'Saved');
                    Request::endAjax();
                } else
                    Request::returnError('Database error');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        Request::setTitle('Edit Form');
    }

    public function deleteAction()
    {
        $id = (Request::getUri(0));
        $user = FormsModel::get($id);

        if (!$user)
            redirect(url('panel/forms'));

        $data['deleted'] = 'yes';
        $result = Model::update('forms', $data, "`id` = '$id'"); // Update row

        if (!$result)
            Request::returnError('Database error');

        redirectAny(url('panel/forms'));
    }

    public function answersAction()
    {
        $id = intval(Request::getUri(0));
        $form = FormsModel::get($id);

        if (!$form)
            redirect(url('panel/forms'));

        $this->view->list = FormsModel::getAllAnswers($form->id);

        Request::setTitle('Answers');
    }

    public function copy_formAction()
    {
        Request::ajaxPart();

        $id = (Request::getUri(0));
        $form = FormsModel::get($id);
        if (!$form)
            redirectAny(url('panel/forms'));

        $newTitle = preg_replace(
                '~\s\(\d{1,2}\.\d{1,2}\/\d{1,2}:\d{1,2}:\d{1,2}\)~i',
                '',
                $form->title
            ) . ' (' . date('d.m/H:i:s') .')';

        $data = [
            'title'         => $newTitle,
            'sections_row'  => $form->temp_row,
            'temp_real_sec' => $form->temp_real_sec,
            'time'          => time(),
        ];

        $insert = Model::insert('forms', $data);
        $insertId = Model::insertID();

        if (!$insert && $insertId) {
            //copy fields sort for new form
            $sorts = Model::fetchAll(Model::select('form_fields_sort', " `form_id` = $form->id"));
            if (count($sorts) > 0) {
                foreach ($sorts as $sort) {
                    $dataSort = [
                        'form_id' => $insertId,
                        'section_id' => $sort->section_id,
                        'fields_row' => $sort->fields_row,
                        'time' => time()
                    ];
                    Model::insert('form_fields_sort', $dataSort);
                }
            }
        } else
            Request::returnError('Database error');

        redirectAny(url('panel/forms/edit/' . $insertId));
    }

    //Sections
    public function section_popupAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        $this->view->form = FormsModel::get($id);

        if (!$this->view->form)
            redirect(url('panel/forms'));

        Model::import('panel/forms/sections');
        $this->view->sections = SectionsModel::getAll();

        if ($this->startValidation()) {
            // add new section and create row into `form_fields_sort` table
            if (post('action_type') == 'new') {
                $this->addNewSections($id);
            } else {
                // add exist sections
                $this->addExistsSections($id);
            }
        }

        Request::addResponse('html', '#popup', $this->getView());
    }

    public function copy_sectionAction()
    {
        Model::import('panel/forms/sections');

        $id = (Request::getUri(0));
        $section = SectionsModel::get($id);
        $form = FormsModel::get(post('form_id'));

        if (!$section || !$form)
            redirectAny(url('panel/forms'));

        $newData = [
            'title' => $section->title,
            'fields_row' => $section->fields_row,
            'fields_real_sec' => $section->fields_real_sec,
            'title_date' => '(' . date('d.m/H:i:s') .')',
            'duplicate' => 1,
            'time' => time()
        ];

        $resultSection = Model::insert('forms_sections', $newData);
        $insertId = Model::insertID();

        if ($form->sections_row)
            Model::query("UPDATE `forms` SET `sections_row` = CONCAT(`sections_row`, '|$insertId|') WHERE `id` = '$form->id'");

        if (!$resultSection && $insertId) {
            $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = $form->id AND `section_id` = $id"));
            // Create row for sorting fields
            if ($sort)
                $fieldsRow = $sort->fields_row;
            else
                $fieldsRow = $section->fields_row;

            $data = [
                'form_id' => $form->id,
                'section_id' => $insertId,
                'fields_row' => $fieldsRow,
                'time' => time()
            ];
            Model::insert("forms_fields_sort", $data);
        } else
            Request::returnError('Database error');

        redirectAny(url('panel/forms/edit/' . $form->id));
    }

    public function remove_sectionAction()
    {
        Model::import('panel/forms/sections');

        $id       = (Request::getUri(0));
        $section  = SectionsModel::get($id);
        $form     = FormsModel::get(post('form_id'));

        if (!$section || !$form)
            redirectAny(url('panel/forms/edit/' . post('form_id')));

        $fieldsRow = $form->sections_row;
        $fieldsArray = stringToArray($fieldsRow);

        foreach ($fieldsArray as $k => $item) {
            if ($item == $section->id)
                unset($fieldsArray[$k]);
        }

        $newFieldsRow = arrayToString($fieldsArray);

        $data['sections_row'] = $newFieldsRow;
        $result = Model::update('forms', $data, "`id` = '$form->id'"); // Update row

        if ($result) {
            // Remove sorting for fields
            Model::delete('forms_fields_sort', " `form_id` = $form->id AND `section_id` = $section->id");
        } else
            Request::returnError('Database error');

        redirectAny(url('panel/forms/edit/' . $form->id));
    }

    /**
     * Add exists fields to section
     * @param $section_id
     */
    private function addExistsSections($form_id)
    {
        $this->validatePost('section_ids',     'Section',      'required|min_count[1]');

        if ($this->isValid()) {
            $fieldsStr = arrayToString(post('section_ids'));

            $tempRow = $this->view->form->sections_row; // new temp_row
            $oldRealSec = $this->view->form->temp_real_sec; // old section_ids row

            // Add new sections to row
            foreach (post('section_ids') as $sec_id) {
                if (mb_strpos($tempRow, "|$sec_id|") === false)
                    $tempRow .= "|$sec_id|";

                $oldRealSec = str_replace("|$sec_id|", "", $oldRealSec);
            }

            // Remove old real sections
            foreach (stringToArray($oldRealSec) as $old_id)
                $tempRow = str_replace("|$old_id|", "", $tempRow);

            // get all sections were selected in the popup and create new rows into `form_fields_sort` table
            $sections = SectionsModel::getAll(" `id` IN (" . implode(', ', post('section_ids')) . ")");
            foreach ($sections as $section) {
                $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = $form_id AND `section_id` = $section->id"));
                if ($sort)
                    $fieldsRow = $sort->fields_row;
                else
                    $fieldsRow = $section->fields_row;

                //create row for sorting fields
                $data = [
                    'form_id' => $form_id,
                    'section_id' => $section->id,
                    'fields_row' => $fieldsRow,
                    'time' => time()
                ];
                Model::insert("forms_fields_sort", $data);
            }

            Model::query("UPDATE `forms` SET `sections_row` = '$tempRow', `temp_real_sec` = '$fieldsStr' WHERE `id` = '$form_id'");

            Request::addResponse('redirect', false, url('panel', 'forms', 'edit', $form_id));
            Request::endAjax();
        } else {
            if (Request::isAjax())
                Request::returnErrors($this->validationErrors);
        }
    }

    /**
     * create and add new section to form
     * @param $section_id
     */
    private function addNewSections($form_id)
    {
        $this->validatePost('title', 'Title', 'required|trim|min_length[1]|max_length[255]');

        if ($this->isValid()) {
            $data = array(
                'title'     => post('title'),
                'duplicate' => 1,
                'time'      => time()
            );

            $result = Model::insert('forms_sections', $data); // Update row
            $insertID = Model::insertID();

            if (!$result && $insertID) {
                if ($this->view->form->sections_row)
                    Model::query("UPDATE `forms` SET `sections_row` = CONCAT(`sections_row`, '|$insertID|') WHERE `id` = '$form_id'");
                else
                    Model::update("forms", ['sections_row' => '|' . $insertID . '|'], " `id` = '$form_id'");

                // create row for sorting fields
                $data = [
                    'form_id' => $form_id,
                    'section_id' => $insertID,
                    'fields_row' => '',
                    'time' => time()
                ];
                Model::insert("forms_fields_sort", $data);

                Request::addResponse('redirect', false, url('panel', 'forms', 'edit', $form_id));
            } else
                Request::returnError('Database error');
        } else {
            if (Request::isAjax())
                Request::returnErrors($this->validationErrors);
        }
    }

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

    /**
     * Duplicate section
     * @param $section
     * @param $form_id
     * @return int|string
     */
    private function duplicateSection($section, $form_id)
    {
        $dataSec = [
            'title'         => $section->title,
            'fields_row'    => $section->fields_row,
            'time'          => time(),
            'duplicate'     => 1
        ];

        $insert = Model::insert('forms_sections', $dataSec);
        $sectionId = Model::insertID();

        if (!$insert && $sectionId) {
            $newForm = Model::fetch(Model::select('forms', " `id` = '$form_id'"));
            if ($newForm->sections_row)
                Model::query("UPDATE `forms` SET `sections_row` = REPLACE (`sections_row`, '|$section->id|', '|$sectionId|') WHERE `id` = '$form_id'");
        }

        return $sectionId;
    }

    //Fields
    public function field_popupAction()
    {
        Request::ajaxPart();

        Model::import('panel/forms/sections');
        Model::import('panel/forms/fields');

        $section_id  = Request::getUri(0);
        $form_id     = Request::getUri(1);

        $section = $this->view->section = SectionsModel::get($section_id);
        $form    = $this->view->form = FormsModel::get($form_id);

        if (!$section || !$form)
            redirectAny(url('panel/forms'));

        $this->view->fields = FieldsModel::getAll();

        if ($this->startValidation()) {
            if (post('action_type') == 'new')
                $this->addNewFields($section_id);
            else
                $this->addExistsFields($section_id);
        }
        Request::addResponse('html', '#popup', $this->getView());
    }

    public function copy_fieldAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);

        Model::import('panel/forms/fields');
        Model::import('panel/forms/sections');

        $section_id = post('section_id');
        $form_id = post('form_id');

        $field   = FieldsModel::get($id);
        $section = SectionsModel::get($section_id);
        $form    = FormsModel::get($form_id);

        if (!$field || !$section || !$form)
            redirectAny(url('panel/forms/forms'));

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

        $sectionId = $this->getSectionId($section, $form_id);

        if ($section->fields_row)
            Model::query("UPDATE `forms_sections` SET `fields_row` = CONCAT(`fields_row`, '|$insertId|') WHERE `id` = $sectionId");
        else
            Model::update("forms_sections", ['fields_row' => '|' . $insertId . '|'], " `id` = $sectionId");

        // Update sorting fields
        if (Model::fetch(Model::select('forms_fields_sort', " `section_id` = $sectionId"))) {
            Model::query("UPDATE `forms_fields_sort` SET `fields_row` = CONCAT(`fields_row`, '|$insertId|') WHERE `section_id` = $sectionId");
        } else {
            // Create row for sorting fields
            $data = [
                'form_id' => $form_id,
                'section_id' => $sectionId,
                'fields_row' => $section->fields_row . "|$insertId|",
                'time' => time()
            ];
            Model::insert("forms_fields_sort", $data);
        }

        if (!$resultField && $insertId) {} else
            Request::returnError('Database error');

        $this->view->field = FieldsModel::get($insertId);

        // use temp_row from template for get sections and fields
        $this->view->form_id  = intval($form_id);
        $this->view->form = FormsModel::get($form_id);
        $this->view->sections = [];

        $sectionsArr = stringToArray($this->view->form->sections_row);
        if ($sectionsArr)
            $this->view->sections = SectionsModel::getWhere(" `id` IN ('" . implode( "','", $sectionsArr) . "') ORDER BY FIELD(`id`,'" . implode( "','", $sectionsArr) . "')",'object', $form_id);

        Request::addResponse('html', '#sections', $this->getView('modules/panel/modules/forms/views/sections.php'));
        Request::addResponse('html', '#popup', $this->getView('modules/panel/modules/forms/views/title_popup.php'));

        Request::endAjax();
    }

    public function remove_fieldAction()
    {
        Model::import('panel/forms/fields');
        Model::import('panel/forms/sections');

        $id         = (Request::getUri(0));
        $section_id = post('section_id');
        $form_id    = post('form_id');

        $field   = FieldsModel::get($id);
        $section = SectionsModel::get($section_id);
        $form    = FormsModel::get($form_id);

        if (!$field || !$section || !$form)
            redirectAny(url('panel/forms'));

        //if request from forms module
//        if (post('from') == 'forms') {
//            $sectionId = $this->getSectionId($section, $form->id);
//            $section = SectionsModel::get($sectionId);
//            $form_id = $form_id ?: 0;
//            $redirect = url('panel/forms/edit/' . $form_id);
//        }

        $sectionId = $this->getSectionId($section, $form->id);
        $newSection   = SectionsModel::get($sectionId);

        $fieldsRow = $newSection->fields_row;
        $fieldsArray = stringToArray($fieldsRow);

        foreach ($fieldsArray as $k => $item) {
            if ($item == $field->id)
                unset($fieldsArray[$k]);
        }

        $newFieldsRow = arrayToString($fieldsArray);
        $result = Model::update('forms_sections', ['fields_row' => $newFieldsRow], "`id` = '$sectionId'"); // Update row

        //get sort row and remove field
        $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = $form_id AND `section_id` = $sectionId"));

        // Update sorting fields
        if ($sort) {
            $fieldsArraySort = stringToArray($sort->fields_row);
            foreach ($fieldsArraySort as $k => $item) {
                if ($item == $field->id)
                    unset($fieldsArraySort[$k]);
            }
            $newFieldsRowSort = arrayToString($fieldsArraySort);
            Model::query("UPDATE `forms_fields_sort` SET `fields_row` = '$newFieldsRowSort' WHERE `section_id` = $sectionId AND `form_id` = $form_id");
        } else {
            // Create row for sorting fields
            $section = SectionsModel::get($newSection->id);
            $data = [
                'form_id' => $form_id,
                'section_id' => $sectionId,
                'fields_row' => $section->fields_row,
                'time' => time()
            ];

            Model::insert("forms_fields_sort", $data);
        }

        if (!$result)
            Request::returnError('Database error');

        redirectAny(url('panel/forms/edit/' . $form_id));
    }

    public function change_field_titleAction()
    {
        Request::ajaxPart();

        $id = (Request::getUri(0));
        $form_id = (Request::getUri(1));

        Model::import('panel/forms/fields');
        $field = FieldsModel::get($id);

        if (!$field)
            redirectAny(url('panel/forms'));

        if ($this->startValidation()) {
            $this->validatePost('title',      'Title',         'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('title_date', 'Title Date',    'trim|min_length[1]|max_length[255]');
            $this->validatePost('type',       'Type',          'trim|min_length[1]|max_length[255]');

            $type = post('type');
            // answers for checkbox field
            $answers = post('options') ? arrayToString(post('options')) : '';

            if ($type == 'radio' || $type == 'checkbox')
                $this->validatePost('options', 'Options', 'required');

            if ($type == 'info')
                $answers = post('answer_options');

            if ($this->isValid()) {
                $data = array(
                    'title'            => post('title'),
                    'type'             => $type,
                    'answer_options'   => $answers,
                    'title_date' => '',
                );

                $result = Model::update('forms_fields', $data, " `id` = $id"); // Insert row

                if ($result)
                    Request::addResponse('redirect', false, url('panel', 'forms', 'edit', $form_id));
                else
                    Request::returnError('Database error');
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }
    }

    public function field_edit_popupAction()
    {
        Request::ajaxPart();

        Model::import('panel/forms/sections');
        Model::import('panel/forms/fields');

        $field_id    = Request::getUri(0);
        $section_id  = Request::getUri(1);
        $form_id     = Request::getUri(2);

        $field   = FieldsModel::get($field_id);
        $section = SectionsModel::get($section_id);
        $form    = FormsModel::get($form_id);

        if (!$field || !$section || !$form)
            redirectAny(url('panel/forms'));

        if ($this->startValidation()) {
            $title  = $this->validatePost('title',    'Title',    'required|trim|min_length[1]|max_length[255]');
            $type   = $this->validatePost('type',     'Type',     'required|trim|min_length[1]|max_length[255]');

            // answers for checkbox field
            $answers = post('options') ? arrayToString(post('options')) : '';

            if ($type == 'radio' || $type == 'checkbox')
                $this->validatePost('options', 'Options', 'required');

            if ($type == 'info')
                $answers = post('answer_options');

            if ($this->isValid()) {
                $sectionId = $this->getSectionId($section, $form_id);

                $checkField = Model::fetch(Model::select('forms_sections', " `deleted` = 'no' AND `fields_row` LIKE '%|$field_id|%' AND `id` != $sectionId"));
                // Create duplicate if field used somewhere or its original field
                if ($checkField || $field->duplicate != 1)
                    $fieldId = $this->duplicateField($field, $sectionId);
                else
                    $fieldId = $field->id;

                //update field
                $updateData = [
                    'title'          => $title,
                    'type'           => $type,
                    'answer_options' => $answers,
                ];

                $result = Model::update('forms_fields', $updateData, " `id` = $fieldId");

                if ($result) {
                    Request::addResponse('redirect', false, url('panel', 'forms', 'edit', $form_id));
                } else {
                    Request::returnError('Database error');
                }

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

        $this->view->field    = $field;
        $this->view->section  = $section;
        $this->view->form     = $form;
        Request::addResponse('html', '#popup', $this->getView());
    }

    public function change_status_fieldAction()
    {
        Request::ajaxPart();

        $field_id    = post('field_id');
        $section_id  = post('section_id');
        $form_id     = post('form_id');

        Model::import('panel/forms/fields');
        Model::import('panel/forms/sections');

        $field    = FieldsModel::get($field_id);
        $section  = SectionsModel::get($section_id);
        $form    = FormsModel::get($form_id);

        if (!$field || !$section || !$form)
            redirectAny('panel/forms');

        $status = Model::fetch(Model::select('forms_fields_status', " `form_id` = $form_id AND `section_id` = $section_id AND `field_id` = $field_id"));

        $gray = 'yes';
        if ($status) {
            $gray = $status->gray;

            $gray == 'no' ? $gray = 'yes' : $gray = 'no';

            Model::update('forms_fields_status', ['gray' => $gray], " `form_id` = $form_id AND `section_id` = $section_id AND `field_id` = $field_id" );
        } else {
            Model::insert('forms_fields_status', ['gray' => $gray, "form_id" => $form_id, "section_id" => $section_id, "field_id" => $field_id, 'time' => time()]);
        }

        Request::addResponse('func', 'noticeSuccess', 'Changed!');
        Request::endAjax();
    }

    private function duplicateField($field, $section_id)
    {
        $data = [
            'title'           => $field->title,
            'answer_options'  => $field->answer_options,
            'time'            => time(),
            'duplicate'       => 1
        ];

        $insert = Model::insert('forms_fields', $data);
        $fieldId = Model::insertID();

        if (!$insert && $fieldId) {
            $section = Model::fetch(Model::select('forms_sections', " `id` = '$section_id'"));
            if ($section->fields_row) {
                Model::query("UPDATE `forms_sections` SET `fields_row` = REPLACE (`fields_row`, '|$field->id|', '|$fieldId|'), 
                                     `fields_real_sec` = REPLACE (`fields_real_sec`, '|$field->id|', '') WHERE `id` = '$section_id'");

                Model::query("UPDATE `forms_fields_sort` SET `fields_row` = REPLACE (`fields_row`, '|$field->id|', '|$fieldId|') WHERE `section_id` = '$section_id'");
            }
        }

        return $fieldId;
    }

    /**
     * Add exists fields to section
     * @param $section_id
     */
    private function addExistsFields($section_id)
    {
        $this->validatePost('field_ids',     'Field',      'required|min_count[1]');

        if ($this->isValid()) {
            $sectionId = $this->getSectionId($this->view->section, $this->view->form->id);

            $fieldsStr = arrayToString(post('field_ids'));
            $fieldsRow = $this->view->section->fields_row; // new fields_row
            $oldRealFields = $this->view->section->fields_real_sec; // old field_ids row

            // Add new fields to row
            foreach (post('field_ids') as $field_id) {
                if (mb_strpos($fieldsRow, "|$field_id|") === false)
                    $fieldsRow .= "|$field_id|";
                $oldRealFields = str_replace("|$field_id|", "", $oldRealFields);
            }

            // remove fields from row only for original field
            $fieldsArray = stringToArray($fieldsRow);
            foreach ($fieldsArray as $k => $field_id) {
                $field = FieldsModel::get($field_id); //todo try to take out for a foreach
                if ($field->duplicate == 0) {
                    if (!in_array($field_id, post('field_ids')))
                        unset($fieldsArray[$k]);
                }
            }
            $fieldsRow = arrayToString($fieldsArray);

            // Remove old real fields
            foreach (stringToArray($oldRealFields) as $old_id)
                $fieldsRow = str_replace("|$old_id|", "", $fieldsRow);

            $result = Model::update('forms_sections', ['fields_row' => $fieldsRow, 'fields_real_sec' => $fieldsStr], " `id` = '$sectionId'");
            if ($result) {
                // Update sorting fields
                if (Model::fetch(Model::select('forms_fields_sort', " `section_id` = $sectionId AND `form_id` = '{$this->view->form->id}'"))) {
                    Model::query("UPDATE `forms_fields_sort` SET `fields_row` = '$fieldsRow' WHERE `section_id` = '$sectionId' AND `form_id` = '{$this->view->form->id}'"); // todo
                } else {
                    // Create row for sorting fields
                    $data = [
                        'form_id' => $this->view->form->id,
                        'section_id' => $sectionId,
                        'fields_row' => $fieldsRow,
                        'time' => time()
                    ];
                    Model::insert("forms_fields_sort", $data);
                }
                Request::addResponse('redirect', false, url('panel', 'forms', 'edit', $this->view->form->id));
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
            $sectionId = $this->getSectionId($this->view->section, $this->view->form->id);

            $data = array(
                'title'              => post('title'),
                'type'               => post('type'),
                'answer_options'     => $options ?: '',
                'duplicate'          => 1,
                'time'               => time()
            );

            $result = Model::insert('forms_fields', $data); // Update row
            $insertID = Model::insertID();

            if (!$result && $insertID) {

                if ($this->view->section->fields_row)
                    Model::query("UPDATE `forms_sections` SET `fields_row` = CONCAT(`fields_row`, '|$insertID|') WHERE `id` = '$sectionId'");
                else
                    Model::update("forms_sections", ['fields_row' => '|' . $insertID . '|'], " `id` = '$sectionId'");

                // Update sorting fields
                if (Model::fetch(Model::select('forms_fields_sort', " `section_id` = $sectionId"))) {
                    Model::query("UPDATE `forms_fields_sort` SET `fields_row` = CONCAT(`fields_row`, '|$insertID|') WHERE `section_id` = '$sectionId'");

                } else {
                    // Create row for sorting fields
                    $data = [
                        'form_id' => $this->view->form->id,
                        'section_id' => $sectionId,
                        'fields_row' => $this->view->section->fields_row . "|$insertID|",
                        'time' => time()
                    ];
                    Model::insert("form_fields_sort", $data);
                }

                Request::addResponse('redirect', false, url('panel', 'forms', 'edit', $this->view->form->id));
                Request::endAjax();
            } else
                Request::returnError('Database error');
        } else {
            if (Request::isAjax())
                Request::returnErrors($this->validationErrors);
        }
    }

}
/* End of file */
<?php

use Dompdf\Dompdf;

class FormController extends Controller
{
    use Validator;

    public function indexAction()
    {
        $id = Request::getUri(0);
        if (get('mode') == 'answers') {
            $progress = Model::fetch(Model::select('forms_progress', " `id` = $id"));

            if (!$progress)
                redirect(url('/'));

            $form_id = $progress->form_id;
            $this->view->form = FormModel::get($form_id, $progress->id);
            $this->view->progress = $progress;
        } else {
            $this->view->form = FormModel::get($id);
        }

        if (!$this->view->form)
            redirect(url('/'));

        $this->view->sections = [];
        $sectionsArr = stringToArray($this->view->form->sections_row);

        if ($sectionsArr) {
            Model::import('panel/forms/sections');
            $sections = SectionsModel::getWhere(" `id` IN ('" . implode( "','", $sectionsArr)  . "') ORDER BY FIELD(`id`, '" . implode( "','", $sectionsArr)  . "')", 'object', $this->view->form->id);

            // set answers for fields
            foreach ($sections as $section) {
                foreach ($section->fields as $field) {
                    foreach ($this->view->form->answers as $answer) {
                        if ($field->id == $answer->field_id && $section->id == $answer->section_id ) {
                            $field->answer = $answer->answer;
                        }
                    }

                }

                if (get('mode') == 'answers') {
                    //set images for answers
                    $fieldsArr = array_map(function ($o) {
                        return $o->id;
                    }, $section->fields);

                    $images = SectionsModel::getFieldsAnswers($fieldsArr, $form_id, $section->id);
                    if ($images) {
                        foreach ($section->fields as $field) {
                            $field->answer_images = [];
                            foreach ($images as $image) {
                                if ($image->field_id === $field->id)
                                    $field->answer_images[] = $image->image;
                            }
                        }
                    }
                }
            }

            $this->view->sections = $sections;
        }

        Request::setTitle($this->view->form->title);
    }

    public function saveAction()
    {
        Request::ajaxPart();

        $progress_id = post('progress_id');
        $form_id     = post('form_id');
        $form        = FormModel::get($form_id);

        if (!$form)
            Request::returnError('invalid Form');

        // get form sections
        $this->view->sections = [];
        $sectionsArr = stringToArray($form->sections_row);
        if ($sectionsArr) {
            Model::import('panel/forms/sections');
            $fieldsRow = Model::fetchAll(Model::query(" SELECT * FROM `forms_sections` WHERE `id` IN ('" . implode("','", $sectionsArr) . "')"));

            if (!$progress_id) {
                $data = [
                    'form_id' => $form->id,
                    'title' => post('title'),
                    'time' => time()
                ];
                Model::insert('forms_progress', $data);
                $progress_id = Model::insertID();
            }

            // create array with fields ids
            foreach ($fieldsRow as $string) {
                $fieldsArray = [];
                $fields = stringToArray($string->fields_row);
                $string->fields = array_merge($fieldsArray, $fields);
            }

            if ($fieldsRow) {
                $result = FormModel::insertAnswers($fieldsRow, $form_id, $progress_id);

                if (!$result)
                    Request::returnError('DB Error');
                else {
                    // images for answers
                    $images = post('images');
                    foreach ($fieldsRow as $section) {
                        foreach ($section->fields as $fieldId) {

                            //get all images from DB and check
                            $dbImages = Model::fetchAll(Model::select('forms_answers_images', "`field_id` = $fieldId AND `form_id` = $form_id AND `section_id` = {$section->id}"));
                            foreach ($dbImages as $image) {
                                if (!in_array($image->image, $images[$fieldId .'_' . $section->id] ?: [])) {
                                    File::remove('data/form_builder/answers/' . $image->image);
                                }
                            }
                            Model::delete('forms_answers_images', "`field_id` = $fieldId AND `form_id` = $form_id AND `section_id` = {$section->id}");

                            if ($fieldImages = $images[$fieldId .'_' . $section->id]) {
                                if ($fieldImages) {
                                    foreach ($fieldImages as $image) {

                                        if (!File::copy('data/tmp/' . $image, 'data/form_builder/answers/' . $image))
                                            Request::returnError('Image copy error: ' . error_get_last()['message']);

                                        Model::insert('forms_answers_images', [
                                            'field_id' => $fieldId,
                                            'form_id' => $form_id,
                                            'section_id' => $section->id,
                                            'image' => $image,
                                        ]);
                                    }
                                }

                            }
                        }
                    }
                }

                Request::addResponse('redirect', false,   url('form/' . $progress_id . '?mode=answers'));
            }

        }

        Request::endAjax();
    }

    public function pdfAction()
    {
        Request::ajaxPart();

        $id = Request::getUri(0);
        $progress = Model::fetch(Model::select('forms_progress', " `id` = $id"));

        if (!$progress)
            redirect(url('/'));

        $form_id = $progress->form_id;
        $form    = FormModel::get($form_id, $progress->id);

        if (!$form)
            redirect(url('/'));

        $this->view->form = $form;
        $sectionsArr = stringToArray($form->sections_row);
        if ($sectionsArr) {
            Model::import('panel/forms/sections');
            $sections = SectionsModel::getWhere(" `id` IN ('" . implode( "','", $sectionsArr)  . "') ORDER BY FIELD(`id`, '" . implode( "','", $sectionsArr)  . "')", 'object', $form->id);

            // set answers for fields
            foreach ($sections as $section) {
                foreach ($section->fields as $field) {
                    foreach ($form->answers as $answer) {
                        if ($field->id == $answer->field_id && $section->id == $answer->section_id )
                            $field->answer = $answer->answer;
                    }
                }
            }

            $newTitle = preg_replace(
                '~\s\(\d{1,2}\.\d{1,2}\/\d{1,2}:\d{1,2}:\d{1,2}\)~i',
                '',
                $form->title
            );

            //create pdf
            $this->create_pdf('form-' . $newTitle . '.pdf', $form, $sections);
        } else {
            Request::returnError('No data');
        }

        Request::endAjax();
    }

    private function create_pdf($filename, $form, $sections)
    {
        require_once(_SYSDIR_ . 'system/lib/vendor/autoload.php');

        // instantiate and use the dompdf class
        $dompdf = new Dompdf([
            'enable_remote' => true,
            'chroot'  => _SYSDIR_ .'data',
        ]);

        $this->view->form = $form;
        $this->view->sections = $sections;
//        $html = $this->pdf_template($form, $sections);

        ob_start();
        include _SYSDIR_ . 'modules/form/views/pdf_template.php';
        $contents = ob_get_clean();

        $dompdf->loadHtml($contents);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser
        // $dompdf->stream('CV.pdf',array('Attachment'=>0));
        $content = $dompdf->output();

        File::write(File::mkdir('data/form_builder/') . $filename, $content);

        Request::addResponse('func', 'downloadFile', _SITEDIR_ .'data/form_builder/' . $filename);
    }
}
/* End of file */
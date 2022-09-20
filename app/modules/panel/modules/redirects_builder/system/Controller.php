<?php

ini_set('display_errors', '0');
require_once _SYSDIR_ . 'system/lib/PHPExcel/Classes/PHPExcel.php';

class Redirects_builderController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        Request::setTitle('Exel parser');
    }

    public function parseAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('document',     'Document',     'required|trim|min_length[1]|max_length[200]');
            $this->validatePost('sheet_name',   'Name Sheet',   'trim|min_length[0]|max_length[255]');
            $this->validatePost('old_cell',     'Old URLs',     'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('new_cell',     'New URLs',     'required|trim|min_length[1]|max_length[255]');
            $this->validatePost('start_cell',   'Start Cell Number', 'required|is_numeric');
            $this->validatePost('end_cell',     'End Cell Number',   'required|is_numeric');

            if (post('start_cell') > post('end_cell')) {
                $this->addError('start_cell', 'The Start Cell Number cannot be greater than the End Cell Number!');
            }

            if ($this->isValid()) {
                $file = _SYSDIR_ . 'data/tmp/' . post('document');

                try {
                    $file_type = PHPExcel_IOFactory::identify($file);
                    $objReader = PHPExcel_IOFactory::createReader($file_type);

                    $objPHPExcel = $objReader->load($file);

                    if (post('sheet_name')) {
                        $sheet = $objPHPExcel->getSheetByName(post('sheet_name'));
                    } else {
                        $sheet = $objPHPExcel->getActiveSheet();
                    }

                    if (!$sheet) {
                        Request::returnErrors(['sheet_name' => 'Sheet name not found.']);
                    }

                    $pathFile = $this->getSectors($sheet);

                    if ($pathFile) {
                        Request::addResponse('func', 'downloadFileNew', $pathFile . '||redirects');
                    }
                } catch (Exception $ex) {
                    Request::returnErrors(['error' => $ex->getMessage()]);
                }

                Request::addResponse('func', 'noticeSuccess', 'Redirect file successfully generated');
                Request::endAjax();
            } else {
                if (Request::isAjax()) {
                    Request::returnErrors($this->validationErrors);
                }
            }
        }

        redirect(url('panel/exel_parser'));
    }

    private function getSectors($sheet)
    {
        $sectors = [];
        $cells = [
            strtoupper(post('old_cell')),
            strtoupper(post('new_cell')),
        ];

        for ($i = post('start_cell'); $i <= post('end_cell'); $i++) {
            foreach ($cells as $cell) {
                $value = trim($sheet->getCell($cell . $i)->getValue());

                preg_match('#https://([^/]+(/.*))#', $value, $matches);

                $cellsValue[] = $matches ? $matches[2] : $value;
            }

            if ($cellsValue[0] === '/' || $matches[2] === '/') {
                $cellsValue = null;
                continue;
            }

            strpos($matches[2], '#') === false ? $ends = '/? [L,R=301]' : $ends = '? [L,R=301,NC,NE]';

            $sectors[] = "RewriteRule ^" . ltrim($cellsValue[0], '/') . "?$ /" . rtrim(ltrim($matches[2], '/'), '/') . "$ends\n";

            $cellsValue = null;
        }

        File::write(_SYSDIR_ . 'data/tmp/redirects.txt', $sectors);

        return _SITEDIR_ . 'data/tmp/redirects.txt';
    }
}
/* End of file */

<?php

require 'app/system/lib/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory as IO;

class ParserController extends Controller
{
    protected $layout = 'layout_panel';

    public function indexAction()
    {
        Request::setTitle('Parser | Script');
    }

    public function upload_parseAction()
    {
        Request::ajaxPart();

        $name = post('name', true); // file name, if not set - will be randomly
        $path = post('path', true, 'tmp'); // path where file will be saved, default: 'tmp'
        $field = post('field', true, '#image'); // field where to put file name after uploading
        $preview = post('preview', true, '#preview_file'); // field where to put file name after uploading
        $real_name = post('file_real_name', true); // field where to put file name after uploading

        $result = null;
        foreach ($_FILES as $file) {
            $result = File::uploadFile($file, 'data/' . $path . '/', $name);
            break;
        }

        $absolutePath = $result['path'] . $result['name'] . '.' . $result['format'];

        if (!in_array(strtolower($result['format']), ['csv', 'xls', 'xlsx'])) {
            Request::returnError('This extension is not supported');
        } else {
            $columns = (new CustomSpreadSheet($absolutePath))::getColumns();
            if ($columns) {
                $maxCountElemInColumn = CustomSpreadSheet::getMaxLengthColumn($columns) + 1;

                $newFileName = $result['name'] . '.' . $result['format'];

                foreach ($columns as $index => $column) {
                    $this->view->titles[$index] = $column[0];
                }

                Request::addResponse('val', '#abs_path', $absolutePath); // path to file
                Request::addResponse('val', '#total_count', $maxCountElemInColumn); // total count row
                Request::addResponse('val', '#file_name', $result['fileName']); // total count row

                Request::addResponse('val', $field, $newFileName);
                Request::addResponse('val', $real_name, str_replace(' ', '_', $result['fileName']));
                Request::addResponse('html', $preview, $result['fileName']);
                Request::addResponse('html', '#table', $this->getView('modules/panel/modules/parser/views/checkboxes.php'));
            } else {
                Request::returnError('Empty File');
            }
        }
    }

    public function get_dataAction()
    {
        Request::ajaxPart();

        $fields = post('fields', 'int');
        $absPath = post('abs_path');
        $totalCount = post('total_count');
        $fileName = post('file_name');

        $ranges = 'all';
        if (!empty($fields))
            $ranges = CustomSpreadSheet::generateRangesByIndexes($fields, $totalCount);

        $columns = (new CustomSpreadSheet($absPath, $ranges))::getColumns();

        $this->view->filename = $fileName;
        $this->view->json = json_encode($columns, JSON_PRETTY_PRINT);

        Request::addResponse('html', '#result', $this->getView());

    }

}

class CustomSpreadSheet
{
    private static $ranges;
    private static $worksheet;

    private static $defaultTitleNull = 'Null';
    private static $nullValue = '';


    public function __construct($filepath, $ranges = false)
    {
        self::$ranges = $ranges;
        self::$worksheet = IO::load($filepath)->getActiveSheet();

    }

    public static function getRows()
    {
        return self::$worksheet->toArray(self::$nullValue);
    }

    public static function getColumns()
    {
        if (self::$ranges == 'all') {
            return self::removeNullCellForColumns(self::rowsToColumns(self::getRows()), false);
        }

        if (self::$ranges) {
            return self::removeNullCellForColumns(self::getColumnsByRanges(), false);
        }
        return self::removeNullCellForColumns(self::rowsToColumns(self::getRows()));
    }

    private static function getColumnsByRanges()
    {
        $columns = [];
        foreach (self::$ranges as $range) {
            $columns[] = self::$worksheet->rangeToArray($range, self::$nullValue);
        }
        foreach ($columns as $columnIndex => $column) {
            foreach ($column as $cellIndex => $cell) {
                $columns[$columnIndex][$cellIndex] = $cell[0];
            }
        }
        return $columns;
    }

    private static function rowsToColumns($rows)
    {
        $columns = [];
        foreach ($rows[0] as $cellIndex => $cell) {
            $column = [];
            foreach ($rows as $row) {
                $column[] = $row[$cellIndex];
            }
            $columns[] = $column;
        }

        return $columns;
    }

    private static function removeNullCellForColumns($columns, $saveIndex = true)
    {
        foreach ($columns as $columnIndex => $column) {
            foreach ($column as $cellIndex => $cell) {

                if (!$cell && $cellIndex == 0) {
                    $columns[$columnIndex][$cellIndex] = self::$defaultTitleNull;

                } else if (!$cell) {
                    unset($columns[$columnIndex][$cellIndex]);
                }
            }
            if (count($columns[$columnIndex]) == 1) {
                unset($columns[$columnIndex]);
            }
        }
        if (!$saveIndex) {
            $columns = array_values($columns);
            foreach ($columns as $columnIndex => $column) {
                $columns[$columnIndex] = array_values($column);
            }
        }

        return $columns;
    }

    // Libs
    public static function getMaxLengthColumn($columns)
    {
        $maxLengthColumn = 0;
        foreach ($columns as $column) {
            $count = array_key_last($column);
            if ($count > $maxLengthColumn) {
                $maxLengthColumn = $count;
            }
        }
        return $maxLengthColumn;
    }

    public static function generateRangesByIndexes($indexes, $maxLengthColumn)
    {
        $coordinates = [];
        foreach ($indexes as $index) {
            $coordinates[] = Coordinate::stringFromColumnIndex($index);
        }

        $ranges = [];
        foreach ($coordinates as $coordinate) {
            $ranges[] = sprintf('%s1:%s%s', $coordinate, $coordinate, $maxLengthColumn);
        }
        return $ranges;
    }
}

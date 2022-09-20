<?php
class SectionsModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = [];

        foreach ($queries as $query)
            self::query($query);
    }

    /**
     * Method module_update start automatically if current $version != version in `modules` table, and start from "case 'i'", where i = prev version in modules` table
     * @param int $version
     */
    public function module_update($version)
    {
        $queries = array();

        switch ($version) {
//            case '0':
//                $queries[] = "ALTER TABLE `form_templates_sections` ADD COLUMN `duplicate` tinyint DEFAULT 0 AFTER `fields_row`";
        }

        foreach ($queries as $query)
            self::query($query);
    }

    /**
     * Get user by $id
     * @param $id
     * @return array|object|null
     */
    public static function get($id)
    {
        $sql = "
            SELECT *
            FROM `forms_sections`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll($where = false)
    {
        $sql = "
            SELECT *
            FROM `forms_sections`
            WHERE `deleted` = 'no'
        ";

        if ($where !== false)
            $sql .= " AND $where";

        return self::fetchAll(self::query($sql));
    }

    public static function getWhere($where = false, $mode = 'object', $template_id = false)
    {
        $sql = "
            SELECT *
            FROM `forms_sections`
            WHERE `deleted` = 'no'
        ";

        if ($where !== false)
            $sql .= " AND $where";

        $sections = self::fetchAll(self::query($sql), $mode);

        if ($sections) {
            Model::import('panel/forms/fields');
            foreach ($sections as $section) {
                $section->fields = [];
//                $fieldsArr = explode('||', trim($section->fields_row, '|'));
                $sort = Model::fetch(Model::select('forms_fields_sort', " `form_id` = $template_id AND `section_id` = $section->id"));
                $fieldsArr = explode('||', trim($sort->fields_row, '|'));
                if ($fieldsArr) {
                    $section->fields = FieldsModel::getWhere(" `id` IN ('" . implode( "','", $fieldsArr)  . "') ORDER BY FIELD(`id`, '" . implode( "','", $fieldsArr)  . "')", 'object', $template_id, $section->id);

                    //set images for fields
                    $images = self::getFieldsImages($fieldsArr);
                    if ($images) {
                        foreach ($section->fields as $field) {
                            $field->images = [];
                            foreach ($images as $image) {
                                if ($image->field_id === $field->id && $image->form_id == $template_id && $image->section_id == $section->id)
                                    $field->images[] = $image->image;
                            }
                        }
                    }
                }
            }
        }
        return $sections;
    }

    public static function getFieldsImages($fieldsIds)
    {
        $sql = "
            SELECT *
            FROM `forms_fields_images`
            WHERE `field_id` IN(" . implode(', ', $fieldsIds) . ")
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getFieldsAnswers($fieldsIds, $form_id, $section_id)
    {
        $sql = "
            SELECT *
            FROM `forms_answers_images`
            WHERE `field_id` IN(" . implode(', ', $fieldsIds) . ") AND `form_id` = $form_id AND `section_id` = $section_id
        ";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
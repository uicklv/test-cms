<?php
class FieldsModel extends Model
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
        $queries = [];

        switch ($version) {
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
            FROM `forms_fields`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $field = self::fetch(self::query($sql));

        if ($field) {
            $field->images = Model::fetchAll(Model::select('forms_fields_images', " `field_id` = " . $field->id));
        }

        return $field;
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll($where = false)
    {
        $sql = "
            SELECT *
            FROM `forms_fields`
            WHERE `deleted` = 'no'
        ";

        if ($where)
            $sql .= " AND $where";

        return self::fetchAll(self::query($sql));
    }

    public static function getWhere($where = false, $mode = 'object', $form_id = false, $section_id = false)
    {
        $sql = "
            SELECT *
            FROM `forms_fields`
            WHERE `deleted` = 'no'
        ";

        if ($where !== false)
            $sql .= " AND $where";

        $fields = self::fetchAll(self::query($sql), $mode, 'id');

        if ($form_id && $section_id) {
            if ($fields) {
                foreach ($fields as $field) {
                    $field->gray = 'no';
                    $status = Model::fetch(Model::select('forms_fields_status', " `form_id` = $form_id AND `section_id` = $section_id AND `field_id` = $field->id"));
                    if ($status)
                        $field->gray = $status->gray;
                }
            }
        }

        return $fields;
    }
}

/* End of file */
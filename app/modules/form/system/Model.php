<?php
class FormModel extends Model
{
    public $version = 0;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array();

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

        }

        foreach ($queries as $query)
            self::query($query);
    }


    public static function get($id , $progress_id = false)
    {
        $sql = "
            SELECT *
            FROM `forms`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $form =  self::fetch(self::query($sql));
        if ($form) {
            $form->answers = [];
            if ($progress_id) {
                $answers = Model::fetchAll(Model::select('forms_answers', " `form_id` = '" . $form->id . "' AND `progress_id` = $progress_id"));
                if (is_array($answers) && count($answers) > 0) {
                    $form->answers = $answers;
                }
            }
        }
        return $form;
    }

    public static function insertAnswers($fieldsRow, $form_id, $progress_id)
    {
        $sql = "INSERT INTO `forms_answers`(`form_id`, `field_id`, `answer`, `time`, `progress_id`, `section_id`) VALUES ";
        // prepare data for insert
        foreach ($fieldsRow as $row) {
            foreach ($row->fields as $k => $fieldId) {
                Model::delete('forms_answers', " `form_id` = '$form_id' AND `field_id` IN ('" . implode("','", $row->fields) . "')");
                if (post('field_' . $fieldId . '_' . $row->id)) {
                    $answer = post('field_' . $fieldId . '_' . $row->id);
                    //check if field checkbox type
                    if (is_array($answer))
                        $answer = arrayToString($answer);

                    $sql .= "('" . $form_id . "', '" . $fieldId . "', '" . $answer . "', '" . time() . "', $progress_id, $row->id),";
                }
            }
        }

        $sql = rtrim($sql, ",");

        return Model::query($sql);
    }
}

/* End of file */
<?php
class Data_generatorModel extends Model
{
    public $version = 1; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `fake_data` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `table` varchar(200) NOT NULL,
                `number` varchar(200) NOT NULL,
                `type`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `column` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `existing_table` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;"
        );

        foreach ($queries as $query)
            self::query($query);

    }

    /**
     * Method module_update start automatically if current $version != version in `modules` table, and start from "case 'i'", where i = prev version in modules` table
     * @param int $version
     */
    public function module_update($version)
    {

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
            FROM `fake_data`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll()
    {
        $sql = "
            SELECT *
            FROM `fake_data`
            WHERE `deleted` = 'no'
        ";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
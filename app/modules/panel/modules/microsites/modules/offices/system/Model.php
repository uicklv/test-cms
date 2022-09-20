<?php
class OfficesModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `microsites_offices` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `microsite_id` int(11) NOT NULL,
                `name` varchar(100) NOT NULL,
                `coordinates` varchar(100) NOT NULL,
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
        $queries = array();

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
            FROM `microsites_offices`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll($microsite_id = false)
    {
        $sql = "
            SELECT *
            FROM `microsites_offices`
            WHERE `deleted` = 'no'
        ";

        if ($microsite_id !== false)
            $sql .= " AND `microsite_id` = '$microsite_id'";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
<?php
class ResourcesModel extends Model
{
    public $version = 2; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `resources` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `image` varchar(60) DEFAULT NULL,
                `file` varchar(100) DEFAULT NULL,
                `type` varchar(100) DEFAULT NULL,
                `posted` enum('no','yes') DEFAULT 'yes',
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(10) DEFAULT 0,
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
            case '0':
                $queries[] = "ALTER TABLE `resources` ADD COLUMN `author` varchar(255) DEFAULT NULL AFTER `content`;";
            case '1':
                $queries[] = "CREATE TABLE IF NOT EXISTS `resource_downloads` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `resource_id` int(10) unsigned NOT NULL,
                    `firstname` varchar(255) DEFAULT NULL,
                    `lastname` varchar(255) DEFAULT NULL,
                    `email` varchar(255) DEFAULT NULL,
                    `time` int(10) unsigned NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;";
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
            FROM `resources`
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
            FROM `resources`
            WHERE `deleted` = 'no'
        ";

        return self::fetchAll(self::query($sql), 'object', 'id');
    }

    public static function getAllPublic($keywords = false, $type = false, $start = false, $end = false)
    {
        $sql = "
            SELECT *
            FROM `resources`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
        ";

        if ($keywords) {
            $sql .= " AND (`title` LIKE '%$keywords%' OR `content` LIKE '%$keywords%' OR `type` LIKE '%$keywords%')";
        }

        if ($type) {
            $sql .= " AND `type` IN ('" . implode("','", $type) . "')";
        }

        $sql .= " ORDER BY `time` DESC";

        if ($start !== false) {
            $sql .= " LIMIT $start";

            if ($end !== false)
                $sql .= ", $end";
        }

        return self::fetchAll(self::query($sql));
    }

    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `resources`
            WHERE `deleted` = 'yes'
        ";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
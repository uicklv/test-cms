<?php
class Event_cardModel extends Model
{
    public $version = 10; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `event_card` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(200) NOT NULL,
                `image` varchar(60) DEFAULT NULL,
                `is_previous` enum('no','yes') DEFAULT 'no',
                `category` varchar(255) DEFAULT NULL,
                `link` varchar(100) DEFAULT NULL,
                `published` varchar(10) DEFAULT 'no',
                `deleted` enum('no','yes') DEFAULT 'no',
                `subtitle` varchar(255) DEFAULT NULL,
                `posted` enum('no','yes') DEFAULT 'yes',
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `events_categories` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar (255) DEFAULT NULL,
                `color` varchar (255) DEFAULT NULL,
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

//        switch ($version) {
//        }

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
            FROM `event_card`
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
            FROM `event_card`
            WHERE `deleted` = 'no'
        ";

        if ($where) {
            $sql .= " $where";
        }

        return self::fetchAll(self::query($sql));
    }

    public static function search($where = false, $limit = false, $order = 'new')
    {
        $sql = "
            SELECT *
            FROM `event_card`
            WHERE `deleted` = 'no' AND `published` = 'yes'
        ";

        if ($where)
            $sql .= " AND $where";

        if ($order == 'new')
            $sql .= " ORDER BY `event_card`.`time` DESC";
        elseif($order == 'old')
            $sql .= " ORDER BY `event_card`.`time` ASC";

        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        $events =  self::fetchAll(self::query($sql));

        if ($events) {
            foreach ($events as $item) {
                $item->categories = [];
                if ($item->category) {
                    $categoriesIds = stringToArray($item->category);
                    $categories = Model::fetchAll(Model::select('events_categories', " `id` IN (" . implode(',', $categoriesIds)  .")"));

                    $item->categories = $categories;
                }
            }
        }

        return $events;
    }


    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `event_card`
            WHERE `deleted` = 'yes'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getPublished()
    {
        $sql = "
            SELECT *
            FROM `event_card`
            WHERE `deleted` = 'no' AND `published` = 'yes'
            ORDER BY `id` DESC
        ";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
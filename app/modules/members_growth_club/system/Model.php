<?php
class Members_growth_clubModel extends Model
{
    public $version = 0;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `club_users` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `email` varchar(200) NOT NULL,
                `role` enum('unconfirmed','user') DEFAULT 'unconfirmed',
                `password` varchar(60) DEFAULT '',
                `token` varchar(50) DEFAULT NULL,
                `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `lastname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `deleted` enum('no','yes') DEFAULT 'no',
                `reg_time` int(10) unsigned NOT NULL,
                `last_time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY (`email`)
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
     * @param $slug
     * @return array|object|null
     */
    public static function getBySlug($slug)
    {
        $sql = "
            SELECT *
            FROM `club_blog`
            WHERE `slug` = '$slug' AND `deleted` = 'no' AND `posted` = 'yes'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getPrevBlog($id)
    {
        $sql = "
            SELECT *
            FROM `club_blog`
            WHERE `id` < '$id' AND `deleted` = 'no' AND `posted` = 'yes'
            ORDER BY `id` DESC
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getNextBlog($id)
    {
        $sql = "
            SELECT *
            FROM `club_blog`
            WHERE `id` > '$id' AND `deleted` = 'no' AND `posted` = 'yes'
            ORDER BY `id` ASC
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll($start = false, $end = false)
    {
        $sql = "
            SELECT *
            FROM `club_blog`
            WHERE `deleted` = 'no' AND `posted` = 'yes'
            ORDER BY `time` DESC
        ";

        if ($start !== false) {
            $sql .= " LIMIT $start";

            if ($end !== false)
                $sql .= ", $end";
        }

        return self::fetchAll(self::query($sql));
    }

    /**
     * @param $id
     * @return array|object|null
     */
    public static function getClubUser($id)
    {
        $sql = "
            SELECT *
            FROM `club_users`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * @param $id
     * @param $data
     * @return bool|string
     */
    public static function updateClubUserByID($id, $data)
    {
        return self::update('club_users', $data, "`id` = '$id' LIMIT 1");
    }

    /**
     * @param $email
     * @return array|object|null
     */
    public static function getUserByEmail($email)
    {
        $sql = "
            SELECT *
            FROM `club_users`
            WHERE `email` = '$email' 
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }
}

/* End of file */
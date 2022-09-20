<?php
class TeamModel extends Model
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

    public static function getUserImage($id)
    {
        $sql = "
            SELECT *
            FROM `user_images`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getUserImages($uid)
    {
        $sql = "
            SELECT *
            FROM `user_images`
            WHERE `user_id` = '$uid'
            ORDER BY `id` ASC
        ";

        return self::fetchAll(self::query($sql));
    }

    /**
     * Get user by $id
     * @param $id
     * @return array|object|null
     */
    public static function getUser($id)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * @param $email
     * @return array|object|null
     */
    public static function getUserByEmail($email)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `email` = '$email'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get all users
     * @return array
     */
    public static function getAllUsers()
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `deleted` = 'no'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getUsersWhere($where)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `deleted` = 'no'
            AND $where
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `deleted` = 'yes'
            AND `role` IN ('admin', 'moder')
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function countUsers($where = false)
    {
        $sql = "
            SELECT COUNT(`id`)
            FROM `users`
        ";

        if ($where)
            $sql .= "WHERE ".$where;

        return self::fetch(self::query($sql), 'row')[0];
    }

    public static function getBiggestSort()
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `deleted` = 'no'
            ORDER BY `sort` DESC
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getNextSmallestSort($sort)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `deleted` = 'no' AND `sort` > 0 AND `sort` < '$sort'
            ORDER BY `sort` DESC
            LIMIT 1
        ";

//        print_data($sql);

        return self::fetch(self::query($sql));
    }

    public static function getNextBiggestSort($sort)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `deleted` = 'no' AND `sort` > 0 AND `sort` > '$sort'
            ORDER BY `sort` ASC
            LIMIT 1
        ";

//        print_data($sql);

        return self::fetch(self::query($sql));
    }
}

/* End of file */
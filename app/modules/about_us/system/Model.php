<?php
class About_usModel extends Model
{
    public $version = 1;

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

    /**
     * @return array
     */
    public static function getUsers($where)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `deleted` = 'no'
        ";

        if ($where)
            $sql .= $where;

        return self::fetchAll(self::query($sql));
    }

    public static function getUser($slug)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `slug` = '$slug' AND `deleted` = 'no'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getUserByID($id)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `id` = '$id' AND `deleted` = 'no'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }
}

/* End of file */
<?php
class CategoriesModel extends Model
{
    public $version = 0; // increment it for auto-update

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

    public static function removeUsers($cid)
    {
        $sql = "
            DELETE 
            FROM `ld_access_categories` 
            WHERE `category_id` = '$cid'
        ";

        return self::query($sql);
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
            FROM `ld_categories`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $category =  self::fetch(self::query($sql));

        if ($category) {
            // Sectors
            $category->users_ids = array();
            $category->users = array();
            $users = self::getCategoryUsers($category->id);

            if (is_array($users) && count($users)) {
                foreach ($users as $user) {
                    $category->users_ids[] = $user->user_id;
                    $category->users[] = $user;
                }
            }
        }

        return $category;
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll()
    {
        $sql = "
            SELECT *
            FROM `ld_categories`
            WHERE `deleted` = 'no'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getAllWithAccess($uid)
    {
        $sql = "
            SELECT *
            FROM `ld_categories`
            WHERE `deleted` = 'no'
            AND `ld_categories`.`id` NOT IN (SELECT `category_id` FROM `ld_access_categories` WHERE `user_id` = '$uid')
            ORDER BY `sort` ASC
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getCategoryUsers($vid)
    {
        $sql = "
            SELECT `ld_access_categories`.*, `users`.`id` as `user_id`
            FROM `ld_access_categories`
            LEFT JOIN `users` ON `users`.`id` = `ld_access_categories`.`user_id`
            WHERE `ld_access_categories`.`category_id` = '$vid'
        ";

        return self::fetchAll(self::query($sql));
    }


    public static function getBiggestSort()
    {
        $sql = "
            SELECT *
            FROM `ld_categories`
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
            FROM `ld_categories`
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
            FROM `ld_categories`
            WHERE `deleted` = 'no' AND `sort` > 0 AND `sort` > '$sort'
            ORDER BY `sort` ASC
            LIMIT 1
        ";

//        print_data($sql);

        return self::fetch(self::query($sql));
    }
}

/* End of file */
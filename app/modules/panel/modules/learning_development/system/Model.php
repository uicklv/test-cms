<?php
class Learning_developmentModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `learning_development` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(200) NOT NULL,
                `image` varchar(60) DEFAULT NULL,
                `video` varchar(60) DEFAULT NULL,
                `file`  varchar(60) DEFAULT NULL,
                `video_poster` varchar(60) DEFAULT NULL,
                `sort` int(10) DEFAULT 0,
                `category` int(10) unsigned DEFAULT NULL,
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_keywords` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `slug` varchar(200) NOT NULL DEFAULT '',
                `posted` enum('no','yes') DEFAULT 'yes',
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                INDEX (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `ld_categories` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(200) NOT NULL,
                `image` varchar (100) DEFAULT NULL,
                `sort` int(10) DEFAULT 0,
                `deleted` enum('no','yes') DEFAULT 'no',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `ld_users` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL,
                `resource_id` int(10) unsigned NOT NULL,
                `time` varchar(20) DEFAULT '',
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `ld_access_resources` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL,
                `resource_id` int(10) unsigned NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `ld_access_categories` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id`  int(10) unsigned NOT NULL,
                `category_id` int(10) unsigned NOT NULL,
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
            FROM `learning_development`
            WHERE `id` = '$id'
            LIMIT 1
        ";

       $resource =  self::fetch(self::query($sql));

       if ($resource) {
               // Sectors
               $resource->users_ids = array();
               $resource->users = array();
               $users = self::getResourceUsers($resource->id);

               if (is_array($users) && count($users)) {
                   foreach ($users as $user) {
                       $resource->users_ids[] = $user->user_id;
                       $resource->users[] = $user;
                   }
               }
       }

       return $resource;
    }

    public static function getResourceUsers($vid)
    {
        $sql = "
            SELECT `ld_access_resources`.*, `users`.`id` as `user_id`
            FROM `ld_access_resources`
            LEFT JOIN `users` ON `users`.`id` = `ld_access_resources`.`user_id`
            WHERE `ld_access_resources`.`resource_id` = '$vid'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function removeUsers($rid)
    {
        $sql = "
            DELETE 
            FROM `ld_access_resources` 
            WHERE `resource_id` = '$rid'
        ";

        return self::query($sql);
    }

    public static function getByCategory($category_id)
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `category` = '$category_id'
            ORDER BY `id` DESC
        ";

        $resources = self::fetchAll(self::query($sql));

        if (is_array($resources) && count($resources) > 0) {
            Model::import('panel/learning_development/categories');
            foreach ($resources as $item) {
                $item->category_name = CategoriesModel::get($item->category);
            }
        }

        return $resources;
    }

    public static function getBiggestSort()
    {
        $sql = "
            SELECT *
            FROM `learning_development`
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
            FROM `learning_development`
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
            FROM `learning_development`
            WHERE `deleted` = 'no' AND `sort` > 0 AND `sort` > '$sort'
            ORDER BY `sort` ASC
            LIMIT 1
        ";

//        print_data($sql);

        return self::fetch(self::query($sql));
    }

    public static function getBySlug($slug)
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `slug` = '$slug'
            LIMIT 1
        ";

        $resource = self::fetch(self::query($sql));

        if ($resource) {
            Model::import('panel/learning_development/categories');
            $resource->category_name = CategoriesModel::get($resource->category);
        }

        return $resource;
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll()
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `deleted` = 'no'
        ";

        $resources = self::fetchAll(self::query($sql));

        if (is_array($resources) && count($resources) > 0) {
            Model::import('panel/learning_development/categories');
            foreach ($resources as $item) {
                $item->category_name = CategoriesModel::get($item->category);
            }
        }

        return $resources;
    }

    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `deleted` = 'yes'
        ";

        return  self::fetchAll(self::query($sql));
    }
}

/* End of file */
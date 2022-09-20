<?php
class Learning_developmentModel extends Model
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

    /**
     * @param $slug
     * @return array|object|null
     */
    public static function getBySlug($slug)
    {
        $sql = "
            SELECT *, (SELECT `id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "') as completed
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

    public static function get($id)
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getByCategory($category_id)
    {
        $sql = "
            SELECT *, (SELECT `id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "') as completed
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `category` = '$category_id'
            ORDER BY `sort` ASC
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

    public static function getByCategoryWithAccess($category_id, $uid)
    {
        $sql = "
            SELECT *, (SELECT `id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "') as completed
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `category` = '$category_id'
            AND `learning_development`.`id` NOT IN (SELECT `resource_id` FROM `ld_access_resources` WHERE `user_id` = '$uid')
            ORDER BY `sort` ASC
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

    public static function getCompleted($category_id)
    {
        $sql = "
            SELECT *, (SELECT `id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "') as completed
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `category` = '$category_id'
            AND `id` IN (SELECT `resource_id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "')
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

    public static function getCompletedWithAccess($category_id, $uid)
    {
        $sql = "
            SELECT *, (SELECT `id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "') as completed
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `category` = '$category_id'
            AND `id` IN (SELECT `resource_id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "')
            AND `learning_development`.`id` NOT IN (SELECT `resource_id` FROM `ld_access_resources` WHERE `user_id` = '$uid')
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

    public static function getIncomplete($category_id)
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `category` = '$category_id'
            AND `id` NOT IN (SELECT `resource_id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "')
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

    public static function getIncompleteWithAccess($category_id, $uid)
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
            AND `category` = '$category_id'
            AND `id` NOT IN (SELECT `resource_id` FROM `ld_users` WHERE `resource_id` = `learning_development`.`id` 
            AND `user_id` = '" . User::get('id') . "')
            AND `learning_development`.`id` NOT IN (SELECT `resource_id` FROM `ld_access_resources` WHERE `user_id` = '$uid')
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

    public static function getPrevBlogByCategory($id, $category)
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `id` < '$id' AND `deleted` = 'no' AND `posted` = 'yes'
            AND `category` = '$category'
            ORDER BY `id` DESC
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getNextBlogByCategory($id, $category)
    {
        $sql = "
            SELECT *
            FROM `learning_development`
            WHERE `id` > '$id' AND `deleted` = 'no' AND `posted` = 'yes'
            AND `category` = '$category'
            ORDER BY `id` ASC
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getPrevBlog($id)
    {
        $sql = "
            SELECT *
            FROM `blog`
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
            FROM `blog`
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
            FROM `blog`
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

    public static function getWhereSector($sector = false, $limit = false)
    {
        $sql = "
            SELECT `blog`.*,`blog`.`content`, `blog_categories`.`name` as sector_name, `blog_categories`.`id` as sector_id
            FROM `blog`
            LEFT JOIN `blog_categories` ON `blog`.`sector` = `blog_categories`.`id`
            WHERE `blog`.`deleted` = 'no' AND `blog`.`posted` = 'yes'";

        if ($sector)
            $sql .= " AND `blog_categories`.`name` = '$sector'";

        $sql .= " ORDER BY `blog`.`id` DESC";

        if($limit)
            $sql .= " LIMIT $limit";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
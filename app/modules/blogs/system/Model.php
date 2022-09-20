<?php
class BlogsModel extends Model
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
            SELECT *
            FROM `blog`
            WHERE `slug` = '$slug' AND `deleted` = 'no'
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
}

/* End of file */
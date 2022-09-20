<?php
class TestimonialsModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `microsites_testimonials` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `microsite_id` int(11) NOT NULL,
                `name` varchar(200) NOT NULL,
                `video` varchar(200) NOT NULL,
                `image` varchar(60) DEFAULT NULL,
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
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
            FROM `microsites_testimonials`
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
            FROM `microsites_testimonials`
            WHERE `deleted` = 'no'
        ";

        if ($microsite_id !== false)
            $sql .= " AND `microsite_id` = '$microsite_id'";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
<?php
class OfficesModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `offices` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `coordinates` varchar(100) DEFAULT NULL,
                `address` varchar(255) DEFAULT NULL,
                `day_1` varchar(255) DEFAULT NULL,
                `day_2` varchar(255) DEFAULT NULL,
                `time_1` varchar(255) DEFAULT NULL,
                `time_2` varchar(255) DEFAULT NULL,
                `postcode` varchar(255) DEFAULT NULL,
                `tel` varchar(255) DEFAULT NULL,
                `image` varchar(60) DEFAULT NULL,
                `image_main` varchar(60) DEFAULT NULL,
                `email` varchar(200) DEFAULT NULL,
                `apply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `slug` varchar(100) DEFAULT NULL,
                `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `posted` varchar(20) DEFAULT 'yes',
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(10) unsigned NOT NULL,
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
            FROM `offices`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getBySlug($slug)
    {
        $sql = "
            SELECT *
            FROM `offices`
            WHERE `slug` = '$slug'
            AND `deleted` = 'no'
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
            FROM `offices`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
        ";

        if ($microsite_id !== false)
            $sql .= " AND `microsite_id` = '$microsite_id'";

        return self::fetchAll(self::query($sql));
    }

    public static function getNotThis($slug, $id)
    {
        $sql = "
            SELECT *
            FROM `offices`
            WHERE `slug` = '$slug'
            AND `id` != '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function search($name = false)
    {
        $sql = "
            SELECT *
            FROM `offices`
            WHERE `deleted` = 'no'
            AND `posted` = 'yes'
        ";

        if ($name !== false)
            $sql .= " AND `name` LIKE '%$name%'";

        $offices =  self::fetchAll(self::query($sql));

        if (post('postcode'))
            $postcode = self::getPostcode(post('postcode'));
        if (is_array($offices) && count($offices) > 0) {
            foreach ($offices as $vacancy) {
                // Postcode
                if ($postcode) {
                    $vacancy->distance = false;

                    if (post('postcode')) {
                        if (!$vacancy->postcode) $vacancy->distance = false;

                        $vacPostcode = self::getPostcode($vacancy->postcode);
                        if (!$vacPostcode) $vacancy->distance = false;

                        $distance = calculateTheDistance($postcode->latitude, $postcode->longitude, $vacPostcode->latitude, $vacPostcode->longitude);

                        $vacancy->distance = round($distance / 1609, 1); // go to miles
                    }

                }
            }
        }

        return $offices;
    }

    public static function getPostcode($code)
    {
        $sql = "
            SELECT * 
            FROM `postcodelatlng` 
            WHERE `postcode` = '$code' 
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getPostcodes($code)
    {
        $sql = "
            SELECT * 
            FROM `postcodelatlng` 
            WHERE `postcode` LIKE '$code%' 
            LIMIT 10
        ";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
<?php
class MicrositesModel extends Model
{
    public $version = 1; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `microsites` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(100) DEFAULT NULL DEFAULT '',
                `ref` varchar(50) DEFAULT NULL DEFAULT '',
                `website` varchar(150) NOT NULL DEFAULT '',
                `company_size` int(11) DEFAULT NULL,
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_keywords` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `logo_image` varchar(100) NOT NULL DEFAULT '',
                `header_image` varchar(100) NOT NULL DEFAULT '',
                `key_image` varchar(100) NOT NULL DEFAULT '',
                `overview_image` varchar(100) NOT NULL DEFAULT '',
                `opportunities_image` varchar(100) NOT NULL DEFAULT '',
                `og_image` varchar(100) NOT NULL DEFAULT '',
                `slug` varchar(100) NOT NULL DEFAULT '',
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `microsites_sectors` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `microsite_id` int(10) unsigned NOT NULL,
                `sector_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `microsites_locations` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `microsite_id` int(10) unsigned NOT NULL,
                `location_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `microsites_vacancies` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `microsite_id` int(10) unsigned NOT NULL,
                `vacancy_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `microsites_tag_sectors` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `microsite_id` int(10) unsigned NOT NULL,
                `sector_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `microsites_analytics` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `entity_id` int(10) unsigned DEFAULT 0,
                `user_id` int(10) unsigned DEFAULT 0,
                `ref` varchar(50) DEFAULT '',
                `referrer` varchar(200) DEFAULT NULL,
                `ip` varchar(200) DEFAULT NULL,
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`), 
                KEY (`entity_id`) 
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `microsites_referrers` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `microsite_id` int(10) unsigned DEFAULT 0,
                `title` varchar(200) DEFAULT NULL,
                `count` int(10) unsigned DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
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
            case '0':
                $queries[] = "ALTER TABLE `microsites` ADD COLUMN `views` int(10) unsigned DEFAULT 0 AFTER `company_size`";
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
            FROM `microsites`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $item = self::fetch(self::query($sql));

        if ($item) {
            // Tag Sectors
            $item->tag_sector_ids = array();
            $item->tag_sectors = array();
            $tag_sectors = self::getItemTagSectors($item->id);

            if (is_array($tag_sectors) && count($tag_sectors)) {
                foreach ($tag_sectors as $tag) {
                    $item->tag_sector_ids[] = $tag->sector_id;
                    $item->tag_sectors[] = $tag;
                }
            }

            // Sectors
            $item->sector_ids = array();
            $item->sectors = array();
            $sectors = self::getItemSectors($item->id);

            if (is_array($sectors) && count($sectors)) {
                foreach ($sectors as $sector) {
                    $item->sector_ids[] = $sector->sector_id;
                    $item->sectors[] = $sector;
                }
            }

            // Locations
            $item->location_ids = array();
            $item->locations = array();
            $locations = self::getItemLocations($item->id);

            if (is_array($locations) && count($locations)) {
                foreach ($locations as $location) {
                    $item->location_ids[] = $location->location_id;
                    $item->locations[] = $location;
                }
            }

            // Microsite vacancies
            $item->vacancy_ids = array();
            $item->vacancies = array();
            $vacancies = self::getItemVacancies($item->id);

            if (is_array($vacancies) && count($vacancies)) {
                foreach ($vacancies as $vacancy) {
                    $item->vacancy_ids[] = $vacancy->vacancy_id;
                    $item->vacancies[] = $vacancy;
                }
            }
        }

        return $item;
    }

    /**
     * Get all users
     * @return array
     */
    public static function getAll()
    {
        $sql = "
            SELECT *
            FROM `microsites`
            WHERE `deleted` = 'no'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `microsites`
            WHERE `deleted` = 'yes'
        ";

        return self::fetchAll(self::query($sql));
    }


    public static function getItemTagSectors($id)
    {
        $sql = "
            SELECT `microsites_tag_sectors`.*, `tag_sectors`.`name` as `sector_name`
            FROM `microsites_tag_sectors`
            LEFT JOIN `tag_sectors` ON `tag_sectors`.`id` = `microsites_tag_sectors`.`sector_id`
            WHERE `microsites_tag_sectors`.`microsite_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemSectors($id)
    {
        $sql = "
            SELECT `microsites_sectors`.*, `sectors`.`name` as `sector_name`
            FROM `microsites_sectors`
            LEFT JOIN `sectors` ON `sectors`.`id` = `microsites_sectors`.`sector_id`
            WHERE `microsites_sectors`.`microsite_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemLocations($id)
    {
        $sql = "
            SELECT `microsites_locations`.*, `locations`.`name` as `location_name`
            FROM `microsites_locations`
            LEFT JOIN `locations` ON `locations`.`id` = `microsites_locations`.`location_id`
            WHERE `microsites_locations`.`microsite_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemVacancies($id)
    {
        $sql = "
            SELECT `microsites_vacancies`.*, `vacancies`.`title` as `location_name`
            FROM `microsites_vacancies`
            LEFT JOIN `vacancies` ON `vacancies`.`id` = `microsites_vacancies`.`vacancy_id`
            WHERE `microsites_vacancies`.`microsite_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function countUsers($where = false)
    {
        $sql = "
            SELECT COUNT(`id`)
            FROM `microsites`
        ";

        if ($where)
            $sql .= "WHERE ".$where;

        return self::fetch(self::query($sql), 'row')[0];
    }

    public static function getViewsByDays($id, $days = 9)
    {
        $sql = "
            SELECT *
            FROM `microsites_analytics`
            WHERE `entity_id` = '$id' AND `time` > " . (time() - $days * 24 * 3600) . " 
            ORDER BY `time` DESC
            LIMIT 500
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getViews($id)
    {
        $sql = "
            SELECT *
            FROM `microsites_analytics`
            WHERE `entity_id` = '$id' AND (`referrer` != '' OR `ref` != '')
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getReferrersList($id)
    {
        $sql = "
            SELECT *
            FROM `microsites_referrers`
            WHERE `microsite_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getReferrer($id)
    {
        $sql = "
            SELECT *
            FROM `microsites_referrers`
            WHERE `id` = '$id'
        ";

        return self::fetch(self::query($sql));
    }
}

/* End of file */

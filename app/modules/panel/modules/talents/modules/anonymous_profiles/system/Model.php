<?php
class Anonymous_profilesModel extends Model
{
    public $version = 1; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `talent_anonymous_profiles` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `job_title` varchar(100) NOT NULL,
              `ref` varchar(100) DEFAULT NULL,
              `quote` text DEFAULT NULL,
              `postcode` varchar(100) DEFAULT NULL,
              `radius` float DEFAULT NULL,
              `distance_type` varchar(50) DEFAULT NULL,
              `relocate` varchar(10) DEFAULT NULL,
              `contract` enum('permanent','contract','both') NOT NULL DEFAULT 'permanent',
              `availability` varchar(100) DEFAULT NULL,
              `currency` varchar(50) DEFAULT NULL,
              `min_annual_salary` float DEFAULT NULL,
              `min_daily_salary` float DEFAULT NULL,
              `annual_currency` varchar(50) DEFAULT NULL,
              `min_hourly_salary` float DEFAULT NULL,
              `hourly_currency` varchar(50) DEFAULT NULL,
              `daily_currency` varchar(50) DEFAULT NULL,
              `skills` text DEFAULT NULL,
              `consultant_id` int(11) NOT NULL,
              `keywords` varchar(200) DEFAULT NULL,
              `education` varchar(200) DEFAULT NULL,
              `video_link` varchar(100) DEFAULT NULL,
              `video_file` varchar(100) DEFAULT NULL,
              `video_type` varchar(100) DEFAULT NULL,
              `created_time` int(11) NOT NULL,
              `published_time` int(11) DEFAULT NULL,
              `deleted` enum('no','yes') DEFAULT 'no',
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `talent_anonymous_profiles_languages` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `profile_id` int(11) NOT NULL,
              `language_id` int(11) NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `talent_anonymous_profiles_locations` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `profile_id` int(11) NOT NULL,
              `location_id` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `talent_anonymous_profiles_skills` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `profile_id` int(11) NOT NULL,
              `skill_id` int(11) NOT NULL,
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
                $queries[] = " ALTER TABLE `anonymous_profiles` ADD FULLTEXT KEY `search` (`availability`,`education`,`postcode`,`quote`,`ref`,`job_title`,`skills`);";
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
            FROM `talent_anonymous_profiles`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $profile =  self::fetch(self::query($sql));

        if ($profile) {

            //Languages
            $profile->languages = [];
            $languages = self::getLanguages($profile->id);
            if (is_array($languages) && count($languages) > 0) {
                foreach ($languages as $lang) {
                    $profile->languages[] = $lang;
                }
            }

            //locations
            $profile->locations = [];
            $locations = self::getLocations($profile->id);
            if (is_array($locations) && count($locations) > 0) {
                foreach ($locations as $loc) {
                    $profile->locations[] = $loc;
                }
            }

            //consultant
            $consultant = self::getConsultant($profile->consultant_id);
            if ($consultant)
                $profile->consultant = $consultant;

            $profile->hotlist_ids = [];
            $profile->hotlists = [];
            $hotlists = self::getHotlists($profile->id);

            if (is_array($hotlists) && count($hotlists) > 0) {
                foreach ($hotlists as $an) {
                    $profile->hotlist_ids[] = $an->list_id;
                    $profile->hotlists[] = $an;
                }
            }

        }

        return $profile;
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll()
    {
        $sql = "
            SELECT *
            FROM `talent_anonymous_profiles`
            WHERE `deleted` = 'no'
        ";

        $profiles =  self::fetchAll(self::query($sql));

        if (is_array($profiles) && count($profiles) > 0) {
            foreach ($profiles as $item) {

                //Languages
                $item->languages = [];
                $languages = self::getLanguages($item->id);
                if (is_array($languages) && count($languages) > 0) {
                    foreach ($languages as $lang) {
                        $item->languages[] = $lang;
                    }
                }

                //Skills
                $item->skills = [];
                $skills = self::getSkills($item->id);
                if (is_array($skills) && count($languages) > 0) {
                    foreach ($skills as $skill) {
                        $item->skills[] = $skill;
                    }
                }

                //locations
                $item->locations = [];
                $locations = self::getLocations($item->id);
                if (is_array($locations) && count($locations) > 0) {
                    foreach ($locations as $loc) {
                        $item->locations[] = $loc;
                    }
                }

                //consultant
                $consultant = self::getConsultant($item->consultant_id);
                if ($consultant)
                    $item->consultant = $consultant;


                //hotlists
                $item->hotlist_ids = [];
                $item->hotlists = [];
                $hotlists = self::getHotlists($item->id);

                if (is_array($hotlists) && count($hotlists) > 0) {
                    foreach ($hotlists as $an) {
                        $item->hotlist_ids[] = $an->list_id;
                        $item->hotlists[] = $an;
                    }
                }

            }
        }

        return $profiles;
    }


    public static function getHotlists($id)
    {
        $sql = "
            SELECT `talent_hotlists_anonymous_profiles`.*, `talent_hotlists`.`name` as `list_name`, `talent_hotlists`.`id` as `list_id`   
            FROM `talent_hotlists_anonymous_profiles`
            LEFT JOIN `talent_hotlists` ON `talent_hotlists`.`id` = `talent_hotlists_anonymous_profiles`.`hotlist_id`
            WHERE `talent_hotlists_anonymous_profiles`.`profile_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }


    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `talent_anonymous_profiles`
            WHERE `deleted` = 'yes'
        ";

        $profiles =  self::fetchAll(self::query($sql));

        if (is_array($profiles) && count($profiles) > 0) {
            foreach ($profiles as $item) {

                //Languages
                $item->languages = [];
                $languages = self::getLanguages($item->id);
                if (is_array($languages) && count($languages) > 0) {
                    foreach ($languages as $lang) {
                        $item->languages[] = $lang;
                    }
                }

                //Skills
                $item->skills = [];
                $skills = self::getSkills($item->id);
                if (is_array($skills) && count($languages) > 0) {
                    foreach ($skills as $skill) {
                        $item->skills[] = $skill;
                    }
                }

                //locations
                $item->locations = [];
                $locations = self::getLocations($item->id);
                if (is_array($locations) && count($locations) > 0) {
                    foreach ($locations as $loc) {
                        $item->locations[] = $loc;
                    }
                }

                //consultant
                $consultant = self::getConsultant($item->id);
                if ($consultant)
                    $item->consultant = $consultant;

            }
        }

        return $profiles;
    }


    public static function getLanguages($id)
    {

        $sql = "
            SELECT `talent_anonymous_profiles_languages`.*, `talent_languages`.`name` as `language_name`
            FROM `talent_anonymous_profiles_languages`
            LEFT JOIN `talent_languages` ON `talent_languages`.`id` = `talent_anonymous_profiles_languages`.`language_id`
            WHERE `talent_anonymous_profiles_languages`.`profile_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getSkills($id)
    {

        $sql = "
            SELECT `talent_anonymous_profiles_skills`.*, `talent_skills`.`name` as `skill_name`
            FROM `talent_anonymous_profiles_skills`
            LEFT JOIN `talent_skills` ON `talent_skills`.`id` = `talent_anonymous_profiles_skills`.`skill_id`
            WHERE `talent_anonymous_profiles_skills`.`profile_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getLocations($id)
    {

        $sql = "
            SELECT `talent_anonymous_profiles_locations`.*, `talent_locations`.`name` as `location_name`
            FROM `talent_anonymous_profiles_locations`
            LEFT JOIN `talent_locations` ON `talent_locations`.`id` = `talent_anonymous_profiles_locations`.`location_id`
            WHERE `talent_anonymous_profiles_locations`.`profile_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getConsultant($id)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `id` = '$id'
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

    public static function removeLocations($id)
    {
        $sql = "
            DELETE 
            FROM `talent_anonymous_profiles_locations` 
            WHERE `profile_id` = '$id'
        ";

        return self::query($sql);
    }

    public static function removeLanguages($id)
    {
        $sql = "
            DELETE 
            FROM `talent_anonymous_profiles_languages` 
            WHERE `profile_id` = '$id'
        ";

        return self::query($sql);
    }

    public static function removeHotlists($id)
    {
        $sql = "
            DELETE 
            FROM `talent_hotlists_anonymous_profiles` 
            WHERE `profile_id` = '$id'
        ";

        return self::query($sql);
    }

}

/* End of file */
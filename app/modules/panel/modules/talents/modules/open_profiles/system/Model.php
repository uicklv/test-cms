<?php
class Open_profilesModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `talent_open_profiles` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `candidate_name` varchar(100) NOT NULL,
              `job_title` varchar(100) DEFAULT NULL,
              `ref` varchar(100) DEFAULT NULL,
              `quote` text DEFAULT NULL,
              `postcode` varchar(100) DEFAULT NULL,
              `radius` float DEFAULT NULL,
              `distance_type` varchar(50) DEFAULT NULL,
              `relocate` varchar(255) DEFAULT NULL,
              `contract` enum('permanent','contract','both') DEFAULT 'permanent',
              `availability` varchar(100) DEFAULT NULL,
              `min_annual_salary` float DEFAULT NULL,
              `min_daily_salary` float DEFAULT NULL,
              `annual_currency` varchar(50) DEFAULT NULL,
              `daily_currency` varchar(50) DEFAULT NULL,
              `min_hourly_salary` float DEFAULT NULL,
              `hourly_currency` varchar(50) DEFAULT NULL,
              `resume_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `consultant_id` int(11) NOT NULL,
              `video_link` varchar(100) DEFAULT NULL,
              `video_file` varchar(100) DEFAULT NULL,
              `video_type` varchar(100) DEFAULT NULL,
              `keywords` varchar(200) DEFAULT NULL,
              `education` varchar(200) DEFAULT NULL,
              `cv` varchar(100) DEFAULT NULL,
              `deleted` enum('no','yes') DEFAULT 'no',
              `image` varchar(150) DEFAULT NULL,
              `time` int(11) NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `talent_open_profiles_languages` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `open_profile_id` int(11) NOT NULL,
              `language_id` int(11) NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `talent_open_profiles_skills` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `open_profile_id` int(11) NOT NULL,
              `skill_id` int(11) NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;",

            "CREATE TABLE IF NOT EXISTS `talent_open_profiles_locations` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `open_profile_id` int(11) NOT NULL,
              `location_id` int(11) NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

          "CREATE TABLE IF NOT EXISTS `talent_locations` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(200) NOT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
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
            FROM `talent_open_profiles`
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
            FROM `talent_open_profiles`
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
                $consultant = self::getConsultant($item->id);
                if ($consultant)
                    $item->consultant = $consultant;

            }
        }

        return $profiles;
    }


    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `talent_open_profiles`
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
            SELECT `talent_open_profiles_languages`.*, `talent_languages`.`name` as `language_name`
            FROM `talent_open_profiles_languages`
            LEFT JOIN `talent_languages` ON `talent_languages`.`id` = `talent_open_profiles_languages`.`language_id`
            WHERE `talent_open_profiles_languages`.`open_profile_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getSkills($id)
    {

        $sql = "
            SELECT `talent_open_profiles_skills`.*, `talent_skills`.`name` as `skill_name`
            FROM `talent_open_profiles_skills`
            LEFT JOIN `talent_skills` ON `talent_skills`.`id` = `talent_open_profiles_skills`.`skill_id`
            WHERE `talent_open_profiles_skills`.`open_profile_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getLocations($id)
    {

        $sql = "
            SELECT `talent_open_profiles_locations`.*, `talent_locations`.`name` as `location_name`
            FROM `talent_open_profiles_locations`
            LEFT JOIN `talent_locations` ON `talent_locations`.`id` = `talent_open_profiles_locations`.`location_id`
            WHERE `talent_open_profiles_locations`.`open_profile_id` = '$id'
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
            FROM `talent_open_profiles_locations` 
            WHERE `open_profile_id` = '$id'
        ";

        return self::query($sql);
    }

    public static function removeLanguages($id)
    {
        $sql = "
            DELETE 
            FROM `talent_open_profiles_languages` 
            WHERE `open_profile_id` = '$id'
        ";

        return self::query($sql);
    }

}

/* End of file */
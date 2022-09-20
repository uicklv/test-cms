<?php
class Anonymous_profileModel extends Model
{
    public $version = 0;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `talent_candidate_alerts` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(150) DEFAULT NULL,
              `company_name` varchar(150) DEFAULT NULL,
              `email` varchar(200) NOT NULL,
              `time` int(11) NOT NULL,
              `skills_keywords` varchar(255) DEFAULT NULL,
              `postcode` varchar(200) DEFAULT NULL,
              `max_salary` int(11) DEFAULT NULL,
              `salary_term` varchar(50) DEFAULT NULL,
              `area` varchar(255) DEFAULT NULL,
              `area_id` int(10) DEFAULT NULL, 
              `deleted` enum('no','yes') DEFAULT 'no',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `talent_requests` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `request_type` varchar(255) NOT NULL,
              `user_id` int(11) DEFAULT NULL,
              `profile_type` varchar(255) NOT NULL,
              `profile_id` int(11) NOT NULL,
              `name` varchar(150) NOT NULL,
              `company` varchar(150) NOT NULL,
              `email` varchar(200) NOT NULL,
              `tel` varchar(50) NOT NULL,
              `date` varchar(100) DEFAULT NULL,
              `ip` varchar(20) NOT NULL,
              `time` int(11) DEFAULT NULL,
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

    public static function get($slug)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `slug` = '$slug'
            LIMIT 1
        ";

        $vacancy = self::fetch(self::query($sql));

        if ($vacancy) {
            // Sectors
            $vacancy->sector_ids = array();
            $vacancy->sectors = array();
            $sectors = self::getVacancySectors($vacancy->id);

            if (is_array($sectors) && count($sectors)) {
                foreach ($sectors as $sector) {
                    $vacancy->sector_ids[] = $sector->sector_id;
                    $vacancy->sectors[] = $sector;
                }
            }

            // Locations
            $vacancy->location_ids = array();
            $vacancy->locations = array();
            $locations = self::getVacancyLocations($vacancy->id);

            if (is_array($locations) && count($locations)) {
                foreach ($locations as $location) {
                    $vacancy->location_ids[] = $location->location_id;
                    $vacancy->locations[] = $location;
                }
            }

            // Functions
            $vacancy->function_ids = array();
            $vacancy->functions = array();
            $functions = self::getVacancyFunctions($vacancy->id);

            if (is_array($functions) && count($functions)) {
                foreach ($functions as $function) {
                    $vacancy->function_ids[] = $function->function_id;
                    $vacancy->functions[] = $function;
                }
            }
        }

        return $vacancy;
    }


    public static function getFavorites($user_id)
    {
        $sql = "
           SELECT *,
            (SELECT `shortlist_jobs`.`id` FROM `shortlist_jobs` WHERE `vacancies`.`id` = `shortlist_jobs`.`job_id` AND `shortlist_jobs`.`user_id` = '" . User::get('id') . "') AS 'saved'
            FROM `vacancies`
            LEFT JOIN `shortlist_jobs` ON `vacancies`.`id` =  `shortlist_jobs`.`job_id`
            WHERE `vacancies`.`deleted` = 'no'
            AND `shortlist_jobs`.`user_id` = '$user_id' 
        ";


        $sql .= " ORDER BY `vacancies`.`id` DESC";

        $vacancies = self::fetchAll(self::query($sql));


        if (is_array($vacancies) && count($vacancies)) {
            foreach ($vacancies as $vacancy) {
                // Sectors
                $vacancy->sector_ids = array();
                $vacancy->sectors = array();
                $sectors = self::getVacancySectors($vacancy->id);

                if (is_array($sectors) && count($sectors)) {
                    foreach ($sectors as $sector) {
                        $vacancy->sector_ids[] = $sector->id;
                        $vacancy->sectors[] = $sector;
                    }
                }

                // Locations
                $vacancy->location_ids = array();
                $vacancy->locations = array();
                $locations = self::getVacancyLocations($vacancy->id);

                if (is_array($locations) && count($locations)) {
                    foreach ($locations as $location) {
                        $vacancy->location_ids[] = $location->location_id;
                        $vacancy->locations[] = $location;
                    }
                }

            }
        }

        return $vacancies;
    }


    public static function getNotThis($slug, $id)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `slug` = '$slug'
            AND `id` != '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getNotThisRef($ref, $id)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `ref` = '$ref'
            AND `id` != '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getByRef($ref)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `ref` = '$ref'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));

    }


    public static function get_coordinates_by_outcode($outcode)
    {
        $sql = "SELECT AVG(`latitude`) AS `latitude`, AVG(`longitude`) AS `longitude`
                FROM `postcodelatlng` 
                WHERE `outcode` = '" . $outcode . "'";

        return self::fetch(self::query($sql));
    }

    public static function get_radius_fix_by_outcode($outcode,  $center_latitude, $center_longitude)
    {
        $sql = "
        SELECT
            (
                (
                    (
                        acos(
                            sin(
                                (" . $center_latitude . "*pi()/180)
                            ) * sin(
                                (
                                    (
                                        SELECT `postcodelatlng`.`latitude` FROM `postcodelatlng` WHERE `postcodelatlng`.`postcode` = `postcodes1`.`postcode` LIMIT 1
                                    )*pi()/180
                                )
                            ) + cos(
                                (" . $center_latitude . "*pi()/180)
                            ) * cos(
                                (
                                    (
                                        SELECT `postcodelatlng`.`latitude` FROM `postcodelatlng` WHERE `postcodelatlng`.`postcode` = `postcodes1`.`postcode` LIMIT 1
                                    )*pi()/180
                                )
                            ) * cos(
                                (
                                    (" . $center_longitude . " - (
                                        SELECT `postcodelatlng`.`longitude` FROM `postcodelatlng` WHERE `postcodelatlng`.`postcode` = `postcodes1`.`postcode` LIMIT 1
                                    ))*pi()/180
                                )
                            )
                        )
                    )*180/pi()
                )*60*1.1515
            ) AS `fix`
        FROM
            `postcodelatlng` `postcodes1`
        WHERE
            `postcodes1`.`outcode` = '" . $outcode . "'
        ORDER BY
            `fix` DESC
        LIMIT
            1";

        return self::fetch(self::query($sql));

    }

    public static function search($keywords = false, $postcode = false, $language = false, $salary = false, $salary_term = false)
    {

        $boolean_filter = str_replace(array("AND ", "AND", "NOT ", "NOT", "OR", "&quot;"),
            array("+", "+", "-", "-", "", '"'), preg_replace('/\s+/', ' ', $keywords));

        $sql = "
            SELECT `talent_anonymous_profiles`.*, `users`.`job_title` as consultant_job_title
            FROM `talent_anonymous_profiles`
            LEFT JOIN `users` ON `users`.`id` = `talent_anonymous_profiles`.`consultant_id`
            WHERE `talent_anonymous_profiles`.`deleted` = 'no'
        ";

        if ($keywords) {
            $sql .= " AND ( MATCH( `talent_anonymous_profiles`.`availability`, `talent_anonymous_profiles`.`education`, 
            `talent_anonymous_profiles`.`postcode`, `talent_anonymous_profiles`.`quote`, `talent_anonymous_profiles`.`ref`, 
            `talent_anonymous_profiles`.`job_title`, `talent_anonymous_profiles`.`skills` ) AGAINST ( '" . $boolean_filter . "' IN BOOLEAN MODE ) 
            OR `talent_anonymous_profiles`.`keywords` LIKE '%" . strtolower($keywords) . "%'
            OR `talent_anonymous_profiles`.`contract` LIKE '%" . strtolower($keywords) . "%'
            OR `users`.`firstname` LIKE '%" . strtolower($keywords) . "%'
            OR `users`.`lastname` LIKE '%" . strtolower($keywords) . "%'
            OR (SELECT GROUP_CONCAT(`talent_locations`.`name` SEPARATOR ', ') 
            FROM `talent_locations` WHERE `talent_locations`.`id` 
            IN (SELECT `talent_anonymous_profiles_locations`.`location_id` FROM `talent_anonymous_profiles_locations` 
            WHERE `talent_anonymous_profiles_locations`.`profile_id` = `talent_anonymous_profiles`.`id`) ) LIKE '%" . strtolower($keywords) . "%')";
        }

        if ($language) {
            $sql .= " AND (`talent_anonymous_profiles`.`id` IN 
            (SELECT `talent_anonymous_profiles_languages`.`profile_id` FROM `talent_anonymous_profiles_languages` 
            WHERE `talent_anonymous_profiles_languages`.`profile_id` = `talent_anonymous_profiles`.`id` 
            AND `talent_anonymous_profiles_languages`.`language_id` = " . $language . "))";
        }

        if ($salary && $salary_term) { //todo check salary
            switch ($salary_term) {
                case 'annum':
                    $sql .= " AND `talent_anonymous_profiles`.`min_annual_salary` <= " . $salary . "";
                    break;
                case 'day':
                    $sql .= " AND `talent_anonymous_profiles`.`min_daily_salary` <= " . $salary . "";
                    break;
                case 'hour':
                    $sql .= " AND `talent_anonymous_profiles`.`min_hourly_salary` <= " . $salary . "";
                    break;

            }
        }

        $profiles =  self::fetchAll(self::query($sql));

        $postcode = false;
        if (post('postcode'))
            $postcode = self::getPostcode(post('postcode'));

        if (is_array($profiles) && count($profiles) > 0) {
            Model::import('panel/talents/anonymous_profiles');
            foreach ($profiles as $item) {

                //Languages
                $item->languages = [];
                $languages = Anonymous_profilesModel::getLanguages($item->id);
                if (is_array($languages) && count($languages) > 0) {
                    foreach ($languages as $lang) {
                        $item->languages[] = $lang;
                    }
                }

                //Skills
                $item->skills = [];
                $skills = Anonymous_profilesModel::getSkills($item->id);
                if (is_array($skills) && count($languages) > 0) {
                    foreach ($skills as $skill) {
                        $item->skills[] = $skill;
                    }
                }

                //locations
                $item->locations = [];
                $locations = Anonymous_profilesModel::getLocations($item->id);
                if (is_array($locations) && count($locations) > 0) {
                    foreach ($locations as $loc) {
                        $item->locations[] = $loc;
                    }
                }

                //consultant
                $consultant = Anonymous_profilesModel::getConsultant($item->consultant_id);
                if ($consultant)
                    $item->consultant = $consultant;


                //hotlists
                $item->hotlist_ids = [];
                $item->hotlists = [];
                $hotlists = Anonymous_profilesModel::getHotlists($item->id);

                if (is_array($hotlists) && count($hotlists) > 0) {
                    foreach ($hotlists as $an) {
                        $item->hotlist_ids[] = $an->list_id;
                        $item->hotlists[] = $an;
                    }
                }

                // Postcode
                if ($postcode) {
                    $item->distance = false;

                    if (post('radius') && post('postcode')) {
                        if (!$item->postcode) $item->distance = false;

                        $vacPostcode = self::getPostcode($item->postcode);
                        if (!$vacPostcode) $item->distance = false;

                        $distance = calculateTheDistance($postcode->latitude, $postcode->longitude, $vacPostcode->latitude, $vacPostcode->longitude);

                        $item->distance = round($distance / 1609, 1); // go to miles

                    }

                }

            }
        }

        return $profiles;
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

    public static function getVacancySectors($vid)
    {
        $sql = "
            SELECT `vacancies_sectors`.*, `sectors`.`name` as `sector_name`
            FROM `vacancies_sectors`
            LEFT JOIN `sectors` ON `sectors`.`id` = `vacancies_sectors`.`sector_id`
            WHERE `vacancies_sectors`.`vacancy_id` = '$vid'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getVacancyLocations($vid)
    {
        $sql = "
            SELECT `vacancies_locations`.*, `locations`.`name` as `location_name`
            FROM `vacancies_locations`
            LEFT JOIN `locations` ON `locations`.`id` = `vacancies_locations`.`location_id`
            WHERE `vacancies_locations`.`vacancy_id` = '$vid'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getVacancyFunctions($vid)
    {
        $sql = "
            SELECT `vacancies_functions`.*, `functions`.`name` as `function_name`
            FROM `vacancies_functions`
            LEFT JOIN `functions` ON `functions`.`id` = `vacancies_functions`.`function_id`
            WHERE `vacancies_functions`.`vacancy_id` = '$vid'
        ";

        return self::fetchAll(self::query($sql));
    }


    public static function getSectors()
    {
        $sql = "
            SELECT *
            FROM `sectors`
            WHERE `deleted` = 'no'
            ORDER BY `name` ASC
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getLocations()
    {
        $sql = "
            SELECT *
            FROM `locations`
            WHERE `deleted` = 'no'
            ORDER BY `name` ASC
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getAllWithVacancy($where = false)
    {
        $sql = "
            SELECT `vacancies_locations`.*, `locations`.`name`, `locations`.`id` as loc_id
            FROM `vacancies_locations`
            LEFT JOIN locations ON `locations`.`id` = `vacancies_locations`.`location_id`
            WHERE `locations`.`deleted` = 'no'
        ";

        if ($where != false)
            $sql .= "AND $where";

        $sql .= " GROUP BY  `vacancies_locations`.`location_id`";

        return self::fetchAll(self::query($sql));
    }

    public static function searchOld($keywords = false, $postcode = false, $language = false, $salary = false, $salary_term = false)
    {

        $boolean_filter = str_replace(array("AND ", "AND", "NOT ", "NOT", "OR", "&quot;"),
            array("+", "+", "-", "-", "", '"'), preg_replace('/\s+/', ' ', $keywords));

        $sql = "
            SELECT `talent_anonymous_profiles`.*, `users`.`job_title` as consultant_job_title
            FROM `talent_anonymous_profiles`
            LEFT JOIN `users` ON `users`.`id` = `talent_anonymous_profiles`.`consultant_id`
            WHERE `talent_anonymous_profiles`.`deleted` = 'no'
        ";

        if ($keywords) {
            $sql .= " AND ( MATCH( `talent_anonymous_profiles`.`availability`, `talent_anonymous_profiles`.`education`, 
            `talent_anonymous_profiles`.`postcode`, `talent_anonymous_profiles`.`quote`, `talent_anonymous_profiles`.`ref`, 
            `talent_anonymous_profiles`.`job_title`, `talent_anonymous_profiles`.`skills` ) AGAINST ( '" . $boolean_filter . "' IN BOOLEAN MODE ) 
            OR `talent_anonymous_profiles`.`keywords` LIKE '%" . strtolower($keywords) . "%'
            OR `talent_anonymous_profiles`.`contract` LIKE '%" . strtolower($keywords) . "%'
            OR `users`.`firstname` LIKE '%" . strtolower($keywords) . "%'
            OR `users`.`lastname` LIKE '%" . strtolower($keywords) . "%'
            OR (SELECT GROUP_CONCAT(`talent_locations`.`name` SEPARATOR ', ') 
            FROM `talent_locations` WHERE `talent_locations`.`id` 
            IN (SELECT `talent_anonymous_profiles_locations`.`location_id` FROM `talent_anonymous_profiles_locations` 
            WHERE `talent_anonymous_profiles_locations`.`profile_id` = `talent_anonymous_profiles`.`id`) ) LIKE '%" . strtolower($keywords) . "%')";
        }

        if ($postcode) { //todo check postcode
            if (str_replace(' (all)', '', $postcode) === $postcode) {

                $coordinates = Model::fetch(Model::select('postcodelatlng', " `postcode` = '$postcode'"));

                if ($coordinates) {
                    $latitude = $coordinates->latitude;
                    $longitude = $coordinates->longitude;

                    $sql .= " AND (`talent_anonymous_profiles`.`relocate` = 'yes' 
                OR (((acos(sin((" . $latitude . "*pi()/180)) * sin(((SELECT `postcodelatlng`.`latitude` FROM `postcodelatlng` 
                WHERE `postcodelatlng`.`postcode` = `talent_anonymous_profiles`.`postcode` LIMIT 1)*pi()/180))+cos((" . $latitude . "*pi()/180)) 
                * cos(((SELECT `postcodelatlng`.`latitude` FROM `postcodelatlng` 
                WHERE `postcodelatlng`.`postcode` = `talent_anonymous_profiles`.`postcode` LIMIT 1)*pi()/180)) 
                * cos(((" . $longitude . " - (SELECT `postcodelatlng`.`longitude` FROM `postcodelatlng` 
                WHERE `postcodelatlng`.`postcode` = `talent_anonymous_profiles`.`postcode` LIMIT 1))*pi()/180))))*180/pi())*60*1.1515)
                *(IF(`talent_anonymous_profiles`.`distance_type` = 'KM', 1.609344, 1)) <= `talent_anonymous_profiles`.`radius`)";
                }
            } else {

                $outcode = str_replace(' (all)', '', $postcode);
                $coordinates = self::get_coordinates_by_outcode($outcode);
                if ($coordinates) {
                    $latitude = $coordinates->latitude;
                    $longitude = $coordinates->longitude;

                    $fix = self::get_radius_fix_by_outcode($outcode, $latitude, $longitude);

                    $sql .= " AND (`talent_anonymous_profiles`.`relocate` = 'yes' 
                    OR (((acos(sin((" . $latitude . "*pi()/180)) * sin(((SELECT `postcodelatlng`.`latitude` 
                    FROM `postcodelatlng` WHERE `postcodelatlng`.`postcode` = `talent_anonymous_profiles`.`postcode` LIMIT 1)
                    *pi()/180))+cos((" . $latitude . "*pi()/180)) * cos(((SELECT `postcodelatlng`.`latitude` FROM `postcodelatlng` 
                    WHERE `postcodelatlng`.`postcode` = `talent_anonymous_profiles`.`postcode` LIMIT 1)*pi()/180)) 
                    * cos(((" . $longitude . " - (SELECT `postcodelatlng`.`longitude` FROM `postcodelatlng` 
                    WHERE `postcodelatlng`.`postcode` = `talent_anonymous_profiles`.`postcode` LIMIT 1))*pi()/180))))*180/pi())*60*1.1515)
                    *(IF(`talent_anonymous_profiles`.`distance_type` = 'KM', 1.609344, 1)) <= (`talent_anonymous_profiles`.`radius` " . ($fix ? " + " . $fix->fix : "") . "))";
                }
            }
        }

        if ($language) {
            $sql .= " AND (`talent_anonymous_profiles`.`id` IN 
            (SELECT `talent_anonymous_profiles_languages`.`profile_id` FROM `talent_anonymous_profiles_languages` 
            WHERE `talent_anonymous_profiles_languages`.`profile_id` = `talent_anonymous_profiles`.`id` 
            AND `talent_anonymous_profiles_languages`.`language_id` = " . $language . "))";
        }

        if ($salary && $salary_term) { //todo check salary
            switch ($salary_term) {
                case 'annum':
                    $sql .= " AND `talent_anonymous_profiles`.`min_annual_salary` <= " . $salary . "";
                    break;
                case 'day':
                    $sql .= " AND `talent_anonymous_profiles`.`min_daily_salary` <= " . $salary . "";
                    break;
                case 'hour':
                    $sql .= " AND `talent_anonymous_profiles`.`min_hourly_salary` <= " . $salary . "";
                    break;

            }
        }

        $profiles =  self::fetchAll(self::query($sql));

        if (is_array($profiles) && count($profiles) > 0) {
            Model::import('panel/talents/anonymous_profiles');
            foreach ($profiles as $item) {

                //Languages
                $item->languages = [];
                $languages = Anonymous_profilesModel::getLanguages($item->id);
                if (is_array($languages) && count($languages) > 0) {
                    foreach ($languages as $lang) {
                        $item->languages[] = $lang;
                    }
                }

                //Skills
                $item->skills = [];
                $skills = Anonymous_profilesModel::getSkills($item->id);
                if (is_array($skills) && count($languages) > 0) {
                    foreach ($skills as $skill) {
                        $item->skills[] = $skill;
                    }
                }

                //locations
                $item->locations = [];
                $locations = Anonymous_profilesModel::getLocations($item->id);
                if (is_array($locations) && count($locations) > 0) {
                    foreach ($locations as $loc) {
                        $item->locations[] = $loc;
                    }
                }

                //consultant
                $consultant = Anonymous_profilesModel::getConsultant($item->consultant_id);
                if ($consultant)
                    $item->consultant = $consultant;


                //hotlists
                $item->hotlist_ids = [];
                $item->hotlists = [];
                $hotlists = Anonymous_profilesModel::getHotlists($item->id);

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

    /**
     * Get user by id
     * @param $id
     * @return array|object|null
     */
    public static function getUser($id)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

}

/* End of file */
<?php
class JobsModel extends Model
{
    public $version = 0;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries[] = "CREATE TABLE IF NOT EXISTS `candidate_alerts` (
                   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                   `email` varchar(255)  DEFAULT NULL,
                   `keywords` varchar(255)  DEFAULT NULL,
                   `location` varchar(255)  DEFAULT NULL,
                   `type` varchar(255)  DEFAULT NULL,
                   `token` varchar(50) NOT NULL,
                   `sector` varchar(255)  DEFAULT NULL,
                   `time` varchar(20) DEFAULT '',
                   PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;";

        $queries[] = "CREATE TABLE IF NOT EXISTS `sent_alerts` (
                   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                   `email` varchar(255)  DEFAULT NULL,
                   `job_id` varchar(255)  DEFAULT NULL,
                   `time` varchar(20) DEFAULT '',
                   PRIMARY KEY (`id`),
                   INDEX (`email`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;";

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

//        switch ($version) {
//        }

        foreach ($queries as $query)
            self::query($query);
    }

    public static function get($slug)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `slug` = '$slug' AND `deleted` = 'no' AND `time_expire` > '" . (time()) . "'
            LIMIT 1
        ";

        $vacancy = self::fetch(self::query($sql));

        if ($vacancy) {
            $vacancy = self::relationship($vacancy, 'vacancies', 'locations');
            $vacancy = self::relationship($vacancy, 'vacancies', 'sectors');
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
        ";

        if ($id)
            $sql .= " AND `id` != '$id'";

        $sql .= " LIMIT 1";

        return self::fetch(self::query($sql));
    }

    public static function getNotThisAll($slug, $id)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `slug` = '$slug'
            AND `id` != '$id'
        ";

        return self::fetchAll(self::query($sql));
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

    public static function getNotThisAllRef($ref, $id)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `ref` = '$ref'
            AND `id` != '$id'
        ";

        return self::fetchAll(self::query($sql));
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

    public static function search($keywords = false, $type = false, $sector = false, $location = false, $limit = false, $where = false)
    {
        $sql = "
            SELECT *, (SELECT shortlist_jobs.id FROM shortlist_jobs WHERE vacancies.id = shortlist_jobs.job_id AND shortlist_jobs.user_id = '" . User::get('id') . "') AS 'saved'
            FROM `vacancies`
            WHERE `deleted` = 'no' AND `posted` = 'yes' AND `time_expire` > '" . time() . "'
        ";

        if ($where) {
            $sql .= " AND " . $where;
        }

        if ($keywords)
            $sql .= " AND (`title` LIKE '%$keywords%' OR `content` LIKE '%$keywords%' OR `ref` LIKE '%$keywords%')";

        if ($type) {
            if (is_array($type)) // by array of types
                $sql .= " AND `vacancies`.`contract_type` IN ('" . implode("','", $type) . "')";
            else // by once type
                $sql .= " AND `vacancies`.`contract_type` = '$type'";
        }

        // Search by sectors
//        if ($sector) {
//            if (is_array($sector)) // by array of ids
//                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_sectors` WHERE `sector_id` IN ('" . implode("','", $sector) . "')))";
//            else // by id
//                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_sectors` WHERE `sector_id` = '$sector'))";
//        }
//
//        // Search by location
//        if ($location) {
//            if (is_array($location)) // by array of ids
//                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_locations` WHERE `location_id` IN ('" . implode("','", $location) . "')))";
//            else // by id
//                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_locations` WHERE `location_id` = '$location'))";
//        }


        $sql .= " ORDER BY `time` DESC";

        if ($limit)
            $sql .= " LIMIT $limit";

        $vacancies = self::fetchAll(self::query($sql));
//old relationships
//        if (is_array($vacancies) && count($vacancies)) {
//            foreach ($vacancies as $vacancy) {
//                // Sectors
//                $vacancy->sector_ids = array();
//                $vacancy->sectors = array();
//                $sectors = self::getVacancySectors($vacancy->id);
//
//                if (is_array($sectors) && count($sectors)) {
//                    foreach ($sectors as $sector) {
//                        $vacancy->sector_ids[] = $sector->id;
//                        $vacancy->sectors[] = $sector;
//                    }
//                }
//
//                // Locations
//                $vacancy->location_ids = array();
//                $vacancy->locations = array();
//                $locations = self::getVacancyLocations($vacancy->id);
//
//                if (is_array($locations) && count($locations)) {
//                    foreach ($locations as $location) {
//                        $vacancy->location_ids[] = $location->location_id;
//                        $vacancy->locations[] = $location;
//                    }
//                }
//            }
//        }

        $vacancies = self::relationship($vacancies, 'vacancies', 'locations', ['id', 'name'], $location);
        $vacancies = self::relationship($vacancies, 'vacancies', 'sectors', ['id', 'name'], $sector);

        return $vacancies;
    }


    /**
     * Function for fetching vacancies for sectors pages
     * @param $sector
     * @param int $limit
     * @return array
     */
    public static function getForSectorPage($sector = [], int $limit = 25): array
    {
        $sql = "
            SELECT *, (SELECT shortlist_jobs.id FROM shortlist_jobs WHERE vacancies.id = shortlist_jobs.job_id AND shortlist_jobs.user_id = '" . User::get('id') . "') AS 'saved'
            FROM `vacancies`
            WHERE `deleted` = 'no' AND `posted` = 'yes' AND `time_expire` > '" . time() . "'
        ";


        $sql .= " ORDER BY `time` DESC";

        if ($limit)
            $sql .= " LIMIT $limit";

        $vacancies = self::fetchAll(self::query($sql));

        if ($vacancies && $sector)
            $vacancies = self::relationship($vacancies, 'vacancies', 'sectors', ['id', 'name'], $sector);

        return $vacancies;
    }

    public static function getExpiringVacancies()
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `time_expire` < '" . (time() + 3600*24*3) . "' AND `time_expire` > '" . time() . "' AND `time_expire` != 0  AND `deleted` = 'no'
        ";

        $sql .= " ORDER BY `time_expire` DESC";

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

    public static function getCandidateAlertByToken($token)
    {
        $sql = "
            SELECT *
            FROM `candidate_alerts`
            WHERE `token` = '$token' 
            LIMIT 1
        ";
        return self::fetch(self::query($sql));
    }

    public static function getCandidateAlertByEmail($email)
    {
        $sql = "
            SELECT *
            FROM `candidate_alerts`
            WHERE `email` = '$email'
            LIMIT 1
        ";
        return self::fetch(self::query($sql));
    }
}

/* End of file */
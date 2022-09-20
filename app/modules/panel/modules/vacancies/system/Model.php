<?php
class VacanciesModel extends Model
{
    public $version = 1; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `vacancies` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(200) NOT NULL,
                `ref` varchar(200) DEFAULT NULL,
                `contract_type` enum('permanent', 'temporary', 'contract') DEFAULT 'permanent',
                `salary_value` varchar(200) DEFAULT NULL,
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_keywords` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `package` varchar(255) NOT NULL DEFAULT '',
                `consultant_id` int(10) unsigned DEFAULT 0,
                `microsite_id` int(10) unsigned DEFAULT 0,
                `content_short` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `tech_stack` varchar(255) DEFAULT '',
                `image` varchar(100) DEFAULT NULL,
                `postcode` varchar(20) DEFAULT NULL,
                `candidates` varchar(255) DEFAULT NULL,
                `expire_alert` varchar(10) DEFAULT 'no',
                `expire_reason` varchar(255) DEFAULT '',
                `posted` enum('no','yes') DEFAULT 'yes',
                `deleted` enum('no','yes') DEFAULT 'no',
                `views` int(10) unsigned DEFAULT 0,
                `slug` varchar(200) NOT NULL DEFAULT '',
                `time_expire` int(10) unsigned NOT NULL,
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                INDEX (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `vacancies_sectors` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `vacancy_id` int(10) unsigned NOT NULL,
                `sector_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `vacancies_locations` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `vacancy_id` int(10) unsigned NOT NULL,
                `location_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `cv_library` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `vacancy_id` int(10) unsigned DEFAULT 0,
                `candidate_id` int(10) unsigned DEFAULT 0,
                `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `email` varchar(200) NOT NULL,
                `tel` varchar(30) default NULL,
                `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `linkedin` varchar(150) DEFAULT NULL,
                `job_spec` varchar(50) DEFAULT NULL,
                `cv` varchar(50) DEFAULT NULL,
                `status` varchar(100) DEFAULT '',
                `application_id` int(10) unsigned DEFAULT 0 COMMENT 'Bullhorn field',
                `bh_notes` varchar (255)  DEFAULT 'Integration access revoked' COMMENT 'Bullhorn field',
                `bh_candidate_id` int(10) unsigned DEFAULT 0 COMMENT 'Bullhorn field',
                `deleted` enum('no', 'yes') DEFAULT 'no',
                `time` int(10) unsigned,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

             "CREATE TABLE IF NOT EXISTS `shortlist_jobs` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL,
                `job_id` int(10) unsigned NOT NULL,
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `vacancies_candidates` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `vacancy_id` int(10) unsigned NOT NULL,
                `candidate_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

             "CREATE TABLE IF NOT EXISTS `vacancies_customers` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `vacancy_id` int(10) unsigned NOT NULL,
                `customer_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

             "CREATE TABLE IF NOT EXISTS `vacancies_customers_email` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `vacancy_id` int(10) unsigned NOT NULL,
                `customer_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `vacancies_analytics` (
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

             "CREATE TABLE IF NOT EXISTS `vacancies_referrers` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `vacancy_id` int(10) unsigned DEFAULT 0,
                `title` varchar(200) DEFAULT NULL,
                `count` int(10) unsigned DEFAULT 0,
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
            case '0':
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `internal` tinyint(2) unsigned NOT NULL DEFAULT 0 AFTER `postcode`;";
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `app_email` varchar(255) DEFAULT NULL AFTER `postcode`;";
        }

        foreach ($queries as $query)
            self::query($query);
    }


    public static function getFavorite($uid, $jid)
    {
        $sql = "
            SELECT *
            FROM `shortlist_jobs`
            WHERE `user_id` = '$uid'
            AND `job_id` = '$jid'
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * @param $id
     * @return array|object|null
     */
    public static function getApplication($id)
    {
        $sql = "
            SELECT *
            FROM `cv_library`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }


    /**
     * @param false $where
     * @return array
     */
    public static function getAppWhere($where = false)
    {
        $sql = "
            SELECT *
            FROM `cv_library`
            WHERE `deleted` = 'no'
        ";
        if ($where)
            $sql .= " AND $where";

        $sql .= "ORDER BY `time` DESC";

        return self::fetchAll(self::query($sql));
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
            FROM `vacancies`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $vacancy = self::fetch(self::query($sql));

        if ($vacancy) {
            $vacancy = self::relationship($vacancy, 'vacancies', 'locations');
            $vacancy = self::relationship($vacancy, 'vacancies', 'sectors');

            // Customers
            $vacancy->customer_ids = array();
            $vacancy->customers = array();
            $customers = self::getVacancyCustomers($vacancy->id);

            if (is_array($customers) && count($customers)) {
                foreach ($customers as $customer) {
                    $vacancy->customer_ids[] = $customer->customer_id;
                    $vacancy->customers[] = $customer;
                }
            }
        }

        return $vacancy;
    }

    public static function getVacancyCustomers($vid)
    {
        $sql = "
            SELECT `vacancies_customers`.*, `users`.`firstname`, `users`.`lastname` , `users`.`id` as `customer_id`, `users`.`email`
            FROM `vacancies_customers`
            LEFT JOIN `users` ON `users`.`id` = `vacancies_customers`.`customer_id`
            WHERE `vacancies_customers`.`vacancy_id` = '$vid'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getLocationWhere($lid, $sid = false, $cid= false, $sector_type = false)
    {
        $sql = "
            SELECT COUNT(DISTINCT vacancies.id) as counter FROM vacancies 
            LEFT JOIN vacancies_locations ON vacancies.id = vacancies_locations.vacancy_id 
            LEFT JOIN vacancies_sectors ON vacancies.id = vacancies_sectors.vacancy_id 
            LEFT JOIN sectors ON vacancies_sectors.sector_id = sectors.id
            WHERE `vacancies`.`deleted` = 'no'
            AND (`vacancies`.`time_expire` > '" . (time() - 180) . "' OR `vacancies`.`time_expire` = 0)
            AND vacancies_locations.location_id = '$lid'";

        if ($sector_type) {
            $sql .= "AND sectors.sector_type = '$sector_type'";
        }
        if ($sid){
            $sids = "'" . implode( "' ,'" , $sid) . "'";
            $sql .= "AND vacancies_sectors.sector_id IN ($sids)";
        }
        if ($cid){
            $cids = "'" . implode( "' ,'" , $cid) . "'";
            $sql .= "AND vacancies.contract_type IN ($cids)";
        }


        $jobs =  self::fetchAll(self::query($sql));
        $jobs[0]->location_id = $lid;
        return $jobs;
    }

    public static function getLocationsWhere($where = false)
    {
        $sql = "
            SELECT *,
            (SELECT COUNT(*) FROM vacancies_locations WHERE location_id = locations.id) AS total
            FROM `locations` 
            WHERE `deleted` = 'no'
        ";
        if ($where)
            $sql .= " AND $where";

        $sql .= " ORDER BY `name` ASC";

        return self::fetchAll(self::query($sql));
    }


    public static function getVacancyByCandidate($cid)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `deleted` = 'no'
            AND `candidates` LIKE '%|$cid|%'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function removeCustomers($vid)
    {
        $sql = "
            DELETE 
            FROM `vacancies_customers` 
            WHERE `vacancy_id` = '$vid'
        ";

        return self::query($sql);
    }

    public static function getTypeWhere($type, $sid = false, $lid= false, $sector_type = false)
    {
        $sql = "
            SELECT COUNT(DISTINCT vacancies.id) as counter FROM vacancies 
            LEFT JOIN vacancies_locations ON vacancies.id = vacancies_locations.vacancy_id 
            LEFT JOIN vacancies_sectors ON vacancies.id = vacancies_sectors.vacancy_id 
            LEFT JOIN sectors ON vacancies_sectors.sector_id = sectors.id
            WHERE `vacancies`.`deleted` = 'no'
            AND (`vacancies`.`time_expire` > '" . (time() - 180) . "' OR `vacancies`.`time_expire` = 0)
            AND vacancies.contract_type = '$type'";

        if ($sector_type) {
            $sql .= "AND sectors.sector_type = '$sector_type'";
        }
        if ($sid){
            $sids = "'" . implode( "' ,'" , $sid) . "'";
            $sql .= "AND vacancies_sectors.sector_id IN ($sids)";
        }
        if ($lid){
            if (is_array($type)) { // by array of types
                $lids = "'" . implode("' ,'", $lid) . "'";
                $sql .= "AND vacancies_locations.location_id IN ($lids)";
            } else {
                $sql .= "AND vacancies_locations.location_id = '$lid'";
            }
        }

        $jobs =  self::fetchAll(self::query($sql));
        $jobs[0]->contract_type = $type;
        return $jobs;
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll($microsite_id = false, $limit = false, $where = false, $status = 'no')
    {
        $sql = "
           SELECT *,
            (SELECT COUNT(`id`) FROM `cv_library` cl WHERE cl.`vacancy_id` = v.`id`) as 'applications'
            FROM `vacancies` v
            WHERE v.`deleted` = '$status'
        ";

        if ($microsite_id !== false)
            $sql .= " AND `microsite_id` = '$microsite_id'";

        if ($where !== false)
            $sql .=  $where;

        $sql .= " ORDER BY `time` DESC, `id` DESC";

        if (is_numeric($limit))
            $sql .= " LIMIT $limit";

        $vacancies = self::fetchAll(self::query($sql));

        if ($vacancies) {
            $vacancies = self::relationship($vacancies, 'vacancies', 'locations');
            $vacancies = self::relationship($vacancies, 'vacancies', 'sectors');
            $vacancies = self::relationship($vacancies, 'vacancies', 'users', ['id', 'firstname', 'lastname']);
        }

        return $vacancies;
    }


    public static function getVacanciesCandidate($microsite_id = false, $limit = false, $where = false, $status = 'no')
    {
        $sql = "
           SELECT *, cl.`vacancy_id`, cl.`candidate_id`,
            (SELECT COUNT(`id`) FROM `cv_library` cl WHERE cl.`vacancy_id` = v.`id`) as 'applications'
            FROM `vacancies` v
            LEFT JOIN `cv_library` cl on cl.`vacancy_id` = v.`id`
            WHERE v.`deleted` = '$status' 
        ";

        if ($microsite_id !== false)
            $sql .= " AND `microsite_id` = '$microsite_id'";

        if ($where !== false)
            $sql .=  $where;

        $sql .= " ORDER BY v.`time` DESC, v.`id` DESC";

        if (is_numeric($limit))
            $sql .= " LIMIT $limit";

        $vacancies = self::fetchAll(self::query($sql));

        if (is_array($vacancies) && count($vacancies)) {
            foreach ($vacancies as $vacancy) {
                // Sectors
                $vacancy->sector_ids = array();
                $vacancy->sectors = array();
                $sectors = self::getVacancySectors($vacancy->vacancy_id);

                if (is_array($sectors) && count($sectors)) {
                    foreach ($sectors as $sector) {
                        $vacancy->sector_ids[] = $sector->id;
                        $vacancy->sectors[] = $sector;
                    }
                }

                // Locations
                $vacancy->location_ids = array();
                $vacancy->locations = array();
                $locations = self::getVacancyLocations($vacancy->vacancy_id);

                if (is_array($locations) && count($locations)) {
                    foreach ($locations as $location) {
                        $vacancy->location_ids[] = $location->location_id;
                        $vacancy->locations[] = $location;
                    }
                }

                // Consultant
                if ($vacancy->consultant_id)
                    $vacancy->consultant = self::getVacancyConsultant($vacancy->consultant_id);
            }
        }

        return $vacancies;
    }

    public static function getVacanciesForConsultant($consultant_id)
    {
        $sql = "
           SELECT *,
            (SELECT COUNT(`id`) FROM `cv_library` cl WHERE cl.`vacancy_id` = v.`id`) as 'applications'
            FROM `vacancies` v
            WHERE v.`deleted` = 'no' AND v.`consultant_id` = '$consultant_id'
            AND (`time_expire` > '" . (time() - 180) . "' OR `time_expire` = 0)
        ";

        $sql .= " ORDER BY `time` DESC, `id` DESC";

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

                // Consultant
                if ($vacancy->consultant_id)
                    $vacancy->consultant = self::getVacancyConsultant($vacancy->consultant_id);
            }
        }

        return $vacancies;
    }

    public static function getCandidatesForOffers($uid)
    {
        /*
                $sql = "
                    SELECT `vacancies_candidates`.*, `candidates_portal`.*, `candidates_portal`.`id` as cid, `candidates_portal`.`time` as ctime, `vacancies`.`title` as vacancy_title
                    FROM `vacancies_candidates`
                    LEFT JOIN `candidates_portal` ON `candidates_portal`.`id` = `vacancies_candidates`.`candidate_id`
                    LEFT JOIN `vacancies` ON `vacancies_candidates`.`vacancy_id` = `vacancies`.`id`
                    WHERE `vacancies_candidates`.`vacancy_id` IN ( SELECT id FROM vacancies WHERE id IN (SELECT `vacancy_id` FROM `vacancies_customers` WHERE `customer_id` = '" . User::get('id') . "'))
                    AND `employed` = 'on' AND `hide_offers` = 'off'
                    ORDER BY `ctime` DESC
                ";*/
        $sql = "
            SELECT * 
            FROM `candidates_portal` 
            WHERE `customer_offer` = '$uid'
            AND `hide_offers` = 'off' 
            ORDER BY `time` DESC
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getVacancyCandidates($vid, $where = false)
    {
        $sql = "
            SELECT `vacancies_candidates`.*, `candidates_portal`.*, `candidates_portal`.`id` as cid, 
            (SELECT `status` FROM `candidates_status` WHERE `candidate_id` = cid AND `vacancy_id` = '$vid' LIMIT 1) as c_status
            FROM `vacancies_candidates`
            LEFT JOIN `candidates_portal` ON `candidates_portal`.`id` = `vacancies_candidates`.`candidate_id`
            WHERE `vacancies_candidates`.`vacancy_id` = '$vid'
        ";

        if ($where !== false)
            $sql .= " AND $where";

        $sql .= " ORDER BY c_status";

        return self::fetchAll(self::query($sql));
    }

    public static function getVacancyCandidate($vacancy_id, $candidate_id)
    {
        $sql = "
            SELECT *
            FROM `vacancies_candidates`
            WHERE `vacancy_id` = '$vacancy_id'
            AND `candidate_id` = '$candidate_id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }


    public static function getLatest($limit = 6)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `deleted` = 'no'
            ORDER BY `time` DESC
            LIMIT $limit
        ";
        return self::fetchAll(self::query($sql));
    }

    public static function getSortedByField($field, $limit = 12)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `deleted` = 'no'
            ORDER BY `$field` DESC
            LIMIT $limit
        ";
        return self::fetchAll(self::query($sql));
    }

    public static function getVacanciesBySectorType($sector_type, $keywords = false, $type = false, $sector = false, $location= false, $orderBy = false, $limit = false)
    {
        $output = [];
        $sectors = self::getSectorsBySectorType($sector_type);

        foreach ($sectors as $item)
        {
            $vacancy = self::getVacanciesBySector($item->id, $keywords, $type, $sector, $location, $orderBy, $limit);
            if ($vacancy){
                foreach ($vacancy as $v) {
                    $v->sector_name = $item->name;
                    $output[$v->id] = $v;
                }
            }
        }

        return $output;
    }

    public static function getSectorsBySectorType($sector_type)
    {
        $sql = "
            SELECT * 
            FROM `sectors`
            WHERE `sector_type` = '$sector_type'
        ";

        return self::fetchAll(self::query($sql));
    }


    public static function getVacanciesBySector($sector_id, $keywords = false, $type = false, $sector = false, $location = false, $orderBy = false, $limit = false)
    {
        $sql = "
            SELECT `vacancies`.*
            FROM `vacancies`
            LEFT JOIN `vacancies_sectors` ON `vacancies`.id = `vacancies_sectors`.vacancy_id
            WHERE `vacancies_sectors`.sector_id = '$sector_id'
            AND `deleted` = 'no' AND (`time_expire` > '" . (time() - 180) . "' OR `time_expire` = 0)
        ";

        if ($keywords)
            $sql .= " AND (`vacancies`.`title` LIKE '%$keywords%' OR `vacancies`.`content` LIKE '%$keywords%' OR `vacancies`.`ref` LIKE '%$keywords%')";

        // Search by contract type
        if ($type) {
            if (is_array($type)) // by array of types
                $sql .= " AND `vacancies`.`contract_type` IN ('" . implode("','", $type) . "')";
            else // by once type
                $sql .= " AND `vacancies`.`contract_type` = '$type'";
        }

        // Search by sectors
        if ($sector) {
            if (is_array($sector)) // by array of ids
                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_sectors` WHERE `sector_id` IN ('" . implode("','", $sector) . "')))";
            else // by id
                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_sectors` WHERE `sector_id` = '$sector'))";
        }

        // Search by location
        if ($location) {
            if (is_array($location)) // by array of ids
                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_locations` WHERE `location_id` IN ('" . implode("','", $location) . "')))";
            else // by id
                $sql .= " AND (`vacancies`.`id` IN (SELECT `vacancy_id` FROM `vacancies_locations` WHERE `location_id` = '$location'))";
        }

        if ($orderBy = 'DESC')
            $sql .= " ORDER BY `vacancies`.`id` DESC";

        if ($limit)
            $sql .= " LIMIT $limit";

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

                // Consultant
                if ($vacancy->consultant_id)
                    $vacancy->consultant = self::getVacancyConsultant($vacancy->consultant_id);
            }
        }

        return $vacancies;
    }

    public static function getSectors()
    {
        $sql = "
            SELECT *,  (SELECT COUNT(*) FROM vacancies_sectors WHERE sector_id = sectors.id) AS total
            FROM `sectors`
            WHERE `deleted` = 'no'
            ORDER BY `name` ASC
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getSectorWhere($sid, $lid = false, $cid= false)
    {
        $sql = "
            SELECT COUNT(DISTINCT vacancies.id) as counter FROM vacancies 
            LEFT JOIN vacancies_sectors ON vacancies.id = vacancies_sectors.vacancy_id 
            LEFT JOIN vacancies_locations ON vacancies.id = vacancies_locations.vacancy_id 
            WHERE `deleted` = 'no' AND (`time_expire` > '" . (time() - 180) . "' OR `time_expire` = 0)
            AND vacancies_sectors.sector_id = '$sid'";
        if ($lid){
            $lids = "'" . implode( "' ,'" , $lid) . "'";
            $sql .= "AND vacancies_locations.location_id IN ($lids)";
        }
        if ($cid){
            $cids = "'" . implode( "' ,'" , $cid) . "'";
            $sql .= "AND vacancies.contract_type IN ($cids)";
        }

        $jobs =  self::fetchAll(self::query($sql));
        $jobs[0]->sector_id = $sid;

        return $jobs;

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

    public static function getVacancyConsultant($uid)
    {
        $sql = "
            SELECT * from `users` WHERE `id` = '$uid'
        ";

        return self::fetch(self::query($sql));
    }

    public static function removeSectors($vid)
    {
        $sql = "
            DELETE 
            FROM `vacancies_sectors` 
            WHERE `vacancy_id` = '$vid'
        ";

        return self::query($sql);
    }

    public static function removeLocations($vid)
    {
        $sql = "  
            DELETE 
            FROM `vacancies_locations` 
            WHERE `vacancy_id` = '$vid'
        ";

        return self::query($sql);
    }

    public static function getVacanciesByConsultant($consultant_id, $limit = null, $where = false)
    {
        $sql = "
            SELECT * from `vacancies` 
            WHERE `id` IN (SELECT `vacancy_id` FROM `vacancies_customers` WHERE `customer_id` = '$consultant_id')
            AND `deleted` = 'no'
        ";

        if ($where !== false)
            $sql .= "AND $where";

        $sql .= " ORDER BY `time` DESC, `id` DESC";

        if(is_numeric($limit))
            $sql .= " LIMIT $limit ";

        return self::fetchAll(self::query($sql));
    }

    public static function getVacanciesByCandidate($cid)
    {
        $sql = "
            SELECT * FROM `vacancies_candidates` 
            WHERE `candidate_id`  = '$cid'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getViewsByDays($id, $days = 9)
    {
        $sql = "
            SELECT *
            FROM `vacancies_analytics`
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
            FROM `vacancies_analytics`
            WHERE `entity_id` = '$id' AND (`referrer` != '' OR `ref` != '')
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getReferrersList($id)
    {
        $sql = "
            SELECT *
            FROM `vacancies_referrers`
            WHERE `vacancy_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getReferrer($id)
    {
        $sql = "
            SELECT *
            FROM `vacancies_referrers`
            WHERE `id` = '$id'
        ";

        return self::fetch(self::query($sql));
    }

    public static function search($where=false, $orderby=false, $sort=false, $start=false, $end=false)
    {
        $sql = " 
            SELECT *, (SELECT COUNT(`id`) FROM `cv_library` cl WHERE cl.`vacancy_id` = v.`id`) as 'applies'
            FROM `vacancies` v
            WHERE v.`deleted` = 'no' AND (v.`time_expire` > '" . (time() - 180) . "' OR v.`time_expire` = 0)
            $where";

        if ($orderby != 'sectors' && $orderby != 'locations') {
            if ($orderby && $sort) {
                $sql .= " ORDER BY $orderby $sort ";
            }
        }

        $vacancies = self::fetchAll(self::query($sql));

        if ($orderby == 'sectors' || $orderby == 'locations') {

            eval(sprintf('function cmpString($a, $b) {
               $a = strtolower($a->%s[0]->name);
               $b = strtolower($b->%s[0]->name);

                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            }', $orderby, $orderby));

            usort($vacancies, 'cmpString');
            if ($sort == 'asc')
                $vacancies = array_reverse($vacancies);
        }

        $vacancies = self::relationship($vacancies, 'vacancies', 'sectors');
        $vacancies = self::relationship($vacancies, 'vacancies', 'locations');

        if ($start !== false && $end !== false) {
            $vacancies = array_slice($vacancies, $start, $end);
        }

        return $vacancies;
    }
}

/* End of file */

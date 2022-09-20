<?php
class CrelateModel extends Model
{
    public $version = 1;

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
            case '0':
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `contract_length` varchar(150) DEFAULT NULL AFTER `contract_type`;";
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `client_email` varchar(150) DEFAULT NULL AFTER `postcode`;";
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `postcode` varchar(150) DEFAULT NULL AFTER `contract_length`;";
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `job_code` varchar(150) DEFAULT NULL AFTER `postcode`;";
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `job_id` varchar(150) DEFAULT NULL AFTER `job_code`;";

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

    public static function search($keywords = false, $type = false, $sector = false, $location = false, $orderBy = false, $limit = false)
    {
        $sql = "
            SELECT *
            FROM `vacancies`
            WHERE `deleted` = 'no'
        ";

        if ($keywords)
            $sql .= " AND (`title` LIKE '%$keywords%' OR `content` LIKE '%$keywords%' OR `ref` LIKE '%$keywords%')";

        // Search by contract type
        if ($type) {
            if (is_array($type)) // by array of types
                $sql .= " AND `contract_type` IN ('" . implode("','", $type) . "')";
            else // by once type
                $sql .= " AND `contract_type` = '$type'";
        }

        // Search by sectors
        if ($sector) {
            if (is_array($sector)) // by array of ids
                $sql .= " AND (`id` IN (SELECT `vacancy_id` FROM `vacancies_sectors` WHERE `sector_id` IN ('" . implode("','", $sector) . "')))";
            else // by id
                $sql .= " AND (`id` IN (SELECT `vacancy_id` FROM `vacancies_sectors` WHERE `sector_id` = '$sector'))";
        }

        // Search by location
        if ($location) {
            if (is_array($location)) // by array of ids
                $sql .= " AND (`id` IN (SELECT `vacancy_id` FROM `vacancies_locations` WHERE `location_id` IN ('" . implode("','", $location) . "')))";
            else // by id
                $sql .= " AND (`id` IN (SELECT `vacancy_id` FROM `vacancies_locations` WHERE `location_id` = '$location'))";
        }

        if ($orderBy = 'DESC')
            $sql .= " ORDER BY `id` DESC";

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
            SELECT *,
            (SELECT COUNT(*) FROM vacancies_sectors WHERE sector_id = sectors.id) AS total
            FROM `sectors` 
            WHERE `deleted` = 'no'
            ORDER BY `name` ASC
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getLocations()
    {
        $sql = "
            SELECT *,
            (SELECT COUNT(*) FROM vacancies_locations WHERE location_id = locations.id) AS total
            FROM `locations`
            WHERE `deleted` = 'no'
            ORDER BY `name` ASC
        ";

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

    /**
     * Get user by email
     * @param $email
     * @return array|object|null
     */
    public static function getUserByEmail($email)
    {
        $sql = "
            SELECT *
            FROM `users`
            WHERE `email` = '$email' 
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

}

/* End of file */
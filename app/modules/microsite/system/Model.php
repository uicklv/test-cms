<?php
class MicrositeModel extends Model
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
     * @param $ref
     * @return array|object|null
     */
    public static function getMicrosite($ref)
    {
        $sql = "
            SELECT *
            FROM `microsites`
            WHERE `ref` = '$ref' AND `deleted` = 'no'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
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

        if ($type)
            $sql .= " AND `contract_type` = '$type'";

        if ($sector)
            $sql .= " AND (`id` IN (SELECT `vacancy_id` FROM `vacancies_sectors` WHERE `sector_id` = '$sector'))";

        if ($location)
            $sql .= " AND (`id` IN (SELECT `vacancy_id` FROM `vacancies_locations` WHERE `location_id` = '$location'))";

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
}

/* End of file */

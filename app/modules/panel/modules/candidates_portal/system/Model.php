<?php
class Candidates_portalModel extends Model
{
    public $version = 0;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `candidates_portal` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `lastname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `tel` varchar(30) NOT NULL DEFAULT '',
                `email` varchar(200) DEFAULT NULL,
                `id_file` varchar(150) DEFAULT NULL,
                `passport` varchar(150) DEFAULT NULL,
                `address` varchar(500) DEFAULT NULL,
                `employed` varchar(20) DEFAULT 'off',
                `hide_offers` varchar(20) DEFAULT 'off',
                `dob` int(10) unsigned DEFAULT 0,
                `start_date` int(10) unsigned DEFAULT 0,
                `status` varchar(255) NOT NULL DEFAULT '1',
                `location` varchar(255) NOT NULL DEFAULT '',
                `notice_period` varchar(255) NOT NULL DEFAULT '',
                `salary` varchar(255) NOT NULL DEFAULT '',
                `cv` varchar(255) NOT NULL DEFAULT '',
                `customer_offer` varchar(20) DEFAULT 0,
                `hired_salary` varchar(255) DEFAULT NULL,
                `link1` varchar(255) DEFAULT NULL,
                `link2` varchar(255) DEFAULT NULL,
                `linkedin` varchar(150) NOT NULL DEFAULT '',
                `git_hub` varchar(255) NOT NULL DEFAULT '',
                `stack_overflow` varchar(255) NOT NULL DEFAULT '',
                `site` varchar(255) NOT NULL DEFAULT '',
                `slug` varchar(100) NOT NULL DEFAULT '',
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `candidates_status` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `candidate_id`  int(10) unsigned NOT NULL,
                `vacancy_id`int(10) unsigned NOT NULL,
                `status` varchar(20) DEFAULT '1',
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

    public static function removeVacanciesStatus($vid)
    {
        $sql = "
            DELETE 
            FROM `candidates_status` 
            WHERE `vacancy_id` = '$vid'
        ";

        return self::query($sql);
    }

    /**
     * Get user by $id
     * @param $id
     * @return array|object|null
     */
    public static function getCandidate($id)
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $candidate =  self::fetch(self::query($sql));

        if ($candidate){

            $candidate->vacancy_ids = array();
            $candidate->vacancies = array();
            $vacancies = self::getCandidateVacancies($candidate->id);

            if (is_array($vacancies) && count($vacancies)) {
                foreach ($vacancies as $vacancy) {
                    $candidate->vacancy_ids[] = $vacancy->vacancy_id;
                    $candidate->vacancies[] = $vacancy;
                }
            }
        }

        return $candidate;
    }

    public static function getCandidateVacancies($cid)
    {
        $sql = "
            SELECT `vacancies_candidates`.*, `vacancies`.`title` as `vacancy_name`, `vacancies`.`id` as `vacancy_id`
            FROM `vacancies_candidates`
            LEFT JOIN `vacancies` ON `vacancies`.`id` = `vacancies_candidates`.`vacancy_id`
            WHERE `vacancies_candidates`.`candidate_id` = '$cid'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getCandidateForOffers($id)
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `id` = '$id'
            AND `employed` = 'on'
            AND `hide_offers` = 'off'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get all users
     * @return array
     */

    public static function getCandidatesFromString($stringIds)
    {
        $idsArray = explode('||', trim($stringIds, '|'));

        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'no'
            AND `id` IN (" . implode(',', $idsArray) . ")
            AND `employed` = 'off'
            ORDER BY `status`
        ";


        return self::fetchAll(self::query($sql));
    }

    public static function search($candidate = false, $limit = false)
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'no'
        ";

        if ($candidate !== false)
            $sql .= " AND CONCAT_WS(' ', `firstname`, `lastname`) LIKE '%$candidate%'";

        $sql .= " ORDER BY `id`";

        if ($limit !== false)
            $sql .= " LIMIT $limit";

        return self::fetchAll(self::query($sql));
    }


    public static function getAllCandidates()
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'no'
        ";

        $candidates =  self::fetchAll(self::query($sql));

        Model::import('panel/vacancies');
        foreach ($candidates as $item){
            $item->job = VacanciesModel::getVacancyByCandidate($item->id);
        }

        return $candidates;
    }

    public static function getArchived()
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'yes'
        ";

        $candidates =  self::fetchAll(self::query($sql));

        Model::import('panel/vacancies');
        foreach ($candidates as $item){
            $item->job = VacanciesModel::getVacancyByCandidate($item->id);
        }

        return $candidates;
    }

    public static function getCandidatesWhere($where)
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'no'
            AND $where
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function countCandidates($where = false)
    {
        $sql = "
            SELECT COUNT(`id`)
            FROM `candidates_portal`
        ";

        if ($where)
            $sql .= "WHERE ".$where;

        return self::fetch(self::query($sql), 'row')[0];
    }

    public static function getBiggestSort()
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'no'
            ORDER BY `sort` DESC
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    public static function getNextSmallestSort($sort)
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'no' AND `sort` > 0 AND `sort` < '$sort'
            ORDER BY `sort` DESC
            LIMIT 1
        ";

//        print_data($sql);

        return self::fetch(self::query($sql));
    }

    public static function getNextBiggestSort($sort)
    {
        $sql = "
            SELECT *
            FROM `candidates_portal`
            WHERE `deleted` = 'no' AND `sort` > 0 AND `sort` > '$sort'
            ORDER BY `sort` ASC
            LIMIT 1
        ";


        return self::fetch(self::query($sql));
    }
}

/* End of file */
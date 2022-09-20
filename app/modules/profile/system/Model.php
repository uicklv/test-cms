<?php
class ProfileModel extends Model
{
    public $version = 2;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `candidates` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `email` varchar(200) NOT NULL,
                `password` varchar(60) DEFAULT '',
                `role` enum('unconfirmed','user') DEFAULT 'user',
                `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `lastname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `title` varchar(100) DEFAULT NULL DEFAULT '',
                `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `tel` varchar(30) NOT NULL DEFAULT '',
                `skype` varchar(100) NOT NULL DEFAULT '',
                `twitter` varchar(100) NOT NULL DEFAULT '',
                `linkedin` varchar(150) NOT NULL DEFAULT '',
                `image` varchar(100) NOT NULL DEFAULT '', 
                `job_title` varchar(150) DEFAULT NULL,
                `location` varchar(255) DEFAULT '',
                `employment_status` varchar(150) DEFAULT NULL,
                `interview_availability` varchar(150) DEFAULT NULL,
                `sectors` varchar(255) DEFAULT '',
                `locations` varchar(255) DEFAULT '',
                `cv` varchar(150) DEFAULT NULL,
                `token` varchar(100) DEFAULT NULL,
                `slug` varchar(100) NOT NULL DEFAULT '',
                `deleted` enum('no','yes') DEFAULT 'no',
                `reg_time` int(10) unsigned NOT NULL,
                `last_time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `text_experience`(
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `salary_increase`(
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `job_type`(
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `job_role`(
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `job_type_id` int(10) unsigned NOT NULL,
                `name` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `marketers` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` int(11) DEFAULT NULL,
              `full_name` varchar(100) NOT NULL COMMENT 'Full Name',
              `email_address` varchar(100) NOT NULL COMMENT 'Email Address',
              `current_company_name` varchar(100) DEFAULT NULL COMMENT 'Current Company Name',
              `industry_sector` tinyint(4) NOT NULL COMMENT 'Industry Sector',
              `job_type` tinyint(4) NOT NULL COMMENT 'Job Type',
              `job_role` int(11) NOT NULL COMMENT 'Job Role',
              `location` tinyint(4) NOT NULL COMMENT 'Location',
              `current_basic_salary` int(11) NOT NULL DEFAULT 0 COMMENT 'Current Basic Salary',
              `salary_increase` int(11) DEFAULT NULL COMMENT 'What Salary Increase Would You Move For?',
              `average_salary` int(11) DEFAULT NULL COMMENT 'average salary',
              `maximum_salary` int(11) DEFAULT NULL,
              `minimum_salary` int(11) DEFAULT NULL,
              `respondent_id` varchar(12) DEFAULT NULL,
              `collector_id` varchar(12) DEFAULT NULL,
              `start_date` datetime DEFAULT NULL,
              `end_date` datetime DEFAULT NULL,
              `format_work` tinyint(4) DEFAULT NULL,
              `job_role_other` varchar(255) DEFAULT NULL,
              `employment_contract` tinyint(4) DEFAULT NULL,
              `daily_rate_type` tinyint(4) DEFAULT NULL,
              `daily_rate_count` int(11) DEFAULT NULL,
              `current_salary_pro_rata` int(11) DEFAULT NULL,
              `satisfied_role` tinyint(4) DEFAULT NULL,
              `text_experience` tinyint(4) DEFAULT NULL,
              `record_id` int(11) DEFAULT NULL,
              `time` int(10) NOT NULL,
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
            case '0':
                $queries[] = "ALTER TABLE `candidates` ADD COLUMN `email_token` varchar(255) DEFAULT NULL AFTER `token`;";
                $queries[] = "ALTER TABLE `candidates` ADD COLUMN `email_confirm` tinyint DEFAULT 0 AFTER `email`;";
            case '1':
                $queries[] = "ALTER TABLE `candidates` ADD COLUMN `restore_token` varchar(255) DEFAULT NULL AFTER `token`;";
        }

        foreach ($queries as $query)
            self::query($query);
    }

    /**
     * Get user by id
     * @param $id
     * @return array|object|null
     */
    public static function getCandidate($id)
    {
        $sql = "
            SELECT *
            FROM `candidates`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get candidate by email
     * @param $email
     * @return array|object|null
     */

    public static function getCandidateByEmail($email)
    {
        $sql = "
            SELECT *
            FROM `candidates`
            WHERE `email` = '$email' 
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }


    public static function getMarketers($sector, $job_type, $job_role, $location, $text_experience)
    {
        $sql = "
                SELECT COUNT(`id`) as 'count'
                FROM `marketers` 
                WHERE `industry_sector` = '$sector' 
                AND `job_type` = '$job_type' AND `job_role` = '$job_role' AND `location` = '$location' 
                AND `text_experience` = '$text_experience' LIMIT 1
        ";

        return self::fetchAll(self::query($sql));
    }


    public static function getMaxMinAvgMarketers($sector, $job_type, $job_role, $location, $text_experience)
    {
        $sql = " SELECT 
    MAX(`current_basic_salary`) as basic_salary_max, 
    MIN(`current_basic_salary`) as basic_salary_min, 
    AVG(`current_basic_salary`) as basic_salary_avg 
    FROM `marketers` WHERE `industry_sector` = '$sector' AND `job_type` = '$job_type' 
                       AND `job_role` = '$job_role' AND `location` = '$location' AND `text_experience` = '$text_experience'";

        return self::fetchAll(self::query($sql));
    }


    /**
     * Update last visit time & etc.. in preDispatch
     * @param $id
     * @param $data
     * @return string
     */
    public static function updateCandidateByID($id, $data)
    {
        return self::update('candidates', $data, "`id` = '$id' LIMIT 1");
    }
}

/* End of file */
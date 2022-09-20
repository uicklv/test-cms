<?php
class FormsModel extends Model
{
    public $version = 2; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = [
            "CREATE TABLE IF NOT EXISTS `forms` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `sections_row` varchar(255) DEFAULT NULL,
                `temp_real_sec` varchar(500) DEFAULT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_sections` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `title_date` varchar(255) DEFAULT NULL,
                `fields_row` text DEFAULT NULL,
                `duplicate` tinyint DEFAULT 0,
                `fields_real_sec` varchar(500) DEFAULT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_fields_sort` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `form_id` int(10) NOT NULL,
                `section_id` int(10) NOT NULL,
                `fields_row` varchar(255) DEFAULT NULL,
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_fields_status` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `form_id` int(10) NOT NULL,
                `section_id` int(10) NOT NULL,
                `field_id` int(10) NOT NULL,
                `gray` varchar(10) DEFAULT 'no',
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_fields` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `title_date` varchar(255) DEFAULT NULL,
                `type` varchar(100) DEFAULT NULL,
                `answer_options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `duplicate` tinyint DEFAULT 0,
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_fields_images` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `field_id` int(10) unsigned NOT NULL,
                `image` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_progress` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `form_id` int(10) NOT NULL,
                `title` varchar(255) NOT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_fields_status` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `form_id` int(10) NOT NULL,
                `section_id` int(10) NOT NULL,
                `field_id` int(10) NOT NULL,
                `gray` varchar(10) DEFAULT 'no',
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `forms_answers` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `form_id` int(10) NOT NULL,
                `section_id` int(11) NOT NULL,
                `progress_id` int(11) NOT NULL,
                `field_id` int(10) DEFAULT NULL,
                `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
        ];

        foreach ($queries as $query)
            self::query($query);
    }

    /**
     * Method module_update start automatically if current $version != version in `modules` table, and start from "case 'i'", where i = prev version in modules` table
     * @param int $version
     */
    public function module_update($version)
    {
        $queries = [];

        switch ($version) {
            case '0':
                $queries[] = "ALTER TABLE `forms_fields_images` ADD COLUMN `form_id` int(10) DEFAULT 0 AFTER `id`;";
                $queries[] = "ALTER TABLE `forms_fields_images` ADD COLUMN `section_id` int(10) DEFAULT 0 AFTER `form_id`;";
            case '1':
                $queries[] = "CREATE TABLE IF NOT EXISTS `forms_answers_images` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `form_id` int(10) unsigned NOT NULL,
                `section_id` int(10) unsigned NOT NULL,
                `field_id` int(10) unsigned NOT NULL,
                `image` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;";
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
            FROM `forms`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll($where = false)
    {
        $sql = "
            SELECT *
            FROM `forms`
            WHERE `deleted` = 'no'
        ";

        if ($where !== false)
            $sql .= " AND $where";

        return self::fetchAll(self::query($sql));
    }

    public static function getAllAnswers($form_id)
    {
        $sql = "
            SELECT *, (SELECT `title` FROM `forms` WHERE `forms_progress`.`form_id` = `forms`.`id`) as 'form_name'
            FROM `forms_progress`
            WHERE `deleted` = 'no'
            AND `form_id` = '$form_id'
        ";

        return self::fetchAll(self::query($sql));
    }
}

/* End of file */
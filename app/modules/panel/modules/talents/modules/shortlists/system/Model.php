<?php
class ShortlistsModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `talent_shortlists` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `consultant_id` int(11) DEFAULT NULL,
              `name` varchar(255) DEFAULT NULL,
              `vacancy_title` varchar(255) DEFAULT NULL,
              `location` varchar(255) DEFAULT NULL,
              `description` text DEFAULT NULL,
              `deleted` enum('no','yes') DEFAULT 'no',
              `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `talent_shortlists_open_profiles` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `profile_id` int(11) NOT NULL,
              `shortlist_id` int(11) NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci;"
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
            FROM `talent_shortlists`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $list =  self::fetch(self::query($sql));

        if ($list) {

            //open profiles
            $list->opens_ids = [];
            $list->opens = [];
            $opens = self::getOpenProfiles($list->id);

            if (is_array($opens) && count($opens) > 0) {
                foreach ($opens as $open) {
                    $list->opens_ids[] = $open->profile_id;
                    $list->opens[] = $open;
                }
            }
        }

        return $list;
    }



    public static function getOpenProfiles($id)
    {
        $sql = "
            SELECT `talent_shortlists_open_profiles`.*, `talent_open_profiles`.`candidate_name` as `profile_name`, `talent_open_profiles`.`id` as `profile_id`    
            FROM `talent_shortlists_open_profiles`
            LEFT JOIN `talent_open_profiles` ON `talent_open_profiles`.`id` = `talent_shortlists_open_profiles`.`profile_id`
            WHERE `talent_shortlists_open_profiles`.`shortlist_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }


    /**
     * Get all
     * @return array
     */
    public static function getAll()
    {
        $sql = "
            SELECT *
            FROM `talent_shortlists`
            WHERE `deleted` = 'no'
        ";

        $lists =  self::fetchAll(self::query($sql));

        if (is_array($lists) && count($lists) > 0) {
            Model::import('panel/team');
            foreach ($lists as $item) {
                $item->consultant = TeamModel::getUser($item->consultant_id);
            }
        }

        return $lists;
    }

    public static function removeOpenProfiles($id)
    {
        $sql = "
            DELETE 
            FROM `talent_shortlists_open_profiles` 
            WHERE `shortlist_id` = '$id'
        ";

        return self::query($sql);
    }

}

/* End of file */
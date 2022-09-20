<?php
class LandingsModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `landings` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(100) DEFAULT NULL DEFAULT '',
                `ref` varchar(50) DEFAULT NULL DEFAULT '',
                `section_row` varchar(255) NOT NULL DEFAULT '',
                `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_keywords` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `og_image` varchar(100) NOT NULL DEFAULT '',
                `deleted` enum('no','yes') DEFAULT 'no',
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `landings_sections` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `landing_id` int(10) unsigned NOT NULL,
                `name` varchar(255) NOT NULL DEFAULT '',
                `options` varchar(255) NOT NULL DEFAULT '',
                `content1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `content2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `content3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `content4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `content5` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `content6` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `type` varchar(100) NOT NULL DEFAULT '',
                `deleted` enum('no','yes') DEFAULT 'no',
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

    /**
     * Get user by $id
     * @param $id
     * @return array|object|null
     */
    public static function get($id)
    {
        $sql = "
            SELECT *
            FROM `landings`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));

    }

    public static function getByUser($uid)
    {
        $sql = "
            SELECT *
            FROM `landings`
            WHERE `id` IN (SELECT `landing_id` FROM `landings_users` WHERE `user_id` = '$uid')
            LIMIT 1
        ";

        $item = self::fetch(self::query($sql));

        if ($item) {
            // Sectors
            $item->sector_ids = array();
            $item->sectors = array();
            $sectors = self::getItemSectors($item->id);

            if (is_array($sectors) && count($sectors)) {
                foreach ($sectors as $sector) {
                    $item->sector_ids[] = $sector->sector_id;
                    $item->sectors[] = $sector;
                }
            }

            // Specialisms
            $item->specialism_ids = array();
            $item->specialisms = array();
            $specialisms = self::getItemSpecialisms($item->id);

            if (is_array($specialisms) && count($specialisms)) {
                foreach ($specialisms as $specialism) {
                    $item->specialism_ids[] = $specialism->specialism_id;
                    $item->specialisms[] = $specialism;
                }
            }

            // Locations
            $item->location_ids = array();
            $item->locations = array();
            $locations = self::getItemLocations($item->id);

            if (is_array($locations) && count($locations)) {
                foreach ($locations as $location) {
                    $item->location_ids[] = $location->location_id;
                    $item->locations[] = $location;
                }
            }

            // Access
            $item->access_ids = array();
            $item->access = array();
            $access = self::getItemAcess($item->id);

            if (is_array($access) && count($access)) {
                foreach ($access as $user) {
                    $item->access_ids[] = $user->user_id;
                }
            }

            // Microsite vacancies
            $item->vacancy_ids = array();
            $item->vacancies = array();
            $vacancies = self::getItemVacancies($item->id);

            if (is_array($vacancies) && count($vacancies)) {
                foreach ($vacancies as $vacancy) {
                    $item->vacancy_ids[] = $vacancy->vacancy_id;
                    $item->vacancies[] = $vacancy;
                }
            }
        }

        return $item;
    }


    public static function getBySlug($slug)
    {
        $sql = "
            SELECT *
            FROM `landings`
            WHERE `ref` = '$slug' AND `deleted` = 'no'
            LIMIT 1
        ";

        $item = self::fetch(self::query($sql));

        if ($item) {
            //Landing sections
            $item->sections = [];
            $sections = self::getSections($item->id);

            if (is_array($sections) && count($sections)) {
                $item->sections = $sections;
            }
        }

        return $item;
    }

    /**
     * Get all users
     * @return array
     */
    public static function getAll()
    {
        $sql = "
            SELECT *
            FROM `landings`
            WHERE `deleted` = 'no'
            ORDER BY `title` ASC
        ";

       $microsites = self::fetchAll(self::query($sql));

       if (is_array($microsites) && count($microsites) > 0) {
           foreach ($microsites as $item){
               // Sectors
               $item->sector_ids = array();
               $item->sectors = array();
               $sectors = self::getItemSectors($item->id);

               if (is_array($sectors) && count($sectors)) {
                   foreach ($sectors as $sector) {
                       $item->sector_ids[] = $sector->sector_id;
                       $item->sectors[] = $sector;
                   }
               }

               // Locations
               $item->location_ids = array();
               $item->locations = array();
               $locations = self::getItemLocations($item->id);

               if (is_array($locations) && count($locations)) {
                   foreach ($locations as $location) {
                       $item->location_ids[] = $location->location_id;
                       $item->locations[] = $location;
                   }
               }

               // Testimonials
               $item->testimonials = array();
               $testimonials = self::getItemTestimonials($item->id);

               if (is_array($testimonials) && count($testimonials)) {
                   foreach ($testimonials as $testimonial) {
                       $item->testimonials[] = $testimonial;
                   }
               }

               // Office
               $item->office = self::getItemOffice($item->id);

               //Microsite sections
               $item->sections = array();
               $sections = self::getSections($item->id);

               if (is_array($sections) && count($sections)) {
                   $item->sections = $sections;
                   foreach ($sections as $section) {
                       if ($section->type == 'meet_the_team'){
                           if ($section->content1) {
                               $item->team = self::getItemTeam($section->content1);
                           }
                       }
                   }
               }
           }
       }

       return $microsites;
    }

    public static function getAllSimple()
    {
        $sql = "
            SELECT *
            FROM `landings`
            WHERE `deleted` = 'no'
            ORDER BY `title` ASC
        ";

        return self::fetchAll(self::query($sql));
    }



    public static function getMicrositesByUser($uid)
    {
        $sql = "
            SELECT *
            FROM `landings`
            WHERE `id` IN (SELECT `landing_id` FROM `microsites_access` WHERE `user_id` = '$uid')
        ";

        $microsites = self::fetchAll(self::query($sql));

        if (is_array($microsites) && count($microsites) > 0) {
            foreach ($microsites as $item){
                // Sectors
                $item->sector_ids = array();
                $item->sectors = array();
                $sectors = self::getItemSectors($item->id);

                if (is_array($sectors) && count($sectors)) {
                    foreach ($sectors as $sector) {
                        $item->sector_ids[] = $sector->sector_id;
                        $item->sectors[] = $sector;
                    }
                }

                // Locations
                $item->location_ids = array();
                $item->locations = array();
                $locations = self::getItemLocations($item->id);

                if (is_array($locations) && count($locations)) {
                    foreach ($locations as $location) {
                        $item->location_ids[] = $location->location_id;
                        $item->locations[] = $location;
                    }
                }

                // Testimonials
                $item->testimonials = array();
                $testimonials = self::getItemTestimonials($item->id);

                if (is_array($testimonials) && count($testimonials)) {
                    foreach ($testimonials as $testimonial) {
                        $item->testimonials[] = $testimonial;
                    }
                }

                // Office
                $item->office = self::getItemOffice($item->id);

                //Microsite sections
                $item->sections = array();
                $sections = self::getSections($item->id);

                if (is_array($sections) && count($sections)) {
                    $item->sections = $sections;
                    foreach ($sections as $section) {
                        if ($section->type == 'meet_the_team'){
                            $item->team = self::getItemTeam($section->content1);
                        }
                    }
                }
            }
        }

        return $microsites;
    }

    public static function getAccess($mid, $uid)
    {
        $sql = "
            SELECT *
            FROM `landings_access`
            WHERE `landing_id` = '$mid'
            AND `user_id` = '$uid'
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }


    public static function getItemTagSectors($id)
    {
        $sql = "
            SELECT `landings_tag_sectors`.*, `tag_sectors`.`name` as `sector_name`
            FROM `landings_tag_sectors`
            LEFT JOIN `tag_sectors` ON `tag_sectors`.`id` = `landings_tag_sectors`.`sector_id`
            WHERE `landings_tag_sectors`.`landing_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemSectors($id)
    {
        $sql = "
            SELECT `landings_sectors`.*, `sectors`.`name` as `sector_name`
            FROM `landings_sectors`
            LEFT JOIN `sectors` ON `sectors`.`id` = `landings_sectors`.`sector_id`
            WHERE `landings_sectors`.`landing_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemSpecialisms($id)
    {
        $sql = "
            SELECT `landings_specialisms`.*, `specialisms`.`name` as `specialism_name`
            FROM `landings_specialisms`
            LEFT JOIN `specialisms` ON `specialisms`.`id` = `landings_specialisms`.`specialism_id`
            WHERE `landings_specialisms`.`landing_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemTeam($ids)
    {
        $ids = explode('||', trim($ids, '|'));
        $teamids = implode(', ', $ids);
        $sql = "
            SELECT *
            FROM `users`
            WHERE `id` IN ($teamids)
            AND `deleted` = 'no';
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getSection($sid)
    {
        $sql = "
            SELECT *
            FROM `landings_sections`
            WHERE `id` = '$sid'
        ";

        return self::fetch(self::query($sql));
    }

    public static function getSections($landing_id, $mode = 'object')
    {
        $sql = "
            SELECT *
            FROM `landings_sections`
            WHERE `landing_id` = '$landing_id'
            AND `deleted` = 'no'
        ";

        return self::fetchAll(self::query($sql), $mode, 'id');
    }

    public static function getItemLocations($id)
    {
        $sql = "
            SELECT `landings_locations`.*, `locations`.`name` as `location_name`, `locations`.`position`, `locations`.`continent`
            FROM `landings_locations`
            LEFT JOIN `locations` ON `locations`.`id` = `landings_locations`.`location_id`
            WHERE `landings_locations`.`landing_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemAcess($id)
    {
        $sql = "
            SELECT `landings_access`.*
            FROM `landings_access`
            LEFT JOIN `users` ON `users`.`id` = `landings_access`.`user_id`
            WHERE `landings_access`.`landing_id` = '$id'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemTestimonials($id)
    {
        $sql = "
            SELECT *
            FROM `landings_testimonials`
            WHERE `landing_id` = '$id'
            AND `deleted` = 'no'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getItemOffice($id)
    {
        $sql = "
            SELECT *
            FROM `landings_offices`
            WHERE `landing_id` = '$id'
          LIMIT 1
        ";

        return self::fetch(self::query($sql));

    }

    public static function getItemVacancies($id)
    {
        $sql = "
            SELECT `landings_vacancies`.*, `vacancies`.`title` as `vacancy_name`, `vacancies`.`content_short`, 
            `vacancies`.`slug`, `vacancies`.`salary_value`, `vacancies`.`currency`
            FROM `landings_vacancies`
            LEFT JOIN `vacancies` ON `vacancies`.`id` = `landings_vacancies`.`vacancy_id`
            WHERE `landings_vacancies`.`landing_id` = '$id'
        ";

        $vacancies = self::fetchAll(self::query($sql));

        if (is_array($vacancies) && count($vacancies)) {
            Model::import('jobs');
            foreach ($vacancies as $vacancy) {
                // Locations
                $vacancy->location_ids = array();
                $vacancy->locations = array();
                $locations = JobsModel::getVacancyLocations($vacancy->id);

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

    public static function countUsers($where = false)
    {
        $sql = "
            SELECT COUNT(`id`)
            FROM `landings`
        ";

        if ($where)
            $sql .= "WHERE ".$where;

        return self::fetch(self::query($sql), 'row')[0];
    }
}

/* End of file */
<?php
class TalentsModel extends Model
{
    public $version = 0;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `talent_users` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `firstname` varchar(100) NOT NULL,
              `lastname` varchar(100) NOT NULL,
              `email` varchar(200) NOT NULL,
              `tel` varchar(30) DEFAULT NULL,
              `password` varchar(200) NOT NULL,
              `image` varchar(100) DEFAULT NULL,
              `linkedin` varchar(150) DEFAULT NULL,
              `deleted` enum('no','yes') DEFAULT 'no'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `talent_password_protection` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `areas`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `password` varchar (255) NOT NULL,
              `protection` tinyint(1) DEFAULT 0,
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
                $queries[] = "INSERT INTO `talent_users` (`id`, `firstname`, `lastname`, `email`, `password`, `deleted`) VALUES
                    (1,'Tom', 'Wilde' ,'tom@boldidentities.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'no');";
        }

        foreach ($queries as $query)
            self::query($query);
    }

    public static function getUserByEmail($email)
    {
        $sql = "
            SELECT *
            FROM `talent_users`
            WHERE `email` = '$email' 
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }

}

/* End of file */
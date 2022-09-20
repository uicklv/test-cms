<?php
class ZohoModel extends Model
{
    public $version = 1;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `zoho_integration` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `access_token` VARCHAR(200) NOT NULL ,
              `refresh_token` VARCHAR(200) NOT NULL ,
              `expires` INT NOT NULL ,
              `created` BIGINT NOT NULL ,
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
//            case '0':
        }

        foreach ($queries as $query)
            self::query($query);
    }

    public static function getToken()
    {
        $sql = "
            SELECT `refresh_token`
            FROM `zoho_integration` 
            LIMIT 1
        ";

        return self::fetch(self::query($sql));
    }


}

/* End of file */
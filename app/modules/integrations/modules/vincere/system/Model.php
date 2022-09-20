<?php
class VincereModel extends Model
{
    public $version = 1;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `vincere_integration` (
              `vincere_integration_id` int(11) NOT NULL AUTO_INCREMENT,
              `access_token` text NOT NULL,
              `refresh_token` text DEFAULT NULL,
              `id_token` text NOT NULL,
              `expires_in` bigint(20) NOT NULL,
              `time` bigint(20) NOT NULL,
              PRIMARY KEY (`vincere_integration_id`)
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
                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `expiry_reason` varchar(255) default NULL AFTER `time_expire`";
        }

        foreach ($queries as $query)
            self::query($query);
    }

    public static function get($params = array(), $orderBy = false, $orderDirection = false)
    {
        $sql = "
            SELECT *
            FROM `vincere_integration` 
        ";

        if ($params) {
            $tmpArr = [];
            foreach ($params as $column => $value)
                $tmpArr[] = "`$column` = '$value'";
            $sql .= " WHERE " . implode(' AND ', $tmpArr);
        }

        if ($orderBy && $orderDirection)
            $sql .= " ORDER BY `$orderBy` $orderDirection ";

        $sql .= " LIMIT 1";

        return self::fetch(self::query($sql));
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
}

/* End of file */
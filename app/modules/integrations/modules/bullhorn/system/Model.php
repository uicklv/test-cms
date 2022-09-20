<?php
class BullhornModel extends Model
{
    public $version = 1;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `bullhorn_integration` (
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
            case '0':
                $queries[] = "ALTER TABLE `bullhorn_integration` ADD COLUMN `note` varchar(255) default NULL AFTER `expires`";
        }

        foreach ($queries as $query)
            self::query($query);
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



    public static function get_row($params = array(), $orderBy = false, $orderDirection = false)
    {
        $sql = "
            SELECT *
            FROM `bullhorn_integration` 
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

    public static function get($params = array(), $orderBy = false, $orderDirection = false)
    {
        $sql = "
            SELECT *
            FROM `bullhorn_integration` 
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

    public static function getAll($table, $params = array(), $orderBy = false, $orderDirection = false, $start = false, $end = false)
    {
        $sql = "
            SELECT *
            FROM `$table` 
        ";

        if ($params) {
            $tmpArr = [];
            foreach ($params as $column => $value)
                $tmpArr[] = "`$column` = '$value'";
            $sql .= " WHERE " . implode(' AND ', $tmpArr);
        }

        if ($orderBy && $orderDirection)
            $sql .= " ORDER BY `$orderBy` $orderDirection ";

        if ($start !== false) {
            $sql .= " LIMIT $start";

            if ($end !== false)
                $sql .= ", $end";
        }

        return self::fetchAll(self::query($sql));
    }

    public static function update_row($table, $params = array(), $data = array())
    {
        $set = array();
        $sql = "UPDATE `$table` SET ";

        foreach ($data as $key => $value)
        {
            if ($value === 0)
                $set[] = "`$key` = '0'";
            else if ($value == '++')
                $set[] = "`$key` = `$key` +1";
            else if ($value == '--')
                $set[] = "`$key` = `$key` -1";
            else if (is_null($value))
                $set[] = "`$key` = NULL";
            else
                $set[] = "`$key` = '$value'";

            //else if (isset($value[0]) && $value[0] == '`')
            //$set[] = "`$key` = $value"; // $data['coins'] = '`coins` -15';
        }

        $sql .= implode(', ', $set);

        if ($params) {
            $tmpArr = [];
            foreach ($params as $column => $value)
                $tmpArr[] = "`$column` = '$value'";
            $sql .= " WHERE " . implode(' AND ', $tmpArr);
        }

        print_data($sql);
        $result = self::query($sql);
        preg_match('/^\D+(\d+)\D+(\d+)\D+(\d+)$/', self::$_db->info,$matches);

        if ($matches && $matches[1]) {
            return true;
        } else {
            return false;
        }

    }

//    public function get($params = array(), $per_page = NULL, $offset = NULL, $order_by = NULL, $order_direction = NULL)
//    {
//        if($per_page !== NULL && $offset !== NULL)
//            $this->db->limit($per_page, $offset);
//
//        if($order_by !== NULL && $order_direction !== NULL)
//            $this->db->order_by($order_by, $order_direction);
//
//        foreach ($params as $column => $value) {
//            $this->db->where($column, $value);
//        }
//
//        return $this->db->get('bullhorn_integration')->result();
//    }
//
//    public function get_count($params = array())
//    {
//        foreach ($params as $column => $value) {
//            $this->db->where($column, $value);
//        }
//
//        return $this->db->count_all_results('bullhorn_integration');
//    }
//
//    public function add_row($data = array())
//    {
//        return $this->db->insert('bullhorn_integration', $data);
//    }
//
//    public function update_row($params = array(), $data = array())
//    {
//        foreach ($params as $column => $value) {
//            $this->db->where($column, $value);
//        }
//
//        $this->db->limit(1);
//        return $this->db->update('bullhorn_integration', $data);
//    }
//
//    public function delete($params = array())
//    {
//        foreach ($params as $column => $value) {
//            $this->db->where($column, $value);
//        }
//
//        return $this->db->delete('bullhorn_integration');
//    }
}

/* End of file */
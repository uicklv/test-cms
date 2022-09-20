<?php
class ShopsModel extends Model
{
    public $version = 0; // increment it for auto-update

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `shop_products` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(200) NOT NULL,
                `ref` varchar(200) DEFAULT NULL,
                `price` FLOAT(11) DEFAULT NULL,
                `preview_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_keywords` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `meta_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
                `image` varchar(100) DEFAULT NULL,
                `posted` enum('no','yes') DEFAULT 'yes',
                `deleted` enum('no','yes') DEFAULT 'no',
                `highlight` tinyint(1) DEFAULT 0,
                `views` int(10) unsigned DEFAULT 0,
                `slug` varchar(200) NOT NULL DEFAULT '',
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                INDEX (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `shop_products_types` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `shop_product_id` int(10) unsigned NOT NULL,
                `type_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `shop_products_brands` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `shop_product_id` int(10) unsigned NOT NULL,
                `brand_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",

            "CREATE TABLE IF NOT EXISTS `shop_products_media` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `product_id` int(10) unsigned NOT NULL,
                `media` varchar(60) NOT NULL,
                `type` enum('image', 'video') DEFAULT 'image',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `shop_shortlist` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL,
                `product_id` int(10) unsigned NOT NULL,
                `time` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 COLLATE=utf8mb4_unicode_ci ;",
            "CREATE TABLE IF NOT EXISTS `shop_reviews` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL,
                `product_id` int(10) unsigned NOT NULL,
                `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `rating` tinyint unsigned NOT NULL,
                `time` int(10) unsigned NOT NULL,
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
//                $queries[] = "ALTER TABLE `vacancies` ADD COLUMN `internal` tinyint(2) unsigned NOT NULL DEFAULT 0 AFTER `postcode`;";
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
            FROM `shop_products`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        $product = self::fetch(self::query($sql));

        $product = self::relationship($product, 'shop_products', 'brands');
        $product = self::relationship($product, 'shop_products', 'types');

        return $product;
    }

    /**
     * Get all
     * @return array
     */
    public static function getAll($where = false, $limit = false, $status = 'no')
    {
        $sql = "
           SELECT *
            FROM `shop_products`
            WHERE `deleted` = '$status'
        ";

        if ($where)
            $sql .= " AND $where";

        $sql .= " ORDER BY `time` DESC, `id` DESC";

        if ($limit)
            $sql .= " LIMIT $limit";

        $products = self::fetchAll(self::query($sql));

        $products = self::relationship($products, 'shop_products', 'brands');
        $products = self::relationship($products, 'shop_products', 'types');

        return $products;
    }

    public static function removeBrands($vid)
    {
        $sql = "
            DELETE 
            FROM `shop_products_brands` 
            WHERE `shop_product_id` = '$vid'
        ";

        return self::query($sql);
    }

    public static function removeTypes($vid)
    {
        $sql = "
            DELETE 
            FROM `shop_products_types` 
            WHERE `shop_product_id` = '$vid'
        ";

        return self::query($sql);
    }

    public static function getProductBrands($pid)
    {
        $sql = "
            SELECT `shop_products_brands`.*, `brands`.`name` as `brand_name`
            FROM `shop_products_brands`
            LEFT JOIN `brands` ON `brands`.`id` = `shop_products_brands`.`brand_id`
            WHERE `shop_products_brands`.`shop_product_id` = '$pid'
        ";

        return self::fetchAll(self::query($sql));
    }

    public static function getProductTypes($pid)
    {
        $sql = "
            SELECT `shop_products_types`.*, `types`.`name` as `type_name`
            FROM `shop_products_types`
            LEFT JOIN `types` ON `types`.`id` = `shop_products_types`.`type_id`
            WHERE `shop_products_types`.`shop_product_id` = '$pid'
        ";

        return self::fetchAll(self::query($sql));
    }

//    public static function getSlug($slug)
//    {
//        $sql = "SELECT COUNT(`id`)
//        FROM `shop_products`
//        WHERE `slug` REGEXP '^$slug(-[0-9])?'"; //like
//
//        $count = self::fetch(self::query($sql), 'row')[0];
//
//        if ($count > 0) {
//            return $slug . '-' . ($count + 1);
//        }
//
//        return $slug;
//    }

    public static function getRef($ref)
    {
        $sql = "SELECT COUNT(`id`) 
        FROM `shop_products`
        WHERE `ref` REGEXP '^$ref(-[0-9])?'";

        $count = self::fetch(self::query($sql), 'row')[0];

        if ($count > 0) {
            return $ref . '-' . ($count + 1);
        }

        return $ref;
    }
}

/* End of file */

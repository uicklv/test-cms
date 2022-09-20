<?php
class ShopModel extends Model
{
    public $version = 0;

    /**
     * Method module_install start automatically if it not exist in `modules` table at first importing of model
     */
    public function module_install()
    {
        $queries = array();

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

    public static function getCount()
    {
        $sql = "
            SELECT COUNT(`id`) AS `count`
            FROM `shop_products`
            WHERE `deleted` = 'no' 
        ";
        $result = self::fetch(self::query($sql));

        return $result->count;
    }

    public static function get($id)
    {
        $sql = "
           SELECT *
            FROM `shop_products`
            WHERE `deleted` = 'no'
            AND `id` = $id LIMIT 1
        ";

       return self::fetch(self::query($sql));
    }

    public static function getReviews($pid)
    {
        $sql = "
           SELECT *
            FROM `shop_reviews`
            WHERE `product_id` = $pid 
        ";

        $reviews = self::fetchAll(self::query($sql));
        $reviews = self::relationship($reviews, 'shop_reviews', 'candidates', ['id', 'firstname', 'lastname'], false, 'user_id', 'one_to_many');

        return $reviews;
    }

    public static function getBySlug($slug)
    {
        $sql = "
           SELECT *
            FROM `shop_products`
            WHERE `deleted` = 'no'
            AND `slug` = '$slug' LIMIT 1
        ";

        $product = self::fetch(self::query($sql));

        $product = self::relationship($product, 'shop_products', 'brands');
        $product = self::relationship($product, 'shop_products', 'types');

        return $product;
    }

    public static function getHighlights()
    {
        $sql = "
           SELECT *
            FROM `shop_products`
            WHERE `deleted` = 'no'
            AND `highlight` = 1
        ";

       return self::fetchAll(self::query($sql));
    }

    public static function getPopular($limit = false)
    {
        $sql = "
           SELECT *,
            (SELECT shop_shortlist.id FROM shop_shortlist WHERE shop_products.id = shop_shortlist.product_id AND shop_shortlist.user_id = '" . User::get('id', 'candidate') . "') AS 'saved'
            FROM `shop_products`
            WHERE `deleted` = 'no'
            ORDER BY `views` DESC
        ";

        if ($limit)
            $sql .= " LIMIT $limit";

        return self::fetchAll(self::query($sql));
    }

    public static function getAll($start = false, $end = false)
    {
        $sql = "
           SELECT *,
            (SELECT shop_shortlist.id FROM shop_shortlist WHERE shop_products.id = shop_shortlist.product_id AND shop_shortlist.user_id = '" . User::get('id', 'candidate') . "') AS 'saved'
            FROM `shop_products`
            WHERE `deleted` = 'no'
            ORDER BY `time` DESC
        ";

        if ($start !== false && $end !== false)
            $sql .= " LIMIT $start, $end ";

        $products = self::fetchAll(self::query($sql));

        $products = self::relationship($products, 'shop_products', 'brands');
        $products = self::relationship($products, 'shop_products', 'types');

        return $products;
    }

    public static function search($keywords = false, $brand = false, $type = false, $price = false, $sort = false, $where = false)
    {
        $sql = "
           SELECT *,
            (SELECT shop_shortlist.id FROM shop_shortlist WHERE shop_products.id = shop_shortlist.product_id AND shop_shortlist.user_id = '" . User::get('id', 'candidate') . "') AS 'saved'
            FROM `shop_products`
            WHERE `deleted` = 'no'
        ";

        if ($keywords) {
            if (is_array($keywords)) {
                $string = implode('|', $keywords);
                $sql .= " AND (`title` REGEXP '$string' OR `content` REGEXP '$string' OR `ref` REGEXP '$string' OR `preview_content` REGEXP '$string')";
            } else {
                $sql .= " AND (`title` LIKE '%$keywords%' OR `content` LIKE '%$keywords%' OR `ref` LIKE '%$keywords%' OR `preview_content` LIKE '%$keywords%')";
            }
        }

        if ($price) {
            if (strpos($price, ',') !== false)
                $price = explode(',', $price);

            $min = $price[0];
            $max = $price[1];

            $sql .= " AND (`price` >= $min AND `price` <= $max) ";
        }

        if ($where)
            $sql .= " AND $where";

        if ($sort) {
            switch ($sort) {
                case 'recent':
                    $sql.= " ORDER BY `time` DESC";break;
                case 'popular':
                    $sql.= " ORDER BY `views` DESC";break;
                case 'cheap':
                    $sql.= " ORDER BY `price` ASC";break;
                case 'expensive':
                    $sql.= " ORDER BY `price` DESC";break;
            }
        }

        $products =  self::fetchAll(self::query($sql));

        $products = self::relationship($products, 'shop_products', 'brands', '*', $brand);
        $products = self::relationship($products, 'shop_products', 'types', '*', $type);

        return $products;
    }

    public static function getFavorite($uid, $pid)
    {
        $sql = "
            SELECT *
            FROM `shop_shortlist`
            WHERE `user_id` = '$uid'
            AND `product_id` = '$pid'
        ";

        return self::fetch(self::query($sql));
    }
}

/* End of file */
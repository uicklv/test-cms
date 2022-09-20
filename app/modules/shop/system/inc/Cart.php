<?php

class Cart
{
    /**
     * get all products from cart
     * @return array
     */
    static function getProducts() : array
    {
        return getSession('cart_products') ?: [];
    }

    /**
     * get number products from cart
     * @return int
     */
    static function getCount() : int
    {
        return count(self::getProducts());
    }

    /**
     * set product to cart
     * @param int $productId
     */
    static function setProduct(int $productId) : void
    {
        $count = self::getProduct($productId);
        $_SESSION['cart_products'][$productId] = ++$count;
    }

    /**
     * get product from cart by id
     * @param int $productId
     * @return int
     */
    static function getProduct(int $productId) : int
    {
        return $_SESSION['cart_products'][$productId] ?: 0;
    }

    /**
     * @param int $productId
     */
    static function removeProduct(int $productId) : void
    {
        unset($_SESSION['cart_products'][$productId]);
    }

    /**
     * minus the amount of product
     * @param int $productId
     * @return int
     */
    static function minusProduct(int $productId) : int
    {
        $countProduct = self::getProduct($productId);

        $countProduct = $countProduct > 1 ? --$countProduct : 0;

        return $countProduct;
    }

    /**
     * remove all products from cart
     */
    static function clearCart() : void
    {
        unset($_SESSION['cart_products']);
    }

}
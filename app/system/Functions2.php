<?php

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array $array
     * @param string $key
     * @return mixed
     */
    function array_get($array, $key)
    {
        if (!is_array($array)) {
            return false;
        }

        if (is_null($key) || empty(trim($key))) {
            return false;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        $keys = explode('.', $key);

        $result = $array;

        while ($k = array_shift($keys)) {
            if (empty($result[$k])) {
                $result = '';
            } else {
                $result = $result[$k];
            }
        }

        return $result;
    }
}

/* End of file */

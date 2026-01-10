<?php

if (!function_exists('double_md5')) {
    /**
     * Encripta una cadena con doble MD5.
     *
     * @param string $value
     * @return string
     */
    function double_md5($value)
    {
        return md5(md5($value));
    }
}

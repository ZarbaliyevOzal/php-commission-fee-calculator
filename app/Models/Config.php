<?php

namespace App\Models;

class Config
{
    /**
     * Get config value
     * @param {String} $key
     * @return {*}
     */
    public static function get($key)
    {
        return $GLOBALS['config'][$key];
    }
}
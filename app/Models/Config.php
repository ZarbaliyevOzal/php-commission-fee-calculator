<?php

namespace App\Models;

trait Config
{
    /**
     * Get config value
     * @param {String} $key
     * @return {*}
     */
    public function config($key)
    {
        return $GLOBALS['config'][$key];
    }
}
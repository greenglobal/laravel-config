<?php

namespace GGPHP\Config\Helpers;

use GGPHP\Config\Models\LaravelConfig;

class Config {
    // Get info config by code
    public function getConfigOf($code)
    {
        if ($config = LaravelConfig::where('code', $code)->first()) {
            return $config;
        }

        return false;
    }
}

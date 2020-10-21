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

    public function getThrottle($nameRoute)
    {
        $throttle = $this->getConfigOf($nameRoute);

        return ! empty($throttle) ? json_decode($throttle->value, true) : [];
    }
}

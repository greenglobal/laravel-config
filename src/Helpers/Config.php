<?php

namespace GGPHP\Config\Helpers;

use GGPHP\Config\Models\LaravelConfig;

class Config {
    // Get info config by code
    public function getConfigByCode($code)
    {
        if ($config = LaravelConfig::where('code', $code)->first()) {
            return $config;
        }

        return false;
    }

    public function getValueDefault($code)
    {
        $fields = config('laravelconfig.fields');
        $default = null;

        foreach ($fields as $field) {
            if ($field['code'] === $code && isset($field['default'])) {
                $default = $field['default'];
                break;
            }
        }

        return $default;
    }

    public function getThrottle($routeName)
    {
        $throttle = $this->getConfigByCode($routeName);

        return ! empty($throttle) ? json_decode($throttle->value, true) : [];
    }
}

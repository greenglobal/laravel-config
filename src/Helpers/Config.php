<?php

    use GGPHP\Config\Models\GGConfig;

    /**
     * This function use to get config info by code
     *
     * @param  code field  $code
     * @return object
     */
    if (! function_exists('getConfigByCode')) {
        function getConfigByCode($code)
        {
            if ($config = GGConfig::where('code', $code)->first())
                return $config;

            return false;
        }
    }

    /**
     * This function use to get default value
     *
     * @param  code field  $code
     * @return object
     */
    if (! function_exists('getDefaultValue')) {
        function getDefaultValue($code)
        {
            $fields = config('config.system');
            $default = null;

            foreach ($fields as $field) {
                if ($field['code'] === $code && isset($field['default'])) {
                    $default = $field['default'];

                    break;
                }
            }

            return $default;
        }
    }

    /**
     * This function use to get throttle info
     *
     * @param  the name of the route  $routeName
     * @return object
     */
    if (! function_exists('getThrottle')) {
        function getThrottle($routeName)
        {
            $throttle = $this->getConfigByCode($routeName);

            return ! empty($throttle) ? json_decode($throttle->value, true) : [];
        }
    }
?>

<?php

    use GGPHP\Config\Models\GGConfig;
    use GGPHP\Config\Services\FirebaseService;

    /**
     * This function use to get config info by code
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
    if (! function_exists('getDefaultValueByCode')) {
        function getDefaultValueByCode($code)
        {
            $configs = config('config.system');
            $default = null;

            foreach ($configs as $config) {
                if ($config['key'] == 'configuration.system.fields') {
                    foreach ($config['fields'] as $field) {
                        if ($field['code'] === $code && isset($field['default'])) {
                            $default = $field['default'];

                            break;
                        }
                    }
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
            $throttle = getConfigByCode($routeName);

            return ! empty($throttle) ? json_decode($throttle->value, true) : [];
        }
    }

    /**
     * This function use to get key data of firebase info by code
     * @param  code field  $code
     * @return string
     */
    if (! function_exists('getKeyByCode')) {
        function getKeyByCode($code)
        {
            $firebaseService = new FirebaseService;
            $reference = $firebaseService->retrieveData(GGConfig::FIELD_REFERENCE);
            $data = $reference->getValue() ?? [];

            foreach($data as $key => $value) {
                if ($value['code'] == $code)
                   return $key;
            }

            return false;
        }
    }

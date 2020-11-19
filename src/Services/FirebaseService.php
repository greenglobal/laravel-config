<?php

declare(strict_types=1);

namespace GGPHP\Config\Services;

use GGPHP\Config\Models\GGConfig;

class FirebaseService
{

    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    /**
     * This function use to get reference
     * @param string $reference
     * @return object
     */
    public function retrieveData($reference = null)
    {
        return $reference ? $this->database->getReference($reference) : $this->database->getReference();
    }

    /**
     * This function use to add data in firebase
     *
     * @param string $reference
     * @param object $data
     * @return object
     */
    public function addData($reference = null, $data = null)
    {
        $firebase = $this->retrieveData($reference);

        return $firebase->push($data);
    }

    /**
     * This function use to set data in firebase
     * @param string $reference
     * @param object $data
     * @return object
     */
    public function setData($reference = null, $data = null)
    {
        $firebase = $this->retrieveData($reference);

        return $firebase->set($data);
    }

    /**
     * This function use to get data config info by code
     * @param  code field  $code
     * @return object
     */
    public function getDataByCode($code)
    {
        $reference = $this->retrieveData(GGConfig::FIELD_REFERENCE);
        $data = $reference->getValue() ?? [];
        $configs = [];

        foreach ($data as $value) {
            if (isset($value['code']) && $value['code'] == $code) {
                $configs = $value;
            }
        }

        return $configs;
    }
}

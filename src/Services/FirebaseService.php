<?php

declare(strict_types=1);

namespace GGPHP\Config\Services;

use GGPHP\Config\Models\GGConfig;
use GGPHP\Config\Models\GGFirebaseStorage;

class FirebaseService
{

    protected $database;
    protected $storage;

    public function __construct()
    {
        $this->database = app('firebase.database');

        $this->storage = app('firebase.storage');
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

    /**
     * This function use to upload file to firebase
     * @param  file field  $file
     * @return object
     */
    public function uploadFile($file, $type, $reference, $expiresAt)
    {
        if (! empty($file)) {
            $bucket = $this->storage->getBucket();
            $name = $file->getClientOriginalName();
            $destination = (! empty($reference) ? $reference . '/' : '')  . $name;

            $result = $bucket->upload($file->get(), [
                'resumable' => true,
                'name' => $destination,
                'uploadType' => $type,
                'predefinedAcl' => 'publicRead'
            ]);

            $url = $result->signedUrl(new \DateTime($expiresAt)) ?? '';

            return GGFirebaseStorage::create([
                'name' => $name,
                'url' => $url,
                'destination' => $destination,
                'type' => $type,
                'expires' => $expiresAt ?? ''
            ]);
        }

        return false;
    }

    /**
     * This function use to get file from firebase by reference
     * @param  file field  $file
     * @return object
     */
    public function getFile($reference)
    {
        $results = GGFirebaseStorage::where('destination', $reference)->first();

        if (! empty($results)) {
            return $results;
        }

        return false;
    }

    /**
     * This function use to upload file by path to firebase
     * @param  path field  $filePath
     * @param  name field  $name
     * @param  type field  $type
     * @param  reference field  $reference
     * @param  expiresAt field  $expiresAt
     * @return object
     */
    public function uploadFileByPath($filePath, $name, $type, $reference, $expiresAt)
    {
        if (! empty($filePath) && ! empty($name) && ! empty($expiresAt)) {
            $bucket = $this->storage->getBucket();
            $fileContents = file_get_contents($filePath);
            $destination = (! empty($reference) ? $reference . '/' : '')  . $name;

            if (! empty($fileContents)) {
                $result = $bucket->upload($fileContents, [
                    'resumable' => true,
                    'name' => $destination,
                    'uploadType' => $type,
                    'predefinedAcl' => 'publicRead'
                ]);

                $url = $result->signedUrl(new \DateTime($expiresAt)) ?? '';

                return GGFirebaseStorage::create([
                    'name' => $name,
                    'url' => $url,
                    'destination' => $destination,
                    'type' => $type,
                    'expires' => $expiresAt ?? ''
                ]);
            }
        }

        return false;
    }
}

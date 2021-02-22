<?php

namespace GGPHP\Config\Tests;

use GGPHP\Config\Tests\TestCase;
use GGPHP\Config\Models\GGConfig;
use GGPHP\Config\Services\FirebaseService;
use Kreait\Firebase\ServiceAccount;

class FirebaseTest extends TestCase
{
    /**
     * Check upload file to firebase storage
     *
     * @return void
     */
    public function testUploadFile()
    {
        $firebaseService = new FirebaseService;
        $filePath = __DIR__ . '/image-test.png';
        $uploadFile = $firebaseService->uploadFileByPath($filePath, 'image-test', 'image/png', 'images', 'today');
        $this->assertNotEmpty($uploadFile, 'File is empty');
    }

    /**
     * Check get url of file on firebase storage
     *
     * @return void
     */
    public function testGetFileUrl()
    {
        $firebaseService = new FirebaseService;
        $filePath = __DIR__ . '/image-test.png';
        $results = $firebaseService->uploadFileByPath($filePath, 'image-test', 'image/png', 'images', 'today');
        $this->assertNotEmpty($results, 'File is empty');

        $results = $firebaseService->getFile('images/image-test');
        $this->assertNotEmpty($results, 'File is empty');
    }
}

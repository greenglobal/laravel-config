<?php

namespace GGPHP\Config\Tests;

use GGPHP\Config\Tests\TestCase;
use GGPHP\Config\Models\GGConfig;
use GGPHP\Config\Services\FirebaseService;
use Kreait\Firebase\ServiceAccount;

class FirebaseTest extends TestCase
{

    /**
     * Check view throttle
     *
     * @return void
     */
    public function testUploadFile()
    {
        $firebaseService = new FirebaseService;
        $filePath = __DIR__ . '/image-test.png';
        $results = $firebaseService->uploadFileByPath($filePath, 'image-test', 'image/png', '', 'today');
        $this->assertNotEmpty($results, 'File is empty');
    }
}

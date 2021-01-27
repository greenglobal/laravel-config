<?php

namespace GGPHP\Config\Tests;

use GGPHP\Config\Tests\TestCase;
use GGPHP\Config\Models\GGConfig;

class ThrottleTest extends TestCase
{
    /**
     * Check view throttle
     *
     * @return void
     */
    public function testThrottleCanView()
    {
        $response = $this->get('configuration/throttles');

        $response->assertStatus(200)->assertSee('API Throttles');
    }

    /**
     * Check view for edit throttle
     *
     * @return void
     */
    public function testThrottleCanEdit()
    {
        $viewResponse = $this->get('configuration/throttles');
        $viewResponse->assertStatus(200)->assertSee('API Throttles');

        $throttleName = 'api.throttle.get';

        $editResponse = $this->get('configuration/throttle/edit/' . $throttleName);

        // Check view for edit throttle
        $editResponse->assertStatus(200)->assertSee('Edit throttle');

        // Set max attempts and decay minutes for api.throttle.get
        $maxAttempts = 2;
        $decayMinutes = 1;

        $throttleConfig = [
            'name' => $throttleName,
            'max_attempts' => (string) $maxAttempts,
            'decay_minutes' => $decayMinutes,
        ];

        $updateResponse = $this->post('configuration/throttle/update', $throttleConfig);

        // Check successful updating
        $updateResponse->assertStatus(302);

        $throttleResult = GGConfig::where('code', 'api.throttle.get')->first();
        $this->assertNotEmpty($throttleResult, 'Throttle is empty');

        for ($i = 1; $i <= $maxAttempts; $i++) {
            $apiResponse = $this->get('api/throttle');

            $apiResponse->assertStatus(200);
        }

        // Check all methods when calling api, 429 too many requests
        // GET, POST, PATCH, PUT, DELETE
        $errorResponse = $this->get('api/throttle');
        $errorResponse->assertStatus(429);

        $errorResponse = $this->post('api/throttle');
        $errorResponse->assertStatus(200);

        $errorResponse = $this->put('api/throttle');
        $errorResponse->assertStatus(200);

        $errorResponse = $this->patch('api/throttle/' . $throttleResult->id);
        $errorResponse->assertStatus(200);

        $errorResponse = $this->delete('api/throttle/' . $throttleResult->id);
        $errorResponse->assertStatus(200);
    }
}

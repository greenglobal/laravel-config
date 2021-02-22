<?php

namespace GGPHP\Config\Tests\API;

use GGPHP\Config\Tests\TestCase;
use GGPHP\Config\Models\GGConfig;

class APIThrottleTest extends TestCase
{
    /**
     * Check api able get all throttle data
     *
     * @return void
     */
    public function testCanGetAllThrottle()
    {
        $response = $this->get(route('api.throttles.index'));

        $response->assertStatus(200);
    }

    /**
     * Check the fields are able to update value
     *
     * @return void
     */
    public function testCanUpdateThrottle()
    {
        $nameRoute = 'api.field.index';
        $response = $this->post(route('api.throttles.update'), [
            'name' => $nameRoute,
            'max_attempts' => '65',
            'decay_minutes' => '2'
        ]);

        $response->assertStatus(200);

        // Check api able get throttle by name
        $response = $this->get(route('api.throttles.get', ['name' => $nameRoute]));

        $response->assertStatus(200);
    }

    /**
     * Check api able reset all field to back default value
     *
     * @return void
     */
    public function testCanResetAllThrottle()
    {
        $nameRoute = 'api.field.index';
        $response = $this->post(route('api.throttles.update'), [
            'name' => $nameRoute,
            'max_attempts' => '65',
            'decay_minutes' => '2'
        ]);

        $response = $this->get(route('api.throttles.reset'));

        $response->assertStatus(200);

        // Check api able get throttle by name
        $response = $this->get(route('api.throttles.get', ['name' => $nameRoute]));

        $response->assertStatus(200);

        $this->assertNotEmpty($response['data']['data']['max_attempts'], 'Max attempts is empty');

        $this->assertNotEmpty($response['data']['data']['decay_minutes'], 'Decay minutes is empty');

        $this->assertEquals($response['data']['data']['max_attempts'], GGConfig::MAX_ATTEMPTS_DEFAULT);

        $this->assertEquals($response['data']['data']['decay_minutes'], GGConfig::DECAY_MINUTES_DEFAULT);
    }
}

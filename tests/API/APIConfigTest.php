<?php

namespace GGPHP\Config\Tests\API;

use GGPHP\Config\Tests\TestCase;

class APIConfigTest extends TestCase
{
    /**
     * Check the fields are able to update value
     *
     * @return void
     */
    public function testCanUpdateField()
    {
        $value = ['max_attempts' => 60, 'decay_minutes' => 1];
        $response = $this->post(route('api.field.create'), [
            'code' => 'test',
            'value' => $value,
            'type' => 'text',
            'default' => $value
        ]);

        $response->assertStatus(201);

        $this->assertNotEmpty($response['data']['id'], 'Not found id.');

        $newValue = ['max_attempts' => 65, 'decay_minutes' => 2];
        $response = $this->patch(route('api.field.update'), [
            'id' => $response['data']['id'],
            'code' => 'test',
            'value' => $newValue,
            'type' => 'text',
            'default' => $newValue
        ]);

        $response->assertStatus(200);
    }

    /**
     * Check api able get all field data
     *
     * @return void
     */
    public function testCanGetAllField()
    {
        $response = $this->get(route('api.field.index'));

        $response->assertStatus(200);
    }

    /**
     * Check api able reset all field to back default value
     *
     * @return void
     */
    public function testCanResetAllField()
    {
        $response = $this->get(route('api.field.reset'));

        $response->assertStatus(200);
    }
}

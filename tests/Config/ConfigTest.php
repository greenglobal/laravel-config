<?php

namespace GGPHP\Config\Tests;

use GGPHP\Config\Tests\TestCase;

class ConfigTest extends TestCase
{
    /**
     * Check view config page
     *
     * @return void
     */
    public function testConfigCanView()
    {
        $response = $this->get('configuration/field/edit');

        $response->assertStatus(200)->assertSee('System configurations');
    }

    /**
     * Check view for save config
     *
     * @return void
     */
    public function testConfigCanSave()
    {
        // Get field data from config file
        $configs = config('config.system');
        $data = [];

        foreach ($configs as $config) {
            if ($config['key'] == 'configuration.system.fields') {
                foreach ($config['fields'] as $field) {
                    $value = ! empty($field['default']) ? $field['default'] : (! empty($field['value']) ? $field['value'] : '');
                    $data = array_merge($data, [$field['code'] => $value]);
                }
            }
        }

        $response = $this->patch('configuration/field/updates', $data);

        $response->assertStatus(count($data) == 0 ? 200 : 302);
    }

    /**
     * Check view for reset to default
     *
     * @return void
     */
    public function testConfigCanResetToDefault()
    {
        $response = $this->get('configuration/field/reset');

        $response->assertStatus(200);
    }
}

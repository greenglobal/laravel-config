<?php

namespace GGPHP\Config\Tests;

use GGPHP\Config\Tests\TestCase;

class AccessFieldTest extends TestCase
{
    /**
     * Check the fields are able to accessing with user role
     *
     * @return void
     */
    public function testAccessFieldCanView()
    {
        $response = $this->get('configuration/field/edit');

        $response->assertStatus(200)
            ->assertSee('System configurations')
            ->assertSee('title 1')
            ->assertDontSee('title 2')
            ->assertSee('title 3')
            ->assertSee('title 4')
            ->assertSee('title 5');
    }
}

<?php

namespace GGPHP\Configuration\Tests;

use Orchestra\Testbench\TestCase;
use GGPHP\Configuration\ConfigurationServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ConfigurationServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}

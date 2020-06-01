<?php

namespace Nrbusinesssystems\MaximoQuery\Tests;

use Nrbusinesssystems\MaximoQuery\Providers\MaximoQueryServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{

    protected function getPackageProviders($app)
    {
        return [MaximoQueryServiceProvider::class];
    }

}

<?php

namespace Papimod\Database\Test;

use Papimod\Dotenv\LoadEnvEvent;
use PHPUnit\Framework\TestCase;

class DatabaseTestCase extends TestCase
{
    public function setUp(): void
    {
        define("ENVIRONMENT_DIRECTORY", dirname(__DIR__));
        define("ENVIRONMENT_FILE", ".env");
        (new LoadEnvEvent())();

        defined("DATABASE_HOST") || define("DATABASE_HOST", $_SERVER['DATABASE_HOST']);
        defined("DATABASE_PORT") || define("DATABASE_PORT", $_SERVER['DATABASE_PORT']);
        defined("DATABASE_USER") || define("DATABASE_USER", $_SERVER["DATABASE_USER"]);
        defined("DATABASE_PASSWORD") || define("DATABASE_PASSWORD", $_SERVER["DATABASE_PASSWORD"]);
        defined("DATABASE_CHARSET") || define("DATABASE_CHARSET", $_SERVER["DATABASE_CHARSET"]);
        defined("DATABASE_NAME") || define("DATABASE_NAME", $_SERVER["DATABASE_NAME"]);
    }
}

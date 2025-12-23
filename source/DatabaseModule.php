<?php

namespace Papimod\Database;

use Papi\PapiModule;
use Papimod\Cache\CacheModule;
use Papimod\Date\DateModule;
use Papimod\Dotenv\DotEnvModule;

final class DatabaseModule extends PapiModule
{
    private static array $migrations = [];

    /**
     * @param string $path file or directory
     */
    public static function addMigration(string ...$path): void
    {
        array_push($migrations, ...$path);
    }

    public static function getPrerequisites(): array
    {
        return [
            DotEnvModule::class,
            DateModule::class,
            CacheModule::class,
            DateModule::class
        ];
    }

    public static function configure(): void
    {
        defined("PAPI_DATABASE_HOST") ||
            define("PAPI_DATABASE_HOST", $_ENV['DATABASE_HOST'] ?? "localhost");

        defined("PAPI_DATABASE_PORT")
            || define("PAPI_DATABASE_PORT", (int) ($_ENV['DATABASE_PORT'] ?? 3306));

        defined("PAPI_DATABASE_USER")
            || define("PAPI_DATABASE_USER", $_ENV["DATABASE_USER"] ?? "root");

        defined("PAPI_DATABASE_PASSWORD")
            || define("PAPI_DATABASE_PASSWORD", $_ENV["DATABASE_PASSWORD"] ?? "");

        defined("PAPI_DATABASE_CHARSET")
            || define("PAPI_DATABASE_CHARSET", $_ENV["DATABASE_CHARSET"] ?? "utf8mb4");

        defined("PAPI_DATABASE_NAME")
            || define("DATABASE_NAME", $_ENV["DATABASE_NAME"]);

        if (defined("DATABASE_MIGRATION_DIRECTORY") === false) {
            $directory = null;

            if (isset($_ENV["DATABASE_MIGRATION_DIRECTORY"])) {
                $directory = PAPI_DOTENV_DIRECTORY
                    . DIRECTORY_SEPARATOR
                    . trim($_ENV["DATABASE_MIGRATION_DIRECTORY"], DIRECTORY_SEPARATOR);
            }

            define("DATABASE_MIGRATION_DIRECTORY", $directory);
        }
    }
}

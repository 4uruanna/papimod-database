# Database Papi Module

![]( https://img.shields.io/badge/php-8.5-777BB4?logo=php)
![]( https://img.shields.io/badge/composer-2-885630?logo=composer)

## Description

Help defining the API [date format](https://www.php.net/manual/en/datetime.format.php) with [time zone](https://www.php.net/manual/en/timezones.php) in your [papi](https://github.com/4uruanna/papi).

Also provide a `DateService` class to convert dates.

## Prerequisites Modules

- [Papimod/Dotenv](https://github.com/4uruanna/papimod-dotenv)
- [Papimod/Date](https://github.com/4uruanna/papimod-date)
- [Papimod/Cache](https://github.com/4uruanna/papimod-cache)

## Configuration

### `DATABASE_HOST` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | string                                            |
|Description    | Database host                                     |
|Default        | `localhost`                                       |

### `DATABASE_PORT` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | int                                               |
|Description    | Database port                                     |
|Default        | `3306`                                            |

### `DATABASE_USER` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | string                                            |
|Description    | Database user                                     |
|Default        | `root`                                            |

### `DATABASE_PASSWORD` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | string                                            |
|Description    | Database password                                 |
|Default        | empty string                                      |

### `DATABASE_CHARSET` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | string                                            |
|Description    | Database charset                                  |
|Default        | `utf8mb4`                                         |

### `DATABASE_NAME` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | Yes                                               |
|Type           | string                                            |
|Description    | Database name                                     |

## Definitions

- (pdo builder) [StatementBuilder](./source/StatementBuilder.php)


## Usage

You can add the following options to your  `.env` file:

```
DATABASE_HOST="localhost"
DATABASE_PORT=3306
DATABASE_USER="root"
DATABASE_PASSWORD=""
DATABASE_CHARSET="utf8mb4"
DATABASE_NAME="my_database"
```

Import the module when creating your application:

```php
require __DIR__ . "/../vendor/autoload.php";

use Papi\PapiBuilder;
use Papimod\Dotenv\DotEnvModule;
use Papimod\Date\DateModule;
use Papimod\Cache\CacheModule;
use Papimod\Date\DateModule;
use Papimod\Database\DatabaseModule;
use function DI\create;

$builder = new PapiBuilder();

$builder->setModule(
        DotEnvModule::class,
        DateModule::class,
        CacheModule::class,
        DatabaseModule::class
    )
    ->build()
    ->run();
```

### PDO Builder

```php
use Papimod\Database\StatementBuilder;
use Papimod\Database\pdo\enumerator\Type;

$statement = StatementBuilder::from("table")
    ->select("name", "age", "email")
    ->where("age")->isGreater(18, Type::INT)
    ->build();

$statement->execute();

// ...
```
<?php

namespace Papimod\Database\Test\pdo;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\Database;
use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\StatementUpdateBuilder;
use Papimod\Database\StatementBuilder;
use Papimod\Date\DateModule;
use Papimod\Date\DateService;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementUpdateBuilder::class)]
final class StatementUpdateBuilderTest extends TestCase
{
    private static Generator $faker;
    private static DateService $date_service;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
        self::$date_service = new DateService();
        DateModule::configure();
    }

    private string $table_name = "statement_update_builder_test";

    public function setUp(): void
    {
        $pdo = Database::pdo();
        $pdo->query("DROP TABLE IF EXISTS {$this->table_name}");
        Database::pdo()->query(<<<SQL
            CREATE TABLE IF NOT EXISTS {$this->table_name}
            (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `string` TEXT,
                `decimal` DECIMAL(20, 10),
                `date_time` DATETIME,
                `date` DATE,
                `time` TIME,
                `blob` BLOB,
                PRIMARY KEY (`id`)
            )
        SQL);

        $pdo->query(<<<SQL
            INSERT INTO {$this->table_name}
            (`string`, `decimal`, `date_time`, `date`, `time`, `blob`)
            VALUES
            (
                "a",
                2.3,
                "2020-12-12 18:55:23",
                "2021-12-12",
                "19:55:23",
                "zuuuuppperrrr"
            )
        SQL);
    }

    public function testText(): void
    {
        $sample = self::$faker->text(255);

        $s = StatementBuilder::from($this->table_name)
            ->update(new Column("string", $sample, Type::TEXT))
            ->where("id")
            ->isEqual(1, Type::INT)
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($sample, $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testDecimal()
    {
        $sample = self::$faker->randomFloat(6, 1, 2);

        StatementBuilder::from($this->table_name)
            ->update(new Column("decimal", $sample, Type::DOUBLE))
            ->where("id")
            ->isEqual(1, Type::INT)
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($sample, $statement->fetch(PDO::FETCH_ASSOC)["decimal"]);
    }

    public function testDateTime(): void
    {
        $sample = self::$faker->dateTime();
        $formatted = self::$date_service->format($sample);

        StatementBuilder::from($this->table_name)
            ->update(new Column("date_time", $sample, Type::DATETIME))
            ->where("id")
            ->isEqual(1, Type::INT)
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($formatted, $statement->fetch(PDO::FETCH_ASSOC)["date_time"]);
    }

    public function testDate(): void
    {
        $sample = self::$faker->dateTime();
        $formatted = self::$date_service->formatDate($sample);

        StatementBuilder::from($this->table_name)
            ->update(new Column("date", $sample, Type::DATE))
            ->where("id")
            ->isEqual(1, Type::INT)
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($formatted, $statement->fetch(PDO::FETCH_ASSOC)["date"]);
    }

    public function testTime(): void
    {
        $sample = self::$faker->dateTime();
        $formatted = self::$date_service->formatTime($sample);

        StatementBuilder::from($this->table_name)
            ->update(new Column("time", $sample, Type::TIME))
            ->where("id")
            ->isEqual(1, Type::INT)
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($formatted, $statement->fetch(PDO::FETCH_ASSOC)["time"]);
    }

    public function testBlob(): void
    {
        $blob = file_get_contents(__DIR__ . "/../assets/white-blob.png");

        StatementBuilder::from($this->table_name)
            ->update(new Column("blob", $blob, Type::BLOB))
            ->where("id")
            ->isEqual(1, Type::INT)
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($blob, $statement->fetch(PDO::FETCH_ASSOC)["blob"]);
    }

    public function testMultipleColumns(): void
    {
        $blob = file_get_contents(__DIR__ . "/../assets/white-blob.png");
        $string = self::$faker->userName();

        StatementBuilder::from($this->table_name)
            ->update(
                new Column("blob", $blob, Type::BLOB),
                new Column("string", $string),
            )
            ->where("id")
            ->isEqual(1, Type::INT)
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($blob, $result["blob"]);
        $this->assertEquals($string, $result["string"]);
    }
}

<?php

namespace Papimod\Database\Test\pdo;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\Database;
use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\StatementInsertBuilder;
use Papimod\Database\StatementBuilder;
use Papimod\Date\DateModule;
use Papimod\Date\DateService;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementInsertBuilder::class)]
final class StatementInsertBuilderTest extends TestCase
{
    private static Generator $faker;
    private static DateService $date_service;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
        self::$date_service = new DateService();
        DateModule::configure();
    }

    private string $table_name = "statement_insert_builder_test";

    public function setUp(): void
    {
        Database::pdo()->query("DROP TABLE IF EXISTS {$this->table_name}");
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
    }

    public function testInsertText(): void
    {
        $sample = self::$faker->text(255);

        StatementBuilder::from($this->table_name)
            ->insert(new Column("string", $sample, Type::TEXT))
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($sample, $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testInsertDecimal()
    {
        $sample = self::$faker->randomFloat(6, 1, 2);

        StatementBuilder::from($this->table_name)
            ->insert(new Column("decimal", $sample, Type::DOUBLE))
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($sample, $statement->fetch(PDO::FETCH_ASSOC)["decimal"]);
    }

    public function testInsertDateTime(): void
    {
        $sample = self::$faker->dateTime();
        $formatted = self::$date_service->format($sample);

        StatementBuilder::from($this->table_name)
            ->insert(new Column("date_time", $sample, Type::DATETIME))
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($formatted, $statement->fetch(PDO::FETCH_ASSOC)["date_time"]);
    }

    public function testInsertDate(): void
    {
        $sample = self::$faker->dateTime();
        $formatted = self::$date_service->formatDate($sample);

        StatementBuilder::from($this->table_name)
            ->insert(new Column("date", $sample, Type::DATE))
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($formatted, $statement->fetch(PDO::FETCH_ASSOC)["date"]);
    }

    public function testInsertTime(): void
    {
        $sample = self::$faker->dateTime();
        $formatted = self::$date_service->formatTime($sample);

        StatementBuilder::from($this->table_name)
            ->insert(new Column("time", $sample, Type::TIME))
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($formatted, $statement->fetch(PDO::FETCH_ASSOC)["time"]);
    }
}

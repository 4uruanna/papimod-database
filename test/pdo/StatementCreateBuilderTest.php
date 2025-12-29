<?php

namespace Papimod\Database\Test\pdo;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\Database;
use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\ForeignKey;
use Papimod\Database\pdo\model\PrimaryKey;
use Papimod\Database\pdo\StatementCreateBuilder;
use Papimod\Database\StatementBuilder;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementCreateBuilder::class)]
final class StatementCreateBuilderTest extends TestCase
{
    private string $table_name = "statement_create_builder_test";
    private string $table_name_foo = "statement_create_builder_test_foo";

    private static Generator $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    public function setUp(): void
    {
        Database::pdo()->exec("DROP TABLE IF EXISTS {$this->table_name_foo}");
        Database::pdo()->exec("DROP TABLE IF EXISTS {$this->table_name}");
    }

    public function testCreateTable(): void
    {
        StatementBuilder::from($this->table_name)
            ->create(new Column("POO", null, Type::TEXT))
            ->build()
            ->execute();

        $value = self::$faker->name();
        Database::pdo()->query("INSERT INTO {$this->table_name} (POO) VALUES ('{$value}')")->execute();
        $statement = Database::pdo()->query("SELECT * FROM {$this->table_name}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($value, $result["POO"]);
    }

    public function testTableWithPrimaryKey(): void
    {
        StatementBuilder::from($this->table_name)
            ->create(
                new Column("ID", null, Type::INT, false, true, true),
                new Column("POO", null, Type::TEXT)
            )
            ->constraint(new PrimaryKey("ID"))
            ->build()
            ->execute();

        $value = self::$faker->name();
        Database::pdo()->query("INSERT INTO {$this->table_name} (POO) VALUES ('{$value}')")->execute();
        $statement = Database::pdo()->query("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($value, $result["POO"]);
    }

    public function testTablesWithForeignkey(): void
    {
        StatementBuilder::from($this->table_name)
            ->create(
                new Column("ID", null, Type::INT, false, true, true),
                new Column("POO", null, Type::TEXT)
            )
            ->constraint(new PrimaryKey("ID"))
            ->build()
            ->execute();

        $s = StatementBuilder::from($this->table_name_foo)
            ->create(
                new Column("ID", null, Type::INT, false, true, true),
                new Column("FOO", null, Type::TEXT),
                new Column("POO", null, Type::INT, false, true),
            )
            ->constraint(
                new PrimaryKey("ID"),
                new ForeignKey("POO", $this->table_name, "ID")
            )
            ->build();

        $s->execute();

        $value = self::$faker->name();
        Database::pdo()->query("INSERT INTO {$this->table_name} (POO) VALUES ('{$value}')")->execute();

        $value_foo = self::$faker->name();
        Database::pdo()->query("INSERT INTO {$this->table_name_foo} (FOO, POO) VALUES ('{$value_foo}', 1)")->execute();

        $statement = Database::pdo()->query("SELECT * FROM {$this->table_name_foo} WHERE ID = 1");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals(1, $result["POO"]);
    }
}

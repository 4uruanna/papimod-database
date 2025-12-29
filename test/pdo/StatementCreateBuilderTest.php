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
    private static Generator $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    private string $table_name = "statement_create_builder_test";
    private string $table_name_foo = "statement_create_builder_test_foo";

    private string $user_name_a;
    private string $user_name_b;

    public function setUp(): void
    {
        Database::pdo()->query("DROP TABLE IF EXISTS {$this->table_name_foo}")->execute();
        Database::pdo()->query("DROP TABLE IF EXISTS {$this->table_name}")->execute();
        $this->user_name_a = self::$faker->userName();
        $this->user_name_b = self::$faker->userName();
    }

    public function testCreateTable(): void
    {
        StatementBuilder::from($this->table_name)
            ->create(new Column("POO", null, Type::TEXT))
            ->build()
            ->execute();

        Database::pdo()
            ->prepare("INSERT INTO {$this->table_name} (POO) VALUES (:val)")
            ->execute([":val" => $this->user_name_a]);

        $statement = Database::pdo()->query("SELECT * FROM {$this->table_name}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($this->user_name_a, $result["POO"]);
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

        Database::pdo()
            ->prepare("INSERT INTO {$this->table_name} (POO) VALUES (:val)")
            ->execute([":val" => $this->user_name_a]);

        $statement = Database::pdo()->query("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals($this->user_name_a, $result["POO"]);
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

        StatementBuilder::from($this->table_name_foo)
            ->create(
                new Column("FOO", null, Type::TEXT),
                new Column("POO", null, Type::INT, false, true),
            )
            ->constraint(
                new ForeignKey("POO", $this->table_name, "ID")
            )
            ->build()
            ->execute();

        Database::pdo()
            ->prepare("INSERT INTO {$this->table_name} (POO) VALUES (:val)")
            ->execute([":val" => $this->user_name_a]);


        Database::pdo()
            ->prepare("INSERT INTO {$this->table_name_foo} (FOO, POO) VALUES (:val, 1)")
            ->execute([":val" => $this->user_name_b]);

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name_foo} WHERE FOO = :val");
        $statement->bindValue(":val", $this->user_name_b);
        $statement->execute();

        $this->assertEquals(1, $statement->fetch(PDO::FETCH_ASSOC)["POO"]);
    }
}

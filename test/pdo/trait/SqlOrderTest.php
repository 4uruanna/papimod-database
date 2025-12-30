<?php

namespace Papimod\Database\Test\pdo;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\Database;
use Papimod\Database\pdo\enumerator\Order;
use Papimod\Database\pdo\trait\SqlOrder;
use Papimod\Database\StatementBuilder;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SqlOrder::class)]
final class SqlOrderTest extends TestCase
{
    private static Generator $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    private string $table_name = "statement_select_builder_test";

    private array $clients;

    public function setUp(): void
    {
        $this->clients = [
            [
                "id" => 1,
                "name" => self::$faker->userName(),
                "age" => self::$faker->numberBetween(1, 100),
                "last_connection" => "2020-11-19"
            ],
            [
                "id" => 2,
                "name" => self::$faker->userName(),
                "age" => self::$faker->numberBetween(1, 100),
                "last_connection" => "2021-11-19"
            ],
            [
                "id" => 3,
                "name" => self::$faker->userName(),
                "age" => self::$faker->numberBetween(1, 100),
                "last_connection" => "2022-11-19"
            ],
            [
                "id" => 4,
                "name" => self::$faker->userName(),
                "age" => self::$faker->numberBetween(1, 100),
                "last_connection" => "2023-11-19"
            ]
        ];

        $pdo = Database::pdo();
        $pdo->query("DROP TABLE IF EXISTS {$this->table_name}")->execute();
        $pdo->query("CREATE TABLE IF NOT EXISTS {$this->table_name} (
            `id` INT NOT NULL,
            `name` TEXT NOT NULL,
            `age` INT NOT NULL,
            `last_connection` DATE NOT NULL,
            PRIMARY KEY (`id`)
        )")->execute();

        foreach ($this->clients as $client) {
            $pdo->prepare(
                <<<SQL
                    INSERT INTO {$this->table_name}
                    (`id`, `name`, `age`, `last_connection`)
                    VALUES
                    (:_id, :_name, :_age, :_last_connection)
                SQL
            )
                ->execute([
                    ":_id" => $client["id"],
                    ":_name" => $client["name"],
                    ":_age" => $client["age"],
                    ":_last_connection" => $client["last_connection"],
                ]);
        }
    }

    public function testAsc(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->orderBy("id")
            ->build();

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($this->clients[0]["name"], $result["name"]);
    }

    public function testDesc(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->orderBy("id", Order::DESC)
            ->build();

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($this->clients[3]["name"], $result["name"]);
    }
}

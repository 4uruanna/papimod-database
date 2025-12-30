<?php

namespace Papimod\Database\Test\pdo\trait;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\Database;
use Papimod\Database\pdo\trait\SqlLimit;
use Papimod\Database\StatementBuilder;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SqlLimit::class)]
final class SqlLimitTest extends TestCase
{

    private static Generator $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    private string $table_name = "database_limit_test";

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

    public function testLimit(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->limit(1)
            ->build();

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->assertCount(1, $result);
        $this->assertEquals($this->clients[0]["name"], $result[0]["name"]);
    }

    public function testOffset(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->limit(0, 1)
            ->build();

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->assertCount(3, $result);
    }
}

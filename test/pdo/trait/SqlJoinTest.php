<?php

namespace Papimod\Database\Test\pdo\trait;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\Database;
use Papimod\Database\pdo\trait\SqlJoin;
use Papimod\Database\StatementBuilder;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SqlJoin::class)]
final class SqlJoinTest extends TestCase
{
    private static Generator $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    private string $table_name = "database_join_test";
    private string $relation_table_name = "database_join_relation_test";

    private array $clients;
    private array $relations;

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

        $this->relations = [
            [
                "client" => 1,
                "email" => self::$faker->email()
            ],
            [
                "client" => 2,
                "email" => self::$faker->email()
            ],
            [
                "client" => 4,
                "email" => self::$faker->email()
            ],
            [
                "client" => 4,
                "email" => self::$faker->email()
            ],
            [
                "client" => 4,
                "email" => self::$faker->email()
            ],
            [
                "client" => null,
                "email" => self::$faker->email()
            ],
            [
                "client" => null,
                "email" => self::$faker->email()
            ]
        ];

        $pdo = Database::pdo();
        $pdo->query("DROP TABLE IF EXISTS {$this->relation_table_name}")->execute();
        $pdo->query("DROP TABLE IF EXISTS {$this->table_name}")->execute();

        $pdo->query(<<<SQL
            CREATE TABLE IF NOT EXISTS {$this->table_name}
            (
                `id` INT NOT NULL,
                `name` TEXT NOT NULL,
                `age` INT NOT NULL,
                `last_connection` DATE NOT NULL,
                PRIMARY KEY (`id`)
            )
        SQL)->execute();

        $pdo->query(<<<SQL
            CREATE TABLE IF NOT EXISTS {$this->relation_table_name}
            (
                `id` INT NOT NULL AUTO_INCREMENT,
                `client` INT,
                `email` TEXT NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (client) REFERENCES {$this->table_name}(id)
            )
        SQL)->execute();

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

        foreach ($this->relations as $relation) {
            $pdo->prepare(
                <<<SQL
                    INSERT INTO {$this->relation_table_name}
                    (`email`, `client`)
                    VALUES
                    (:_email, :_client)
                SQL
            )
                ->execute([
                    ":_email" => $relation["email"],
                    ":_client" => $relation["client"]
                ]);
        }
    }

    public function testLeft(): void
    {
        $statement = StatementBuilder::from($this->table_name . " a")
            ->select()
            ->leftJoin($this->relation_table_name . " b", "a.id", "b.client")
            ->build();

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEquals($this->relations[0]["email"], $result[0]["email"]);
        $this->assertCount(6, $result);
    }

    public function testRight(): void
    {
        $statement = StatementBuilder::from($this->table_name . " a")
            ->select()
            ->rightJoin($this->relation_table_name . " b", "a.id", "b.client")
            ->build();

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->assertCount(7, $result);
    }

    public function testInner(): void
    {
        $statement = StatementBuilder::from($this->table_name . " a")
            ->select()
            ->innerJoin($this->relation_table_name . " b", "a.id", "b.client")
            ->build();

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->assertCount(5, $result);
    }

    public function testFull(): void
    {
        $statement = StatementBuilder::from($this->table_name . " a")
            ->select()
            ->fullJoin($this->relation_table_name . " b", "a.id", "b.client")
            ->build();

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->assertCount(8, $result);
    }
}

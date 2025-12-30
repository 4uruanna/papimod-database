<?php

namespace Papimod\Database\Test\pdo\trait;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\trait\SqlColumn;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SqlColumn::class)]
final class SqlColumnTest extends TestCase
{
    private static Generator $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    use SqlColumn;

    public function testAdd(): void
    {
        $column_a = self::$faker->text(10);
        $column_b = self::$faker->text(10);

        $this->addColumn(
            $column_a,
            new Column($column_b)
        );

        $this->assertCount(2, $this->columns);
        $this->assertEquals($column_a, $this->columns[0]->name);
        $this->assertEquals($column_b, $this->columns[1]->name);

        $query = $this->columnQuery();
        $this->assertEquals("`$column_a`, `$column_b`", $query);
    }
}

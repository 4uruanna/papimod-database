<?php

namespace Papimod\Database\Test\pdo;

use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\PrimaryKey;
use Papimod\Database\pdo\StatementCreateBuilder;
use Papimod\Database\pdo\StatementDeleteBuilder;
use Papimod\Database\pdo\StatementDropBuilder;
use Papimod\Database\pdo\StatementInsertBuilder;
use Papimod\Database\pdo\StatementSelectBuilder;
use Papimod\Database\pdo\StatementTableBuilder;
use Papimod\Database\pdo\StatementTruncateBuilder;
use Papimod\Database\pdo\StatementUpdateBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementTableBuilder::class)]
final class StatementTableBuilderTest extends TestCase
{
    private static StatementTableBuilder $builder;

    public static function setUpBeforeClass(): void
    {
        self::$builder = new StatementTableBuilder('foo');
    }

    public function testCreate(): void
    {
        $this->assertInstanceOf(
            StatementCreateBuilder::class,
            self::$builder->create(new Column("ID", null, Type::INT, false, true, true))
                ->constraint(new PrimaryKey("ID"))
        );
    }

    public function testDelete(): void
    {
        $this->assertInstanceOf(
            StatementDeleteBuilder::class,
            self::$builder->delete()
        );
    }

    public function testDrop(): void
    {
        $this->assertInstanceOf(
            StatementDropBuilder::class,
            self::$builder->drop()
        );
    }

    public function testInsert(): void
    {
        $this->assertInstanceOf(
            StatementInsertBuilder::class,
            self::$builder->insert(new Column("FOO", 123, Type::INT))
        );
    }

    public function testSelect(): void
    {
        $this->assertInstanceOf(
            StatementSelectBuilder::class,
            self::$builder->select()
        );
    }

    public function testTruncate(): void
    {
        $this->assertInstanceOf(
            StatementTruncateBuilder::class,
            self::$builder->truncate()
        );
    }

    public function testUpdate(): void
    {
        $this->assertInstanceOf(
            StatementUpdateBuilder::class,
            self::$builder->update(new Column("FOO", 321, Type::INT))
        );
    }
}

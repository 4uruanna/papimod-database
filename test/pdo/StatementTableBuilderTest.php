<?php

namespace Papimod\Database\Test;

use Papimod\Database\pdo\model\InsertColumn;
use Papimod\Database\pdo\model\UpdateColumn;
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
            self::$builder->insert(InsertColumn::INT("FOO", 0))
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
            self::$builder->update(UpdateColumn::INT("FOO", 0))
        );
    }
}

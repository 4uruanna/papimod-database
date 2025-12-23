<?php

namespace Papimod\Database\Test;

use Papimod\Database\pdo\StatementTableBuilder;
use Papimod\Database\StatementBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementBuilder::class)]
final class StatementBuilderTest extends TestCase
{
    public function testFrom(): void
    {
        $this->assertInstanceOf(StatementTableBuilder::class, StatementBuilder::from("foo"));
    }
}

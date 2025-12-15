<?php

namespace Papimod\Database\Test;

use Papimod\Database\sql\SqlQuery;
use Papimod\Database\sql\SqlTable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;
use PHPUnit\Framework\TestCase;

#[CoversClass(SqlQuery::class)]
#[Medium]
final class SqlQueryTest extends TestCase
{
    public function testFrom(): void
    {
        $this->assertInstanceOf(SqlTable::class, SqlQuery::from('table'));
    }
}

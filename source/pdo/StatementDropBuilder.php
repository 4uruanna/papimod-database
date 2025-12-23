<?php

namespace Papimod\Database\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\model\Table;
use PDOStatement;

final class StatementDropBuilder
{
    public function __construct(public readonly Table $table) {}

    public function build(): PDOStatement
    {
        $pdo = Database::pdo();
        return $pdo->prepare("DROP TABLE {$this->table}");
    }
}

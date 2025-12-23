<?php

namespace Papimod\Database\query;

use Papimod\Database\database\Database;
use Papimod\Database\query\model\Table;
use PDOStatement;

final class StatementTruncateBuilder
{
    public function __construct(public readonly Table $table) {}

    public function build(): PDOStatement
    {
        $pdo = Database::pdo();
        return $pdo->prepare("TRUNCATE TABLE {$this->table}");
    }
}

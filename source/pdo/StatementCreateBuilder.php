<?php

namespace Papimod\Database\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\ForeignKey;
use Papimod\Database\pdo\model\PrimaryKey;
use Papimod\Database\pdo\model\Table;
use Papimod\Database\pdo\trait\SqlColumn;
use PDOStatement;

final class StatementCreateBuilder
{
    use SqlColumn;

    private array $constraints = [];

    public function __construct(
        public readonly Table $table,
        Column ...$column
    ) {
        $this->addColumn(...$column);
    }

    public function constraint(PrimaryKey|ForeignKey ...$constraint): StatementCreateBuilder
    {
        array_push($this->constraints, ...$constraint);
        return $this;
    }

    public function columnQuery(): string
    {
        return implode(
            ', ',
            array_map(
                function ($c) {
                    $query = "`{$c->name}` {$c->type->value}";

                    if ($c->unsigned) {
                        $query .= " UNSIGNED";
                    }

                    if ($c->nullable === false) {
                        $query .= " NOT NULL";
                    }

                    if ($c->auto_increment) {
                        $query .= " AUTO_INCREMENT";
                    }

                    if (strlen($c->default)) {
                        $query .= " DEFAULT {$c->default}";
                    }
                },
                $this->columns
            )
        );
    }

    public function constraintQuery(): string
    {
        $query = "";

        if (count($this->constraints)) {
            $query .= "," . implode(', ', $this->constraints);
        }

        return $query;
    }

    public function build(): PDOStatement
    {
        $pdo = Database::pdo();

        $statement = $pdo->prepare(
            <<<SQL
            CREATE TABLE {$this->table->name}
            (
                {$this->columnQuery()}
                {$this->constraintQuery()}
            )
            SQL
        );

        return $statement;
    }
}

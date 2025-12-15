<?php

namespace Papimod\Database\sql;

final class SqlTable
{
    public readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function delete(): SqlDelete
    {
        return new SqlDelete($this);
    }

    public function drop(): SqlDrop
    {
        return new SqlDrop($this);
    }

    public function insert(): SqlInsert
    {
        return new SqlInsert($this);
    }

    public function select(?array $columns = null): SqlSelect
    {
        return new SqlSelect($this, $columns);
    }

    public function truncate(): SqlTruncate
    {
        return new SqlTruncate($this);
    }

    public function update(): SqlUpdate
    {
        return new SqlUpdate($this);
    }
}

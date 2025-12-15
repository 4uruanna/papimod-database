<?php

namespace Papimod\Database\sql\interface;

use PDOStatement;

interface IQuery
{
    public function build(): PDOStatement;
}

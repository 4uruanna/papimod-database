<?php

namespace Papimod\Database\sql\enumerator;

use PDO;

final class Type
{
    public const NULL = PDO::PARAM_NULL;
    public const INTEGER = PDO::PARAM_INT;
    public const STRING = PDO::PARAM_STR;
    public const BOOLEAN = PDO::PARAM_BOOL;
}

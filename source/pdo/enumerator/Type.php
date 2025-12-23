<?php

namespace Papimod\Database\pdo\enumerator;

use PDO;

enum Type: int
{
    case NULL = PDO::PARAM_NULL;
    case INTEGER = PDO::PARAM_INT;
    case STRING = PDO::PARAM_STR;
    case BOOLEAN = PDO::PARAM_BOOL;
}

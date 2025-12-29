<?php

namespace Papimod\Database\pdo\enumerator;

use PDO;

final class PdoType
{
    public static array $values = [
        "LONGTEXT" => PDO::PARAM_STR,
        "TEXT" => PDO::PARAM_STR,
        "TINYTEXT" => PDO::PARAM_STR,

        "BIGINT" => PDO::PARAM_INT,
        "INT" => PDO::PARAM_INT,
        "TINYINT" => PDO::PARAM_INT,

        "LONGBLOB" => PDO::PARAM_LOB,
        "BLOB" => PDO::PARAM_LOB,
        "BLOB_TINY" => PDO::PARAM_LOB,

        "DOUBLE" => PDO::PARAM_STR,
        "DECIMAL" => PDO::PARAM_STR,
        "FLOAT" => PDO::PARAM_STR,

        "DATE" => PDO::PARAM_STR,
        "DATETIME" => PDO::PARAM_STR,
        "TIME" => PDO::PARAM_STR
    ];
}

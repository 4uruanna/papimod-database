<?php

namespace Papimod\Database\pdo\enumerator;

enum Type: string
{
    case TEXT_LONG = "LONGTEXT";
    case TEXT = "TEXT";
    case TEXT_TINY = "TINYTEXT";

    case INT_BIG = "BIGINT";
    case INT = "INT";
    case INT_TINY = "TINYINT";

    case BLOB_LONG = "LONGBLOB";
    case BLOB = "BLOB";
    case BLOB_TINY = "TINYBLOB";

    case DOUBLE = "DOUBLE";
    case DECIMAL = "DECIMAL";
    case FLOAT = "FLOAT";

    case DATE = "DATE";
    case DATETIME = "DATETIME";
    case TIME = "TIME";
}

<?php

namespace Papimod\Database\pdo\enumerator;

enum Operator: string
{
    case IS_EQUAL = '=';
    case IS_GREATER = '>';
    case IS_LESS = '<';
    case IS_GREATER_OR_EQUAL = '>=';
    case IS_LESS_OR_EQUAL = '<=';
    case IS_NOT_EQUAL = '!=';
    case IS_LIKE = 'LIKE';
    case IS_NOT_LIKE = 'NOT LIKE';
    case IS_IN = 'IN';
    case IS_NOT_IN = 'NOT IN';
    case IS_NULL = 'IS NULL';
    case IS_NOT_NULL = 'IS NOT NULL';
}

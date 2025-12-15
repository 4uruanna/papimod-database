<?php

namespace Papimod\Database\sql\enumerator;

final class Operator
{
    public const EQUAL = '=';
    public const GREATER = '>';
    public const LESS = '<';
    public const GREATER_EQUAL = '>=';
    public const LESS_EQUAL = '<=';
    public const NOT_EQUAL = '!=';
    public const LIKE = 'LIKE';
    public const NOT_LIKE = 'NOT LIKE';
    public const IN = 'IN';
    public const NOT_IN = 'NOT IN';
    public const IS_NULL = 'IS NULL';
    public const IS_NOT_NULL = 'IS NOT NULL';
}

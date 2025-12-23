<?php

namespace Papimod\Database\pdo\enumerator;

enum Join: string
{
    case INNER = 'INNER';
    case LEFT = 'LEFT';
    case RIGHT = 'RIGHT';
    case FULL = 'FULL OUTER';
}

<?php

declare(strict_types=1);

namespace SqlModels\Enums;

enum ComparisonOperator: string
{
    case Equal        = '=';
    case Like         = 'LIKE';
    case Greater      = '>';
    case GreaterEqual = '>=';
    case Less         = '<';
    case LessEqual    = '<=';
}//end enum

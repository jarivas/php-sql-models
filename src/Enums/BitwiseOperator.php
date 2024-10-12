<?php

declare(strict_types=1);

namespace SqlModels\Enums;

enum BitwiseOperator: string
{
    case And         = '&';
    case Or          = '|';
    case ExclusiveOr = '^';
}//end enum

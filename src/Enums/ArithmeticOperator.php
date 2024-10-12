<?php

declare(strict_types=1);

namespace SqlModels\Enums;

enum ArithmeticOperator: string
{
    case Add      = '+';
    case Subtract = '-';
    case Multiply = '*';
    case Divide   = '/';
    case Modulo   = '%';
}//end enum

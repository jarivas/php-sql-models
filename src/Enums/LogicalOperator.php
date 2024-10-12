<?php

declare(strict_types=1);

namespace SqlModels\Enums;

enum LogicalOperator: string
{
    case And     = 'AND';
    case Or      = 'OR';
    case In      = 'IN';
    case Between = 'BETWEEN';
}//end enum

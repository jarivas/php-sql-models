<?php

declare(strict_types=1);

namespace SqlModels;

enum PhpTypes : string
{
    case Array   = 'array';
    case String  = 'string';
    case Float   = 'float';
    case Integer = 'int';
    case Bool    = 'bool';
    case Mixed   = 'mixed';
}//end enum

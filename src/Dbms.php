<?php

declare(strict_types=1);

namespace SqlModels;

enum Dbms : string
{
    case Sqlite = 'sqlite';
    case Mysql  = 'mysql';
    case Pgsql  = 'pgsql';
}//end enum

<?php

declare(strict_types=1);

namespace SqlModels\Tests\Sqlite\Models;

enum Dbms : string
{
    case Sqlite = 'sqlite';
    case Mysql  = 'mysql';
    case Pgsql  = 'pgsql';
    case Mssql  = 'mssql';
}//end enum

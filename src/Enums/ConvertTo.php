<?php


declare(strict_types=1);

namespace SqlModels\Structs;

interface ConvertTo
{


    public function toSqlFormat(): string;


}//end interface

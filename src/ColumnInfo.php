<?php

declare(strict_types=1);

namespace SqlModels;

class ColumnInfo
{


    public function __construct(public string $name='', public string $typeName='', public bool $nullable=true)
    {

    }//end __construct()


}//end class

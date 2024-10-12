<?php

declare(strict_types=1);

namespace SqlModels\Structs;

class Column
{


    public function __construct(
        public string $name,
        public string $typeName,
        public bool $nullable=true,
        public mixed $value=null)
    {

    }//end __construct()


}//end class

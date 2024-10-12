<?php

declare(strict_types=1);

namespace SqlModels\Structs;

class Condition
{


    public function __construct(
        public string $column,
        public string $operator,
        public mixed $value)
    {

    }//end __construct()


}//end class

<?php

declare(strict_types=1);

namespace SqlModels\Structs;

use SqlModels\Enums\Join as JoinEnum;

class Join
{


    public function __construct(
        public JoinEnum $type,
        public string $table2,
        public string $columnTable1,
        public string $columnTable2)
    {

    }//end __construct()


}//end class

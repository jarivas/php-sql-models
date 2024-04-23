<?php

declare(strict_types=1);

namespace SqlModels;

class TableInfo
{


    /**
     * @param    string        $name
     * @param    array<ColumnInfo> $columns
     */
    public function __construct(public string $name='', public array $columns=[])
    {

    }//end __construct()


}//end class

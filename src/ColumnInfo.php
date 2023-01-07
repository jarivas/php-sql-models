<?php

declare(strict_types=1);

namespace SqlModels;

class ColumnInfo
{


    /**
     * @param    string        $name
     * @param    string        $type
     */
    public function __construct(public string $name='', public string $type='')
    {

    }//end __construct()


}//end class
